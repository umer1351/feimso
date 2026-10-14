<?php

namespace App\Http\Controllers;

use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('location');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id, \App\Storefront\StorePage $page)
    {
        $data = $page->load((string) $id);
        abort_if($data === null, 404);
        $cart = session()->get('cart', []);
        return view('vendor.vendor', ['cart' => $cart, 'id' => $id] + $data);
    }

    /** "See all" categories for the current section. */
    public function categoryList(Request $request)
    {
        $sectionId = (string) $request->cookie('section_id', $_COOKIE['section_id'] ?? '');
        $categories = \App\Helpers\FirestoreHelper::query('vendor_categories', [['section_id', '==', $sectionId], ['publish', '==', true]]);
        usort($categories, fn ($a, $b) => strnatcasecmp($a['data']['title'] ?? '', $b['data']['title'] ?? ''));
        $isEcommerce = ($_COOKIE['service_type'] ?? '') === 'Ecommerce Service';
        return view('vendor.categorylist', compact('categories', 'isEcommerce'));
    }

    /**
     * A category's page. The store/product listings already exist
     * (allvendors/bycategory, products/list), so send the visitor there.
     */
    public function categoryDetail($id)
    {
        return ($_COOKIE['service_type'] ?? '') === 'Ecommerce Service'
            ? redirect()->route('productlist', ['category', $id])
            : redirect()->route('vendorsbycategory', $id);
    }

    public function sendnotification(Request $request)
    {
       if(Storage::disk('local')->has('firebase/credentials.json')){
            
            $client= new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];

            $fcm_token = $request->fcm;
            
            if(!empty($access_token) && !empty($fcm_token)){

                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';

                $data = [
                    'message' => [
                        'notification' => [
                            'title' => $request->subject,
                            'body' => $request->message,
                        ],
                        'data' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => '1',
                            'status' => 'done',
                        ],
                        'token' => $fcm_token,
                    ],
                ];

                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result=json_decode($result);

                $response = array();
                $response['success'] = true;
                $response['message'] = 'Notification successfully sent.';
                $response['result'] = $result;

            }else{
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing sender id or token to send notification.';
            }

        }else{
            $response = array();
            $response['success'] = false;
            $response['message'] = 'Firebase credentials file not found.';
        }
       
        return response()->json($response);
    }
}