<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Otp;
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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $user->tokens()->update(['expires_at' => now()]);
            $token = $user->createToken('token')->plainTextToken;
            $record = [
                'token'=>$token,
                'user' => new UserResource($user)
            ];
           
            return  apiResponse($success = true, $data =  $record  , $message = "User Login Successfuly", $code = 200);
        }
        return  apiResponse($success = false, $data =  null  , $message = "Unauthorized", $code = 401); 
     
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
            return response()->json($validator->errors(), 400);
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
        $otp = $this->generateOtp(4,$user->id);
        Otp::updateOrCreate(['user_id' => $user->id], [
            'user_id'=>$user->id,
            'otp'=>$otp,
            'otp_expires'=>now()->addMinutes(5)
            
        ]);
       

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
            return response()->json($validator->errors(), 400);
        }
        // Find the user by email
        $user = User::where('email', $request->email)->first();
        
        $otp = Otp::where('user_id',$user->id)->first();
        if(!empty($otp)){
                    // Validate the OTP
        if ($otp->otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP.'], 400);
        }

        // Check if OTP is expired
        if ($otp->otp_expires < now()) {
            return response()->json(['error' => 'OTP has expired.'], 400);
        }

          // Reset the password
          $user->password = Hash::make($request->password);
          $user->save();

        // Check if the password was reset successfully
        return Password::PASSWORD_RESET
            ?  apiResponse(true, null, "Password reset successfully.", 200)
            : apiResponse(false, null, "Unable to reset password.", 400);

        }else{
            return apiResponse(false, null, "Otp Not Found", 400);
        }

    }


    // protected function generateOtp()
    // {
    //     // Generate random OTP (e.g., 6-digit number)
    //     return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    // }
    function generateOtp($length = 6,$id)
    {
        // Define the characters that can be used in the OTP
        $characters = '0123456789';
    
        // Get the total number of characters
        $characterCount = strlen($characters);
    
        // Initialize the OTP string
        $otp = '';
    
        // Generate a random string
        $randomString = substr(str_shuffle($characters), 0, $length);
    
        // Append additional unique information (e.g., user ID, timestamp)
      
        $uniqueOtp = $randomString . $id;
    
        // Return the unique OTP
        return $uniqueOtp;
    }
    


    public function changePassword(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            
            return apiResponse(false, null, "Current password is incorrect.", 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();
       
        return  apiResponse(true, null, "Password changed successfully.", 200);
    }
}
