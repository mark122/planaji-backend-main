<?php

namespace App\Http\Controllers\Api\Auth;


use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Controllers\Api\Participant\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Participants;
use App\User;
use App\PlanManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgotPasswordCode;
use App\Mail\AppAccessEnableEnquiry;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ParticipantsController extends Controller
{


    public function login(Request $request)
    {
    // {
    //     if ($request->app_name == "planaji") {
    //         $creds = $request->only(['email', 'password']);

    //         if (!$token = auth('participants')->attempt($creds)) {
    //             $response = array([
    //                 'settings' => [
    //                     "status" => 0,
    //                     "message" => "Incorrect Email/Password"
    //                 ]
    //             ]);
    //             return response()->json($response, 401);
    //         }

    //         try {
    //             $user = auth('participants')->userOrFail();
    //             // var_dump($user);

    //         } catch (TokenInvalidException $e) {
    //             return response()->json(['has_error' => true, 'msg' => 'Invalid token'], 401);
    //         } catch (TokenExpiredException $e) {
    //             return response()->json(['has_error' => true, 'msg' => 'Token has Expired'], 401);
    //         } catch (JWTException $e) {
    //             return response()->json(['has_error' => true, 'msg' => 'Token not parsed'], 401);
    //         }

    //         if (empty($user)) {
    //             return false;
    //         }
        // } elseif 
        if (!empty($request->app_name)) {
            if (!$token = auth('participants')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => "Incorrect Email/Password"
                    ]
                );
                return response()->json($response, 200);
            }

            try {
                $user = auth('participants')->userOrFail();
                // var_dump($user);

            } catch (TokenInvalidException $e) {
                return response()->json(['has_error' => true, 'msg' => 'Invalid token'], 401);
            } catch (TokenExpiredException $e) {
                return response()->json(['has_error' => true, 'msg' => 'Token has Expired'], 401);
            } catch (JWTException $e) {
                return response()->json(['has_error' => true, 'msg' => 'Token not parsed'], 401);
            }

            if (empty($user)) {
                return false;
            }
        } else {
            $response = array(
                'settings' => [
                    "status" => 0,
                    "message" => "Invalid App Name"
                ]
            );
            return response()->json($response, 200);
        }

        if ($user->app_access_enabled != 1) {
            $planManager = PlanManager::where('id', $user->planmanager_subscriptions_id)->first();
            $response = array(
                'settings' => [
                    "status" => 2,
                    "message" => "App access is disabled contact plan manager to get app access if already contacted check your Email"
                ],
                'data' => [
                    'plan_manager_email' => $planManager->primary_contact_email,
                ]
            );
            return response()->json($response, 200);
        }

        if ($user->deleted_at != null) {
            $response = array(
                'settings' => [
                    "status" => 0,
                    "message" => "Participant does not exist."
                ]
            );
            return response()->json($response, 200);
        }

        $planManager = PlanManager::where('id', $user->planmanager_subscriptions_id)->first();
        $response = array(
            'settings' => [
                "status" => 1,
                "message" => "User is logged in"
            ],
            'data' => [
                'id' => $user->id,
                'name' => $user->firstname . ' ' . $user->lastname,
                'email' => $user->email,
                'plan_manager_subscription_id' => $user->planmanager_subscriptions_id,
                'plan_manager_email' => $planManager->primary_contact_email,
                'connection' => $user->connection,
                'app_access_enabled' => $user->app_access_enabled,
                'token' => $token,
                'changed_password' => ($user->changed_password == 1) ? 1 : 0

            ]
        );

        return response()->json($response);
    }


    public function refresh()
    {

        try {
            $newToken = auth('participants')->refresh();
            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "refresh token"
                ],
                'data' => [
                    'refresh_token' => $newToken
                ]
            );

            return response()->json($response);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /** Logout */
    public function logOut(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $this->authLogout();
            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => "User is logged Out"
                ]
            );

            return response()->json($response);
        //}
    }

    /**Change Password */
    public function changePassword(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required_with:password|same:new_password|min:6'

            ]);
            if ($validator->fails()) {

                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => $validator->errors()
                    ]
                );
                return response()->json($response);
            }
            $user = $this->authUser();
            if (!Hash::check($request->current_password, $user->password)) {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => 'Invalid current Password'
                    ]
                );
                return response()->json($response);
            }
            Participants::find($user->id)->update(['password' => Hash::make($request->new_password), 'changed_password' => 1, 'password_token' => null]);
            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => 'Password changed successfully'
                ]
            );
            return response()->json($response);
        //}
    }

    /**forgot Password */
    public function forgotPassword(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',

            ]);
            if ($validator->fails()) {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => $validator->errors()
                    ]
                );
                return response()->json($response);
            }
            $user = Participants::where('email', $request->email)->first();
            if ($user) {
                $code = rand(1000, 9999);
                $existUser = Participants::find($user->id);
                $existUser->changed_password = 0;
                $existUser->password_token = $code;
                $existUser->save();

                $this->runtime_mail_config($this->connection);
                $details = [
                    'send_from' => ($request->app_name == "plan_on_track") ? 'planapp@planontrack.com.au' : 'support@planaji.com',
                    'code' => $code
                ];
                Mail::to($request->email)->send(new SendForgotPasswordCode($details));
                $response = array(
                    'settings' => [
                        "status" => 1,
                        "message" => 'security code sent on your email'
                    ]
                );
                return response()->json($response);
            } else {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => 'User not found'
                    ]
                );
                return response()->json($response, 412);
            }
        // } else {
        //     $response = array(
        //         'settings' => [
        //             "status" => 1,
        //             "message" => 'Plana Ji'
        //         ]
        //     );
        //     return response()->json($response);
        // }
    }

    /**Validate Otp */
    public function validateOtp(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required|integer'

            ]);
            if ($validator->fails()) {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => $validator->errors()
                    ]
                );
                return response()->json($response);
            }
            $participant = Participants::where('email', $request->email)
                ->where('password_token', $request->code)->first();
            if ($participant) {
                $response = array(
                    'settings' => [
                        "status" => 1,
                        "message" => 'OTP Validate successfully'
                    ]
                );
                return response()->json($response);
            } else {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => 'Invalid Otp'
                    ]
                );
                return response()->json($response, 412);
            }
        // } else {
        //     $response = array(
        //         'settings' => [
        //             "status" => 1,
        //             "message" => 'Plana Ji'
        //         ]
        //     );
        //     return response()->json($response);
        // }
    }

    /**Reset Password */
    public function resetPassword(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => [
                    'required',
                    'string',
                    'min:6',              // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&.]/', // must contain a special character
                ],
                'confirm_password' => 'required_with:password|same:password|min:6'

            ]);
            if ($validator->fails()) {

                $errormsg = "";
                foreach ($validator->errors()->all() as $value) {
                    $errormsg = $value;
                }

                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => $errormsg
                    ]
                );
                return response()->json($response);
            }
            $participant = Participants::where('email', $request->email)->first();
            if ($participant) {
                Participants::find($participant->id)->update(['password' => Hash::make($request->password), 'changed_password' => 1, 'password_token' => null]);
                $response = array(
                    'settings' => [
                        "status" => 1,
                        "message" => 'Password Reset successfully'
                    ]
                );
                return response()->json($response);
            } else {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => 'User Not Found'
                    ]
                );
                return response()->json($response, 412);
            }
        // } else {
        //     $validator = Validator::make($request->all(), [
        //         'email' => 'required|email',
        //         'password' => [
        //             'required',
        //             'string',
        //             'min:6',              // must be at least 10 characters in length
        //             'regex:/[a-z]/',      // must contain at least one lowercase letter
        //             'regex:/[A-Z]/',      // must contain at least one uppercase letter
        //             'regex:/[0-9]/',      // must contain at least one digit
        //             'regex:/[@$!%*#?&.]/', // must contain a special character
        //         ],
        //         'confirm_password' => 'required_with:password|same:password|min:6'

        //     ]);

            
        //     if ($validator->fails()) {

        //     $errormsg = "";
        //     foreach ($validator->errors()->all() as $value) {
        //         $errormsg = $value;
        //     }

        //         $response = array(
        //             'settings' => [
        //                 "status" => 0,
        //                 "message" => $errormsg
        //             ]
        //         );
        //         return response()->json($response);
        //     }
        //     $participant = Participants::where('email', $request->email)->first();
        //     if ($participant) {
        //         Participants::find($participant->id)->update(['password' => Hash::make($request->password), 'changed_password' => 1, 'password_token' => null]);
        //         $response = array(
        //             'settings' => [
        //                 "status" => 1,
        //                 "message" => 'Password Reset successfully'
        //             ]
        //         );
        //         return response()->json($response);
        //     } else {
        //         $response = array(
        //             'settings' => [
        //                 "status" => 0,
        //                 "message" => 'User Not Found'
        //             ]
        //         );
        //         return response()->json($response, 412);
        //     }
        // }
    }

    /**Send App Access Enquiry Mail */
    public function appAccessEnquiry(Request $request)
    {
        //if ($request->app_name == "plan_on_track") {
            $email = $request->email;
            $participant = Participants::where('email', $email)->first();
            if (!$participant) {
                $response = array(
                    'settings' => [
                        "status" => 0,
                        "message" => 'participant not exist'
                    ]
                );
                return response()->json($response, 412);
            }
            $detail = [
                'name' => $participant->firstname . ' ' . $participant->lastname,
                'email' => $participant->email
            ];

            if (isset($participant->planmanager_subscriptions_id)) {
                $planManager = PlanManager::where('id', $participant->planmanager_subscriptions_id)->first();
                try {
                    Mail::to($planManager->primary_contact_email)->send(new AppAccessEnableEnquiry($detail));
                } catch (\Exception $e) {
                    $this->runtime_mail_config($this->connection);
                    Mail::to(($request->app_name == "plan_on_track") ? 'planapp@planontrack.com.au' : 'support@planaji.com')->send(new AppAccessEnableEnquiry($detail));
                }
            } else {
                $this->runtime_mail_config($this->connection);
                Mail::to(($request->app_name == "plan_on_track") ? 'planapp@planontrack.com.au' : 'support@planaji.com')->send(new AppAccessEnableEnquiry($detail));
            }

            $response = array(
                'settings' => [
                    "status" => 1,
                    "message" => 'App access enquiry mail sent successfully'
                ]
            );
            return response()->json($response);
        //}
    }

    public function runtime_mail_config($connection){

        $get_email_configs = get_email_config($connection);

        //var_dump( $get_email_configs);

        if($get_email_configs)
            foreach($get_email_configs as $key=>$value){
                Config::set('mail.mailers.smtp.'.$key, $value);
            }
    }

}
