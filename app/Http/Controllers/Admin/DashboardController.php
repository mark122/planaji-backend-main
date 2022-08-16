<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
use App\PlanmanagerSubscription;
use App\InvoiceEmail;
use AWS\CRT\HTTP\Message;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;

class DashboardController extends Controller
{
    private $connection = NULL;
    private $InvoiceEmail = NULL;
    public function __construct()
    {
        $this->middleware('auth');
        $this->InvoiceEmail = new InvoiceEmail;
        $this->InvoiceEmail->connection = $this->connection;

        $this->middleware(function ($request, $next) {
            $this->connection = auth()->user()['connection']; // returns user
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        $user_data = auth()->user();
        if (empty($user_data->changed_password)) {
            return redirect('resetpassword?get_token=' . $user_data->password_token);
        }

        // return false;

        $invoiceEmailAddress = DB::connection($this->connection)->select('select planmanager_subscriptions.id, planmanager_subscriptions.plan_manager_id,
            plan_managers.invoice_email,plan_managers.emailpassword from planmanager_subscriptions
            JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
            WHERE planmanager_subscriptions.id=?', [auth()->user()->plan_manager_subscription_id]);

        $invoices = DB::connection($this->connection)->table('invoices')
            ->selectRaw(
                '
            invoices.id,
            participants.ndis_number,
            invoices.invoice_number,
            invoices.invoice_date,
            invoices.due_date,
            invoices.reference_number,
            invoices.service_provider_ABN,
            invoices.service_provider_acc_number,
            invoices.status,
            service_providers.firstname as service_provider_first_name,
            service_providers.lastname as service_provider_last_name,
            invoices.remarks,
            participants.firstname as participant_firstname,
            participants.lastname as participant_lastname,
            SUM(invoice_details.amount) as invoice_amt
            '
            )
            ->leftJoin('participants', 'participants.id', '=', 'invoices.participant_id')
            ->leftJoin('service_providers', 'service_providers.id', '=', 'invoices.serviceprovider_id')
            ->leftJoin('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->where('invoices.planmanager_subscriptions_id', '=', auth()->user()->plan_manager_subscription_id)
            ->whereNull('invoices.deleted_at')
            ->groupBy(
                'invoices.id',
                'participants.ndis_number',
                'invoices.invoice_number',
                'invoices.invoice_date',
                'invoices.due_date',
                'invoices.reference_number',
                'invoices.service_provider_ABN',
                'invoices.service_provider_acc_number',
                'invoices.status',
                'service_providers.firstname',
                'service_providers.lastname',
                'invoices.remarks',
                'participants.firstname',
                'participants.lastname'
            )->orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        $invoicesCount = DB::connection($this->connection)->select('select count(*) as count from invoices 
            LEFT JOIN planmanager_subscriptions ON planmanager_subscriptions.id = invoices.planmanager_subscriptions_id 
            WHERE invoices.deleted_at is null and planmanager_subscriptions.id=?', [auth()->user()->plan_manager_subscription_id]);


        try {
            if (isset($invoiceEmailAddress[0]->emailpassword)) {
                $email = $invoiceEmailAddress[0]->invoice_email;

                $password = Crypt::decryptString($invoiceEmailAddress[0]->emailpassword);

                $oClient = \Webklex\IMAP\Facades\Client::make([
                    'host'  => env('IMAP_HOST', 'mail.planaji.com'),
                    'port'  => env('IMAP_PORT', 993),
                    'protocol'  => env('IMAP_PROTOCOL', 'imap'), //might also use imap, [pop3 or nntp (untested)]
                    'encryption'    => env('IMAP_ENCRYPTION', 'ssl'), // Supported: false, 'ssl', 'tls', 'notls', 'starttls'
                    'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                    'username' => $email,
                    'password' => $password,
                    'authentication' => env('IMAP_AUTHENTICATION', null),
                    'proxy' => [
                        'socket' => null,
                        'request_fulluri' => false,
                        'username' => 'null',
                        'password' => null,
                    ]
                ]);

                // $oClient = Client::account('default'); // defined in config/imap.php


                // dd($oClient->connect());

                //if ($oClient->isConnected()) {
                $oClient->connect();
                //isAuthenticated = ($oClient->connect())->Isauthenticated;
                $isAuthenticated = $oClient->isConnected();

		        $folders = $oClient->getFolders();


                // get all unseen messages from folder INBOX
                // $aMessage = $oClient->getUnseenMessages($oClient->getFolder('INBOX'));

                if ($isAuthenticated) {
                    
                    foreach ($folders as $folder) {

                        //Get all Messages of the current Mailbox $folder
                        /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
                        $messages = $folder->query()->unseen()->limit(5)->get();

                        /** @var \Webklex\PHPIMAP\Message $message */
                        foreach ($messages as $message) {

                            $InvoiceEmail = DB::connection($this->connection)->table('invoice_emails')->where('uuid', '=', $message->getUid())->where('plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)->get()->first();


                            if ($InvoiceEmail === null) {

                                $invoiceemail = new InvoiceEmail;
                                $invoiceemail->uuid = $message->getUid();
                                $invoiceemail->plan_manager_id = auth()->user()->plan_manager_subscription_id;
                                $invoiceemail->subject = $message->getSubject();
                                $invoiceemail->body = $message->getTextBody();
                                $invoiceemail->received_date = $message->getDate()[0];
                                $invoiceemail->from_email = $message->getFrom()[0]->mail;
                                $invoiceemail->connection = $this->connection;

                                $uuid = $message->getUid();
                                $aAttachment = $message->getAttachments();
                                $fileNameToStore = [];
                                $content = [];
                                $aAttachment->each(function ($oAttachment) use (&$fileNameToStore, &$content) {
                                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                                    $fileNameToStore[] = $oAttachment->getName();
                                    $content[] = $oAttachment->content;
                                });

                                if (isset($fileNameToStore[0])) { // check if there is attachment file 1;
                                    $attachment_filename1 = time() . '-' . $fileNameToStore[0];
                                    $invoiceemail->attachment = $attachment_filename1;
                                    $invoiceemail->attachment_url = Storage::disk('s3')->url($attachment_filename1);
                                    $filePath = (Str::contains($email, 'planontrack')) ? 'plan_on_track/invoice_files/' . $attachment_filename1 : 'planaji/invoice_files/' . $attachment_filename1;
                                    Storage::disk('s3')->put($filePath, $content[0], 'private');
                                }
                                if (isset($fileNameToStore[1])) { // check if there is attachment file 2;
                                    $attachment_filename2 = time() . '-' . $fileNameToStore[1];
                                    $invoiceemail->attachment2 = $attachment_filename2;
                                    $invoiceemail->attachment2_url = Storage::disk('s3')->url($attachment_filename2);
                                    $filePath = (Str::contains($email, 'planontrack')) ? 'plan_on_track/invoice_files/' . $attachment_filename2 : 'planaji/invoice_files/' . $attachment_filename2;
                                    Storage::disk('s3')->put($filePath, $content[1], 'private');
                                }

                                $invoiceemail->save();
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $isAuthenticated = false;
        } finally {
            $invoiceEmail = DB::connection($this->connection)->select('select planmanager_subscriptions.id, planmanager_subscriptions.plan_manager_id,
            invoice_emails.id,invoice_emails.plan_manager_id, invoice_emails.subject,
            invoice_emails.body, invoice_emails.received_date,invoice_emails.attachment,invoice_emails.attachment2,invoice_emails.from_email, plan_managers.invoice_email
            from planmanager_subscriptions JOIN invoice_emails ON planmanager_subscriptions.plan_manager_id = invoice_emails.plan_manager_id
            JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
            WHERE invoice_emails.deleted_at is null and planmanager_subscriptions.id=? order by invoice_emails.id DESC limit 10', [auth()->user()->plan_manager_subscription_id]);

            $invoiceEmailCount = DB::connection($this->connection)->select('select count(*) as count from planmanager_subscriptions JOIN invoice_emails ON planmanager_subscriptions.plan_manager_id = invoice_emails.plan_manager_id
            JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
            WHERE invoice_emails.deleted_at is null and planmanager_subscriptions.id=? group by planmanager_subscriptions.plan_manager_id', [auth()->user()->plan_manager_subscription_id]);

            return view('admin.dashboard')->with(compact(['invoiceEmail', 'invoiceEmailAddress', 'invoices', 'invoiceEmailCount', 'invoicesCount', 'isAuthenticated']));
        }
    }

    public function download($id)
    {
        $invoice_emails = DB::connection($this->connection)->table('invoice_emails')
            ->leftJoin('plan_managers', 'plan_managers.id', '=', 'invoice_emails.plan_manager_id')
            ->where('invoice_emails.id', '=', $id)
            ->where('invoice_emails.plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)
            ->get()->first();


        if (!empty($invoice_emails)) { // only belong to plan_manager 
            $headers = [
                'Content-Disposition' => 'attachment; filename="' . $invoice_emails->attachment . '"',
            ];
            $filePath = (Str::contains($invoice_emails->invoice_email, 'planontrack')) ? 'plan_on_track/invoice_files/' . $invoice_emails->attachment : 'planaji/invoice_files/' . $invoice_emails->attachment;
            return \Response::make(Storage::disk('s3')->get($filePath), 200, $headers);
        } else {
            return response()->json(['has_error' => true, 'msg' => 'Ops! Something went wrong.']);
        }
    }


    public function showDashboardEmails()
    {

        $user_data = auth()->user();
        if (empty($user_data->changed_password)) {
            return redirect('resetpassword?get_token=' . $user_data->password_token);
        }

        $invoiceEmailAddress = DB::connection($this->connection)->select('select planmanager_subscriptions.id, planmanager_subscriptions.plan_manager_id,
            plan_managers.invoice_email,plan_managers.emailpassword from planmanager_subscriptions
            JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
            WHERE planmanager_subscriptions.id=?', [auth()->user()->plan_manager_subscription_id]);

        if (isset($invoiceEmailAddress[0]->emailpassword)) {
            $email = $invoiceEmailAddress[0]->invoice_email;
            // $password = $invoiceEmailAddress[0]->emailpassword;

            // $encrypted = \Crypt::encryptString('kp-leT=zGk@b');
            // dd($encrypted);


            $password = Crypt::decryptString($invoiceEmailAddress[0]->emailpassword);

            $oClient = \Webklex\IMAP\Facades\Client::make([
                'host'  => env('IMAP_HOST', 'mail.planaji.com'),
                'port'  => env('IMAP_PORT', 993),
                'protocol'  => env('IMAP_PROTOCOL', 'imap'), //might also use imap, [pop3 or nntp (untested)]
                'encryption'    => env('IMAP_ENCRYPTION', 'ssl'), // Supported: false, 'ssl', 'tls', 'notls', 'starttls'
                'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                'username' => $email,
                'password' => $password,
                'authentication' => env('IMAP_AUTHENTICATION', null),
                'proxy' => [
                    'socket' => null,
                    'request_fulluri' => false,
                    'username' => 'null',
                    'password' => null,
                ]
            ]);

            // $oClient = Client::account('default'); // defined in config/imap.php
            $oClient->connect();
            // dd($oClient->connect());

            $folders = $oClient->getFolder('INBOX');


            // get all unseen messages from folder INBOX
            // $aMessage = $oClient->getUnseenMessages($oClient->getFolder('INBOX'));

            //foreach ($folders as $folder) {

            //Get all Messages of the current Mailbox $folder
            /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
            $messages = $folders->query()->unseen()->paginate(20, 1);

            /** @var \Webklex\PHPIMAP\Message $message */
            foreach ($messages as $message) {

                $InvoiceEmail = DB::connection($this->connection)->table('invoice_emails')->where('uuid', '=', $message->getUid())->where('plan_manager_id', '=', auth()->user()->plan_manager_subscription_id)->get()->first();


                if ($InvoiceEmail === null) {

                    $invoiceemail = new InvoiceEmail;
                    $invoiceemail->uuid = $message->getUid();
                    $invoiceemail->plan_manager_id = auth()->user()->plan_manager_subscription_id;
                    $invoiceemail->subject = $message->getSubject();
                    $invoiceemail->body = $message->getTextBody();
                    $invoiceemail->received_date = $message->getDate()[0];
                    $invoiceemail->from_email = $message->getFrom()[0]->mail;
                    $invoiceemail->connection = $this->connection;

                    $uuid = $message->getUid();
                    $aAttachment = $message->getAttachments();
                    $fileNameToStore = [];
                    $content = [];
                    $aAttachment->each(function ($oAttachment) use (&$fileNameToStore, &$content) {
                        /** @var \Webklex\IMAP\Attachment $oAttachment */
                        $fileNameToStore[] = $oAttachment->getName();
                        $content[] = $oAttachment->content;
                    });

                    if (isset($fileNameToStore[0])) { // check if there is attachment file 1;
                        $attachment_filename1 = time() . '-' . $fileNameToStore[0];
                        $invoiceemail->attachment = $attachment_filename1;
                        $invoiceemail->attachment_url = Storage::disk('s3')->url($attachment_filename1);
                        $filePath = (Str::contains($email, 'planontrack')) ? 'plan_on_track/invoice_files/' . $attachment_filename1 : 'planaji/invoice_files/' . $attachment_filename1;
                        Storage::disk('s3')->put($filePath, $content[0], 'private');
                    }
                    if (isset($fileNameToStore[1])) { // check if there is attachment file 2;
                        $attachment_filename2 = time() . '-' . $fileNameToStore[1];
                        $invoiceemail->attachment2 = $attachment_filename2;
                        $invoiceemail->attachment2_url = Storage::disk('s3')->url($attachment_filename2);
                        $filePath = (Str::contains($email, 'planontrack')) ? 'plan_on_track/invoice_files/' . $attachment_filename2 : 'planaji/invoice_files/' . $attachment_filename2;
                        Storage::disk('s3')->put($filePath, $content[1], 'private');
                    }

                    $invoiceemail->save();
                }
            }
            //}
        }

        $invoiceEmail = DB::connection($this->connection)->select('select planmanager_subscriptions.id, planmanager_subscriptions.plan_manager_id,
            invoice_emails.id,invoice_emails.plan_manager_id, invoice_emails.subject,
            invoice_emails.body, invoice_emails.received_date,invoice_emails.attachment,invoice_emails.attachment2,invoice_emails.from_email, plan_managers.invoice_email
            from planmanager_subscriptions JOIN invoice_emails ON planmanager_subscriptions.plan_manager_id = invoice_emails.plan_manager_id
            JOIN plan_managers ON planmanager_subscriptions.plan_manager_id = plan_managers.id
            WHERE invoice_emails.deleted_at is null and planmanager_subscriptions.id=? order by invoice_emails.id DESC', [auth()->user()->plan_manager_subscription_id]);


        return view('admin.dashboardemails')->with(compact(['invoiceEmail', 'invoiceEmailAddress']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
