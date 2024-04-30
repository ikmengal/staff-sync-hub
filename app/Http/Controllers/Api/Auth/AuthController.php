<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendOtpNotification;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->where("is_employee" , 1)->first();
        if(empty($request->email)){
            return apiResponse(false, null, 'Please Enter Email', 500);
        }

        if($request->password == ""){
            return apiResponse(false, null, 'Please Enter Password', 500);
        }

        if (!$user) {
            return apiResponse(false, null, 'Invalid Email', 500);
        }
        
        // if($user) {
        //     if(isset($request->password) && !empty($request->password)){
        //         if(Hash::check($user->password, $request->password)){
        //             return apiResponse(false, null, 'Invalid Password', 500);
        //         }
        //     }
        // }
        
        $credentials = $request->only('email', 'password');
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            // if (Auth::attempt($credentials)) {

                $user = Auth::user();
                $user->tokens()->update(['expires_at' => now()]);
                $token = $user->createToken('token')->plainTextToken;
                $record = [
                    'token'=>$token,
                    'user' => new UserResource($user)
                ];
            
                return  apiResponse($success = true, $data =  $record  , $message = "User Login Successfuly", $code = 200);
            // }
            // return  apiResponse($success = false, $data =  null  , $message = "Unauthorized", $code = 500); 
        }else{
            return apiResponse(false, null, 'Incorrect Password', 500);
        }
     
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|email|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'slug' =>  base64_encode($request->first_name),
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('token')->plainTextToken;
        $data = [
            'token' => $token, 'user' => new UserResource($user)
        ];
        return  apiResponse(true, $data, "Successfuly Registered", $code = 200);
    
    }


    public function logout(Request $request)
    {
        // Revoke the current user's token

        $request->user()->currentAccessToken()->delete();
        return apiResponse(true, null, "Logged out successfuly", 200);
       
    
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        // Generate and store OTP
        $otp = $this->generateOtp();
        $user->update(['otp' => $otp, 'otp_expires' => now()->addMinutes(5)]);

        // Send OTP via email
        $user->notify(new SendOtpNotification($otp));
       
        return  apiResponse(true, null, "OTP sent to your email.", 200);
    }
    protected function broker()
    {
        return Password::broker('users');
    }
    public function resetPassword(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Return validation errors if the validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Validate the OTP
        if ($user->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 500);
        }

        // Check if OTP is expired
        if ($user->otp_expires < now()) {
            return response()->json(['error' => 'OTP has expired.'], 500);
        }

          // Reset the password
          $user->password = Hash::make($request->password);
          $user->save();

        // Check if the password was reset successfully
        return Password::PASSWORD_RESET
            ?  apiResponse(true, null, "Password reset successfully.", 200)
            : apiResponse(false, null, "Unable to reset password.", 500);
    }


    protected function generateOtp()
    {
        // Generate random OTP (e.g., 6-digit number)
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function changePassword(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            
            return apiResponse(false, null, "Current password is incorrect.", 500);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();
       
        return  apiResponse(true, null, "Password changed successfully.", 200);
    }
}
