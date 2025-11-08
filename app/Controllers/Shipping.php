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
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();

            try {
                $client = new \GuzzleHttp\Client();

                $payload = [
                    "origin_address" => [
                        "line_1"        => $data['origin_line_1'] ?? '',
                        "state"         => $data['origin_state'] ?? '',
                        "city"          => $data['origin_city'] ?? '',
                        "postal_code"   => $data['origin_postal_code'] ?? '',
                        "country_alpha2" => $data['origin_country'] ?? '',
                    ],
                    "destination_address" => [
                        "line_1"        => $data['dest_line_1'] ?? '',
                        "state"         => $data['dest_state'] ?? '',
                        "city"          => $data['dest_city'] ?? '',
                        "postal_code"   => $data['dest_postal_code'] ?? '',
                        "country_alpha2" => $data['dest_country'] ?? '',
                    ],
                    "incoterms" => "DDU",
                    "parcels" => [[
                        "total_actual_weight" => (float)($data['weight'] ?? 1),
                        "items" => [[
                            "origin_country_alpha2" => $data['origin_country'] ?? '',
                            "quantity" => 1,
                            "declared_currency" => "USD",
                            "description" => "Test item",
                            "category" => $data['category'],
                            "hs_code" => $data['hs_code'], // example HS code (T-shirts)
                            "actual_weight" => (float)($data['weight'] ?? 1),
                            "dimensions" => [
                                "length" => (float)($data['length'] ?? 10),
                                "width"  => (float)($data['width'] ?? 10),
                                "height" => (float)($data['height'] ?? 10),
                            ],
                            "declared_customs_value" => 20
                        ]]
                    ]]
                ];

                $response = $client->post('https://public-api.easyship.com/2024-09/rates', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . trim($this->token),
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json; version=2024-09',
                    ],
                    'json' => $payload,  // ← let Guzzle handle encoding
                    'http_errors' => false,
                ]);


                $rates = json_decode($response->getBody(), true);
                header('Content-Type: application/json');
                echo json_encode($rates);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function book()
    {
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
            'origin_line_1'  => $request->getPost('origin_line_1'),
            'origin_city'    => $request->getPost('origin_city'),
            'origin_state'   => $request->getPost('origin_state'),
            'origin_postal'  => $request->getPost('origin_postal_code'),
            'origin_country' => $request->getPost('origin_country'),

            // Destination
            'dest_line_1'    => $request->getPost('dest_line_1'),
            'dest_city'      => $request->getPost('dest_city'),
            'dest_state'     => $request->getPost('dest_state'),
            'dest_postal'    => $request->getPost('dest_postal_code'),
            'dest_country'   => $request->getPost('dest_country'),

            // Parcel
            'weight'         => $request->getPost('weight'),
            'length'         => $request->getPost('length'),
            'width'          => $request->getPost('width'),
            'height'         => $request->getPost('height'),
            'category'       => $request->getPost('category'),
            // Easyship rate info
            'courier_name'   => $request->getPost('courier_name'),
            'service_name'   => $request->getPost('service_name'),
            'delivery_time'  => $request->getPost('delivery_time'),
            'currency'       => $request->getPost('currency'),
            'total_charge'   => $request->getPost('total_charge'),
            'description'    => $request->getPost('description'),
            //user
            'user_id'        => $user_id,
        ];


        $shippingModel = new \App\Models\ShippingBookingModel();
        $id = $shippingModel->insert($data);

        if ($id) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking saved successfully',
                'booking_id' => $id
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

            $email->setTo($user['email']);
            $email->setSubject('Your Request #' . $booking['id'] . ' is Waiting for Payment');
            $email->setMessage($message);
            $email->setMailType('html');
            $email->send();
        }
        $users_warehouse = $usersWarehouseModel->where('user_id', $booking['user_id'])->where('is_default', 1)->first();

        $ware_house_id  =  $users_warehouse['warehouse_id'];

        // If status is shipped → create a package
        if ($newStatus === 'shipped') {
            $packageData = [
                'virtual_address_id' => $ware_house_id,
                'user_id'            => $booking['user_id'],
                'retailer'           => $booking['courier_name'] ?? 'Unknown',
                'tracking_number'    => 'PENDING-' . strtoupper(uniqid()),
                'length'             => $booking['length'],
                'width'              => $booking['width'],
                'height'             => $booking['height'],
                'weight'             => $booking['weight'],
                'value'              => $booking['total_charge'] ?? 0,
                'status'             => 'incoming',
                'created_at'         => date('Y-m-d H:i:s')
            ];

            $packageModel->insert($packageData);
        }

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

        $email->setTo($userModel->find($request['user_id'])['email']);
        $email->setSubject('Your Request #' . $request['id'] . ' is Waiting for Purchase Invoice');
        $email->setMessage($message);
        $email->setMailType('html'); // Important

        if ($email->send()) {

            return redirect()->back()->with('success', 'Email has been setn successfully.');
        } else {
            log_message('error', $email->printDebugger(['headers']));
            return redirect()->back()->with('error', 'There has been an error while sending email.');
        }
    }
}
