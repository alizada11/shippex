<?php

namespace App\Controllers;

use App\Controllers\Admin\DeliveredToday;
use App\Models\DeliveredTodayModel;
use App\Models\HeroSectionModel;
use App\Models\HowItWorksModel;
use App\Models\LocationModel;
use App\Models\PagesModel;
use App\Models\PromoCardModel;
use App\Models\VirtualAddressModel;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class Home extends BaseController
{
    protected $token;
    public function __construct()
    {

        $this->token = getenv('EASYSHIP_API_TOKEN');
        if (!$this->token) {
            log_message('error', 'Easyship API token is not set.');
            throw new \Exception('Easyship API token is missing.');
        }
    }
    public function index(): string
    {
        $heroModel = new HeroSectionModel();
        $data['hero'] = $heroModel->first();
        $howItWorkModel = new HowItWorksModel();
        $data['steps']  = $howItWorkModel->orderBy('id')->findAll();
        $locationsModel = new LocationModel();
        $data['locations'] = $locationsModel->orderBy('id')->findAll();
        $deliveredModel = new DeliveredTodayModel();
        $data['items'] = $deliveredModel->orderBy('id')->findAll();
        $promoModel = new PromoCardModel();

        $data['promoCards'] = $promoModel->findAll();


        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($this->token),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json; version=2024-09',
                ],
                'timeout' => 10, // optional: fail faster if API is unresponsive
            ]);

            $catss = json_decode($response->getBody(), true);
            $categories = $catss['item_categories'] ?? [];
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
        // Extract categories
        $data['categories'] = $categories;

        return view('home', $data);
    }
    public function view($id)
    {
        $pageModel = new PagesModel();
        $page = $pageModel->find($id);
        echo $page['content'];
    }




    public function warehouses()
    {
        $whs = new VirtualAddressModel();
        $whs = $whs->findAll();
        return view('warehouses', ['warehouses', $whs]);
    }
}
