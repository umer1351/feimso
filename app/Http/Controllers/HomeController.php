<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $route = \Route::currentRouteName();
        if (!isset($_COOKIE['section_id']) && !isset($_COOKIE['address_name']) && $route != "set-location") {
            \Redirect::to('set-location')->send();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'Parcel Delivery Service') {
            return view('home_page.parcel_home');
        } else if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'Rental Service') {
            return view('home_page.rental_home');
        } else if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'Ecommerce Service') {
            return view('home_page.ecommerce_home');
        } else if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'Multivendor Delivery Service') {
            return view('home_page.multivendor_home');
        } else if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'Cab Service') {
            return view('home_page.cab_home');
        } else if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == 'On Demand Service') {
            return view('home_page.ondemand_home');
        }
    }

    public function setLocation()
    {
        return view('layer');
    }

    /**
     * Used to let the browser upload the Firebase service-account file, which
     * the server then fetched with file_get_contents() on a client-supplied
     * path. Firebase is gone, and that was a file-read/SSRF hole, so it is a no-op.
     */
    public function storeServiceFile(Request $request)
    {
        return response()->noContent();
    }
}