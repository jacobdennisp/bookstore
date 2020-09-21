<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;



class UserAuthController extends Controller
{
    public function userlogin(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email|max:255',
            'password'=>'required|string|min:6',
        ]);
        if($validator->fails()){
            return response(['error'=>$validator->errors()->all()],422);
        }
        $user=User::where('email',$request->email)->first();
        if ($user){
            if (Hash::check($request->password, $user->password)){
                $token=$user->createToken('Password Provider')->accessToken;
                $response = ['token'=>$token];
                return response($response, 200);
            } else {
                $response = ["Message" => "Password Does not Matc"];
                return response($response, 422);
            }
        } else {
                $response = ["Message" => "User Not Found"];
                return response($response,422);
        }
    }
    
    public function userlogout(Request $request){
        $token = $request->user()->token();
        $token->revoke();
        $response = ['Message'=>"You have been Logged Out Successfully!"];
        return response($response,200);
    }
    

}
