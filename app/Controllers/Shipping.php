<?php

namespace App\Controllers;

use App\Models\ShippingBookingModel;
use App\Models\UserModel;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Services\EasyshipService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class Shipping extends BaseController
{
    public function form()
    {
        return view('shipping/form');
    }
    protected $shippingModel;
    protected $paypalClient;
    protected $token;

    public function __construct()
    {

        $this->token = getenv('EASYSHIP_API_TOKEN');
        if (!$this->token) {
            log_message('error', 'Easyship API token is not set.');
            throw new \Exception('Easyship API token is missing.');
        }
        $this->shippingModel = new ShippingBookingModel();
        $clientId = getenv('PAYPAL_CLIENT_ID');
        $clientSecret = getenv('PAYPAL_SECRET');
        $environment = new SandboxEnvironment($clientId, $clientSecret); // use LiveEnvironment for production
        $this->paypalClient = new PayPalHttpClient($environment);
    }

    public function getRates()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }

        $body = json_decode($this->request->getBody(), true);
        if (!$body) {
            return $this->response->setJSON(['error' => ['message' => 'Invalid JSON body']]);
        }

        // Normalize inputs & defaults
        $origin = $body['origin_address'] ?? [];
        $dest   = $body['destination_address'] ?? [];
        $parcels = $body['parcels'] ?? [];
        $incoterms = $body['incoterms'] ?? 'DDU';

        $isInsured = false;
        if (isset($body['insurance']['is_insured'])) {
            $isInsured = filter_var($body['insurance']['is_insured'], FILTER_VALIDATE_BOOLEAN);
        }

        // Validate minimal required fields
        if (
            empty($origin['line_1']) || empty($origin['city']) || empty($origin['country_alpha2']) ||
            empty($dest['line_1']) || empty($dest['city']) || empty($dest['country_alpha2'])
        ) {
            return $this->response->setStatusCode(422)->setJSON(['error' => ['message' => 'Missing required origin/destination fields']]);
        }

        // Ensure parcels structure
        if (empty($parcels) || !is_array($parcels)) {
            // build a default parcel from single item fields if provided (compat fallback)
            $parcels = [[
                'total_actual_weight' => floatval($body['weight'] ?? 1),
                'box' => [
                    'length' => floatval($body['length'] ?? 0),
                    'width'  => floatval($body['width'] ?? 0),
                    'height' => floatval($body['height'] ?? 0),
                ],
                'items' => [[
                    'quantity' => 1,
                    'declared_currency' => $body['declared_currency'] ?? 'USD',
                    'declared_customs_value' => floatval($body['declared_customs_value'] ?? 0),
                    'actual_weight' => floatval($body['weight'] ?? 1),
                    'display_weight_unit' => 'kg',
                    'hs_code' => $body['hs_code'] ?? null,
                    'category' => $body['category'] ?? null,
                    'set_as_residential' => isset($dest['set_as_residential']) ? (bool)$dest['set_as_residential'] : false
                ]]
            ]];
        }

        // Build final payload according to Easyship public API spec (2024-09)
        $payload = [
            'origin_address' => [
                'line_1' => $origin['line_1'] ?? '',
                'line_2' => $origin['line_2'] ?? null,
                'state'  => $origin['state'] ?? null,
                'city'   => $origin['city'] ?? '',
                'postal_code' => $origin['postal_code'] ?? null,
                'country_alpha2' => $origin['country_alpha2'] ?? ''
            ],
            'destination_address' => [
                'line_1' => $dest['line_1'] ?? '',
                'line_2' => $dest['line_2'] ?? null,
                'state'  => $dest['state'] ?? null,
                'city'   => $dest['city'] ?? '',
                'postal_code' => $dest['postal_code'] ?? null,
                'country_alpha2' => $dest['country_alpha2'] ?? '',
                'delivery_instructions' => $dest['delivery_instructions'] ?? null,
                'set_as_residential' => isset($dest['set_as_residential']) ? (bool)$dest['set_as_residential'] : false
            ],
            'incoterms' => in_array($incoterms, ['DDU', 'DDP']) ? $incoterms : 'DDU',
            'insurance' => [
                'is_insured' => $isInsured
            ],
            'parcels' => $parcels,
            // optional: prefer currency passed from client
            'shipping_settings'   => (object) ($body['shipping_settings'] ?? []),
            'courier_settings'    => (object) ($body['courier_settings'] ?? [])


        ];

        try {
            $client = new \GuzzleHttp\Client();

            // Use your token storage: replace with $this->token if you have it
            $token = $this->token;

            $response = $client->post('https://public-api.easyship.com/2024-09/rates', [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($token),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json; version=2024-09',
                ],
                'json' => $payload,
                'http_errors' => false,
                'timeout' => 30,
            ]);

            $status = $response->getStatusCode();
            $body = (string)$response->getBody();
            // Return upstream body as-is (decoded)
            $decoded = json_decode($body, true);

            return $this->response->setStatusCode($status)->setJSON($decoded);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $msg = $e->getMessage();
            if ($e->hasResponse()) {
                $msgBody = (string)$e->getResponse()->getBody();
                $decoded = json_decode($msgBody, true);
                return $this->response->setStatusCode(502)->setJSON(['error' => $decoded ?? ['message' => $msg]]);
            }
            return $this->response->setStatusCode(500)->setJSON(['error' => ['message' => $msg]]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => ['message' => $e->getMessage()]]);
        }
    }



    public function book()
    {
        // $request = service('request');
        // echo "<pre>";
        // print_r($request->getPost());
        // echo "<pre>";
        // exit;
        $session = session();
        // Check if user is logged in
        if (!$session->has('user_id')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Please login first, to book it.&nbsp; <a class="btn bg-shippex-purple text-white" href="' . base_url('/login') . '">Login</a>'
            ]);
        } else {
            $user_id = session()->get('user_id');
        }
        $request = service('request');


        $data = [
            // Origin
            'origin_line_1'       => $request->getPost('origin_line_1'),
            'origin_city'         => $request->getPost('origin_city'),
            'origin_state'        => $request->getPost('origin_state'),
            'origin_postal'       => $request->getPost('origin_postal_code'),
            'origin_country'      => $request->getPost('origin_country'),

            // Destination
            'dest_line_1'         => $request->getPost('dest_line_1'),
            'dest_city'           => $request->getPost('dest_city'),
            'dest_state'          => $request->getPost('dest_state'),
            'dest_postal'         => $request->getPost('dest_postal_code'),
            'dest_country'        => $request->getPost('dest_country'),

            // Parcel
            'weight'              => $request->getPost('weight'),
            'length'              => $request->getPost('length'),
            'width'               => $request->getPost('width'),
            'height'              => $request->getPost('height'),
            'category'            => $request->getPost('category'),

            // Easyship rate info
            'courier_name'        => $request->getPost('courier_name'),
            'service_name'        => $request->getPost('service_name'),
            'delivery_time'       => $request->getPost('delivery_time'),
            'currency'            => $request->getPost('currency'),
            'total_charge'        => $request->getPost('total_charge'),
            'description'         => $request->getPost('description'),
            'set_rate'            => $request->getPost('set_rate'),

            // New fields
            'hs_code'             => $request->getPost('hs_code'),
            'declared_customs_value' => $request->getPost('declared_customs_value'),
            'declared_currency'   => $request->getPost('declared_currency'),
            'is_insured'          => $request->getPost('is_insured') ? 1 : 0,
            'insured_amount'      => $request->getPost('insured_amount'),
            'incoterms'           => $request->getPost('incoterms'),
            'set_as_residential'  => $request->getPost('set_as_residential') ? 1 : 0,
            'tax_duty'            => $request->getPost('tax_duty'),

            // User
            'user_id'             => $user_id,
        ];



        $shippingModel = new \App\Models\ShippingBookingModel();
        $id = $shippingModel->insert($data);

        if ($id) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking saved successfully',
                'booking_id' => $id,
                'role' => session()->get('role')
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to save booking'
            ]);
        }
    }

    public function shippingRates()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($this->token),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json; version=2024-09',
                ],
                'timeout' => 10, // optional: fail faster if API is unresponsive
            ]);

            $data = json_decode($response->getBody(), true);
            $categories = $data['item_categories'] ?? [];
        } catch (ConnectException $e) {
            // Network or timeout issue
            $categories = [];
            $errorMessage = 'Unable to connect to the shipping service right now. Please try again later.';
        } catch (RequestException $e) {
            // API responded with 4xx/5xx error
            $categories = [];
            $errorMessage = 'We are unable to retrieve shipping rates at the moment. Please try again shortly.';
        } catch (\Exception $e) {
            // Catch-all for unexpected errors
            $categories = [];
            $errorMessage = 'An unexpected error occurred while fetching shipping information.';
        }

        // Pass message to the view
        return view('shipping-rates', [
            'title' => 'Calculator',
            'categories' => $categories,
            'error' => $errorMessage ?? null,
        ]);
    }


    public function requests()
    {
        helper('app');
        $model = new ShippingBookingModel();

        $data['requests'] = $model->orderBy('created_at', 'DESC')->paginate(12);
        $data['pager'] = $model->pager;
        $data['title'] = 'Shipping Requests';
        return view('admin/shipping/index', $data);
    }

    public function details($id)
    {
        helper('app');

        $historyModel = new \App\Models\BookingStatusHistoryModel();
        $model = new ShippingBookingModel();
        $data['request'] = $model->where('id', $id)->first();
        $data['history'] = $historyModel
            ->where('book_id', $id)
            ->orderBy('changed_at', 'AESC')
            ->findAll();
        // dd($data);
        $data['title'] = 'Shipping Req Details';
        return view('admin/shipping/details', $data);
    }


    public function updateStatus($bookingId)
    {
        $json = $this->request->getJSON(true); // true = associative array

        if (! $json || ! isset($json['new_status'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request data'
            ]);
        }

        $newStatus = $json['new_status'];

        $bookingModel = new \App\Models\ShippingBookingModel();
        $historyModel = new \App\Models\BookingStatusHistoryModel();
        $userModel = new \App\Models\UserModel();
        $packageModel = new \App\Models\PackageModel();
        $usersWarehouseModel = new \App\Models\WarehouseRequestModel();

        // Get current booking
        $booking = $bookingModel->find($bookingId);
        if (! $booking) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $oldStatus = $booking['status'];
        $statusOrder = [
            'pending'   => 1,
            'accepted'  => 2,
            'shipping'  => 3,
            'shipped'   => 4,
            'delivered' => 5,
            'canceled'  => 6, // can jump to canceled if needed
        ];

        // Update booking status
        if ($newStatus === 'canceled') {

            // allow cancellation at any stage
        } else if ($newStatus === 'shipping' && empty($booking['payment_status'])) {
            $totalCharge = $booking['total_charge'];
            return $this->response->setJSON([
                'success' => false,
                'message' => "User did not payed yet. The amount to pay is <b>$</b><b>{$totalCharge}</b>"
            ]);
        } else if ($statusOrder[$newStatus] == $statusOrder[$oldStatus]) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "The status is already '{$oldStatus}' ."
            ]);
        } else if ($statusOrder[$newStatus] < $statusOrder[$oldStatus]) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Cannot change status from '{$oldStatus}' back to '{$newStatus}'."
            ]);
        }
        if ($newStatus === 'canceled') {
            // allow cancellation at any stage
            $bookingModel->update($bookingId, ['status' => $newStatus]);
        } else if ($statusOrder[$newStatus] < $statusOrder[$oldStatus]) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Cannot move status from '{$oldStatus}' back to '{$newStatus}'."
            ]);
        }

        $bookingModel->update($bookingId, ['status' => $newStatus]);

        // Log history
        $historyModel->insert([
            'book_id'    => $bookingId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => session()->get('user_id'), // optional
        ]);

        // If status is accepted → send email
        if ($newStatus === 'accepted') {
            $email = \Config\Services::email();

            $user = $userModel->find($booking['user_id']);
            $userName = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));

            $data = [
                'request'  => $booking,
                'userName' => $userName ?: 'Customer'
            ];

            $message = view('emails/shipping_payment', $data);
            $email->setFrom('info@shippex.online', 'Shippex Admin');
            $email->setTo($user['email']);
            $email->setSubject('Your Request #' . $booking['id'] . ' is Waiting for Payment');
            $email->setMessage($message);
            $email->setMailType('html');
            $email->send();
        }
        // $users_warehouse = $usersWarehouseModel->where('user_id', $booking['user_id'])->where('is_default', 1)->first();

        // $ware_house_id  =  $users_warehouse['warehouse_id'];

        // // If status is shipped → create a package
        // if ($newStatus === 'shipped') {
        //     $packageData = [
        //         'virtual_address_id' => $ware_house_id,
        //         'user_id'            => $booking['user_id'],
        //         'retailer'           => $booking['courier_name'] ?? 'Unknown',
        //         'tracking_number'    => 'PENDING-' . strtoupper(uniqid()),
        //         'length'             => $booking['length'],
        //         'width'              => $booking['width'],
        //         'height'             => $booking['height'],
        //         'weight'             => $booking['weight'],
        //         'value'              => $booking['total_charge'] ?? 0,
        //         'status'             => 'incoming',
        //         'created_at'         => date('Y-m-d H:i:s')
        //     ];

        //     $packageModel->insert($packageData);
        // }

        // Get updated history
        $history = $historyModel
            ->where('book_id', $bookingId)
            ->orderBy('changed_at', 'DESC')
            ->findAll();

        // Return JSON response for SweetAlert
        return $this->response->setJSON([
            'success' => true,
            'message' => $newStatus === 'shipped'
                ? 'Status updated to "shipped" and package created successfully.'
                : "Status updated to {$newStatus}.",
            'history' => $history
        ]);
    }



    public function updateLabel($id)
    {
        $model = new ShippingBookingModel();
        $file = $this->request->getFile('label');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'images/labels', $newName); // saves to writable/uploads/invoices

            $model->update($id, ['label' => $newName]);

            $shippingModel = new ShippingBookingModel();
            $result = $shippingModel->where('id', $id)->first();

            $user_id = $result['user_id'];



            $model = new \App\Models\UserModel();
            $user = $model->where('id', $user_id)->first();

            if ($user) {

                $link['name'] = $user['firstname'] . ' ' . $user['lastname'];
                $user_email = $user['email'];

                $link['text'] = base_url("customer/shipping/details/$id");

                // html page
                $message = view('emails/download_label', $link);

                $email = \Config\Services::email();

                $email->setFrom('info@shippex.online', 'Shippex Admin');
                $email->setTo($user_email);
                $email->setSubject('Download Your Labe');
                $email->setMessage($message);
                $email->setMailType('html'); // Important

                $email->send();
            }
            return redirect()->to('/shipping/details/' . $id)->with('success', 'Label uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Please select a valid file.');
    }
    public function deleteLabel($id)
    {
        $model = new \App\Models\ShippingBookingModel();

        // Fetch the record
        $booking = $model->find($id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        // Check if a label file exists
        if (!empty($booking['label'])) {
            $filePath = FCPATH . 'images/labels/' . $booking['label'];

            // Delete file from the server if it exists
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        // Update database (set label to null)
        $model->update($id, ['label' => null]);

        return redirect()->to('/shipping/details/' . $id)->with('success', 'Label deleted successfully.');
    }

    public function updateInvoice($id)
    {
        $model = new ShippingBookingModel();
        $file = $this->request->getFile('purchase_invoice');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'images/invoices', $newName); // saves to writable/uploads/invoices

            $model->update($id, ['purchase_invoice' => $newName]);

            return redirect()->to('/customer/shipping/details/' . $id)->with('success', 'Invoice uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Please select a valid file.');
    }

    public function pay($id)
    {
        $model = new ShippingBookingModel();
        $requestData = $model->find($id);

        if (!$requestData || $requestData['status'] !== 'accepted') {
            return redirect()->back()->with('error', 'Payment not allowed.');
        }

        // PayPal client
        $clientId = getenv('PAYPAL_CLIENT_ID');
        $clientSecret = getenv('PAYPAL_SECRET');
        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);

        // Create order
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "value" => $requestData['amount'],
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "return_url" => base_url('/shipping/complete/' . $id),
                "cancel_url" => base_url('/shipping/cancel/' . $id)
            ]
        ];

        try {
            $response = $client->execute($request);
            return redirect()->to($response->result->links[1]->href); // redirect to PayPal checkout
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Endpoint for JS SDK createOrder
    public function createOrder($id)
    {
        $model = new ShippingBookingModel();
        $requestData = $model->find($id);

        if (!$requestData || $requestData['status'] !== 'accepted') {
            return $this->response->setJSON(['error' => 'Payment not allowed']);
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "value" => $requestData['total_charge'],
                    "currency_code" => "USD"
                ]
            ]]
        ];

        try {
            $response = $this->paypalClient->execute($request);
            return $this->response->setJSON(['id' => $response->result->id]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    // Endpoint for JS SDK onApprove
    public function captureOrder($id)
    {
        $orderID = $this->request->getPost('orderID');
        if (!$orderID) {
            return $this->response->setJSON(['error' => 'Order ID missing']);
        }

        $request = new OrdersCaptureRequest($orderID);
        $request->prefer('return=representation');

        try {
            $response = $this->paypalClient->execute($request);

            // Update booking status
            $this->shippingModel->update($id, ['status' => 'shipping', 'payment_status' => 'paid', 'payment_info' => json_encode($response->result)]);

            return $this->response->setJSON(['status' => 'success', 'details' => $response->result]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }


    public function complete($id)
    {
        $data = [
            'payment_status' => 'paid', // or 'completed'
            'status' => 'accepted'      // or whatever your workflow requires
        ];

        if ($this->shippingModel->update($id, $data)) {
            return redirect()->to(base_url('shipping/view/' . $id))
                ->with('success', 'Payment completed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update payment status.');
        }
    }

    public function cancel($id)
    {
        $data = [
            'payment_status' => 'canceled', // mark payment canceled
            'status' => 'canceled'          // mark order canceled
        ];

        if ($this->shippingModel->update($id, $data)) {
            return redirect()->to(base_url('shipping/view/' . $id))
                ->with('success', 'Payment has been canceled.');
        } else {
            return redirect()->back()->with('error', 'Failed to cancel payment.');
        }
    }
    public function destroy($id)
    {
        $shippingModel = new ShippingBookingModel();

        $shipping = $shippingModel->find($id);
        if (! $shipping) {
            return redirect()->back()->with('error', 'Shipping request not found.');
        }

        $shippingModel->delete($id);

        return redirect()->back()->with('success', 'Shipping request deleted successfully.');
    }
    public function notify_user($id)
    {

        $email = \Config\Services::email();

        // Prepare data for email
        $shippingModel = new ShippingBookingModel();
        $userModel = new UserModel();

        // Fetch main request
        $request = $shippingModel->find($id);
        $userName = $userModel->find($request['user_id'])['firstname'] . ' ' . $userModel->find($request['user_id'])['lastname'];
        $data = [
            'request' => $request, // the $request row
            'userName' => $userName ?? 'Customer'
        ];

        $message = view('emails/notify_shipping_payment', $data);
        $email->setFrom('info@shippex.online', 'Shippex Admin'); // MUST be your Hostinger email
        $email->setTo($userModel->find($request['user_id'])['email']);
        $email->setSubject('Your Request #' . $request['id'] . ' is Waiting for Purchase Invoice');
        $email->setMessage($message);
        $email->setMailType('html'); // Important

        if ($email->send()) {

            return redirect()->back()->with('success', 'Email has been sent successfully.');
        } else {
            log_message('error', $email->printDebugger(['headers']));
            return redirect()->back()->with('error', 'There has been an error while sending email.');
        }
    }
}
