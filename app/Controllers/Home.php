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

        $response = $client->request('GET', 'https://public-api.easyship.com/2024-09/item_categories', [
            'headers' => [
                'Authorization' => 'Bearer ' . trim($this->token),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json; version=2024-09',
            ],
        ]);

        // Decode JSON into associative array
        $cats = json_decode($response->getBody(), true);

        // Extract categories
        $data['categories'] = $cats['item_categories'] ?? [];

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
