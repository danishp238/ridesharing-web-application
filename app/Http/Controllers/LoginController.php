<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginVerification;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        // validate the email (phone number in tutorial)

        $request->validate([

            'email' => 'required|email'
        ]);


        // find or create a user model

        $user = User::firstOrCreate([
            'email' => $request->email
        ]);


        // send the user an otp via the received email

        $otp = rand(111111, 999999);
        $user->login_code = $otp;
        $user->save();

        // try {
        //     Mail::to($user->email)->send(new LoginVerification($otp));

        //     return response()->json(
        //         ['message' => 'Your otp has been sent to your email']);
        // } 
        // catch (\Exception $error) {
        //     return response()->json(['message' => '$error']);
        // }

        return response()->json(['message' => 'Your otp has been sent and is :' . $otp]);

    }

    public function verify(Request $request)
    {
        // validate incoming request

        $request->validate([
            'email' => 'required|email',
            'login_code' => 'required|numeric|digits:6'
        ]);


        // find the user

        $user = User::where('email', $request->email)
        ->where('login_code', $request->login_code)
        ->first();


        if ($user) {
            
            $user->update([
                'login_code' => null
            ]);

            // return response()->json($user->login_code);

            return $user->createToken($request->login_code)
            ->plainTextToken;
        }

         return response()->json(['message' => 'invalid verification code'], 401);


        // $user = User::where('email', $request->email)->first();

        // is the code provided same one saved?

        // if so, return back an auth token

        // if ($user) {
        //     return $user->createToken($request->login_code)->plainTextToken();
        //     $user->update([

        //         'login_code' => null
        //     ]);
        // }

    //     if ($user && Hash::check($request->login_code, $user->login_code)) {
    //         // Create an auth token
    //         $token = $user->createToken('authToken')->plainTextToken;

    //         // Clear the login code
    //         $user->update(['login_code' => null]);

    //         return response()->json(['token' => $token], 200);

    // }
    //     // if not, return back a msg

    //     return response()->json(['message' => 'invalid otp'], 401);

    }
}
