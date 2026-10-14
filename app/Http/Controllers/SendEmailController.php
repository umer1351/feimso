<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Mail\SetEmailData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('location');
    }

    function send(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        $data = array(
            'name' => $request->name,
            'message' => $request->message
        );
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new SendMail($data, $request->email));
        return back()->with('success_contact', 'Thanks for contacting us!');
    }

    function index()
    {
        return view('send_email');
    }

    /**
     * Order emails built by the storefront JS. This used to accept any
     * subject/body/recipients from anyone (an open mail relay). Now: signed in
     * only, rate limited, and every recipient must be an existing account
     * (the customer and the store owner are).
     */
    function sendMail(Request $request)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::check(), 401);
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array|max:3',
            'recipients.*' => 'email',
        ]);
        $recipients = array_values(array_unique(array_map('strtolower', $data['recipients'])));
        $known = \App\Models\User::whereIn('email', $recipients)->count();
        abort_unless($known === count($recipients), 422, 'Unknown recipient');

        $message = base64_decode($data['message'], true);
        abort_if($message === false, 422);
        Mail::to($recipients)->send(new SetEmailData($data['subject'], $message));
        return "email sent successfully!";
    }
}

?>