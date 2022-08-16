<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\ClientManager;
use App\InvoiceEmail;

use Auth;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            $host = $request->getHttpHost();

            if (Str::contains($host, 'planontrack')) {
                return view('auth.enterpriselogin4', compact('host'));
            } else {
                auth()->logout();
                return view('home');
                //return view('login');
            }
        }
    }


    public function messageus(Request $request)
    {
        $this->validate(request(), [
            'fname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $details = [
            'fname' => $request->get('fname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'message' => $request->get('message'),
        ];

        Mail::to('sales@planaji.com')->send(new \App\Mail\SendMessageMail($details));

        return response()->json(['success' => 'Successfully email sent!']);
    }
    // public function getmail()
    // {
    //     $email = 'axialinvoice@planaji.com';
    //     $password = ';k3ytE9)G)aq';

    //     $oClient = \Webklex\IMAP\Facades\Client::make([
    //         'host'  => env('IMAP_HOST', 'mail.planaji.com'),
    //         'port'  => env('IMAP_PORT', 993),
    //         'protocol'  => env('IMAP_PROTOCOL', 'imap'), //might also use imap, [pop3 or nntp (untested)]
    //         'encryption'    => env('IMAP_ENCRYPTION', 'ssl'), // Supported: false, 'ssl', 'tls', 'notls', 'starttls'
    //         'validate_cert' => env('IMAP_VALIDATE_CERT', true),
    //         'username' => $email,
    //         'password' => $password,
    //         'authentication' => env('IMAP_AUTHENTICATION', null),
    //         'proxy' => [
    //             'socket' => null,
    //             'request_fulluri' => false,
    //             'username' => 'null',
    //             'password' => null,
    //         ]
    //     ]);

    //     // $oClient = Client::account('default'); // defined in config/imap.php
    //     $oClient->connect();
    //     // dd($oClient->connect());

    //     $folders = $oClient->getFolders();


    //     // get all unseen messages from folder INBOX
    //     // $aMessage = $oClient->getUnseenMessages($oClient->getFolder('INBOX'));

    //     foreach($folders as $folder){

    //     //Get all Messages of the current Mailbox $folder
    //     /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
    //     $messages = $folder->query()->unseen()->get();

    //     /** @var \Webklex\PHPIMAP\Message $message */
    //     foreach($messages as $message){

    //         $InvoiceEmail = InvoiceEmail::where('uuid', '=', $message->getUid())->first();
    //         if ($InvoiceEmail === null) {

    //             $invoiceemail= new InvoiceEmail;
    //             $invoiceemail->uuid = $message->getUid();
    //             $invoiceemail->plan_manager_id = '1';
    //             $invoiceemail->subject = $message->getSubject();
    //             $invoiceemail->body = $message->getTextBody();
    //             $invoiceemail->received_date = $message->getDate()[0];
    //             $invoiceemail->from_email = $message->getFrom()[0]->mail;

    //             $uuid = $message->getUid();
    //             $aAttachment = $message->getAttachments();
    //             $fileNameToStore;
    //             $aAttachment->each(function ($oAttachment) use(&$fileNameToStore){
    //                 /** @var \Webklex\IMAP\Attachment $oAttachment */
    //                 $fileNameToStore = $oAttachment->getName();
    //                 file_put_contents(public_path('assets/invoice/'. $fileNameToStore), $oAttachment->content);
    //             });
    //             $invoiceemail->attachment = $fileNameToStore;
    //             $invoiceemail->save();
    //         }

    //         //Move the current Message to 'INBOX.read'
    //         // if($message->move('INBOX.read') == true){
    //         //     echo 'Message has ben moved';
    //         // }else{
    //         //     echo 'Message could not be moved';
    //         // }
    //         }
    //     }
    // }
}
