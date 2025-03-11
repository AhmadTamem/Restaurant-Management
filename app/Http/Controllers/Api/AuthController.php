<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validate = $request->validate([
                "email" => "required|email|unique:users,email",
                "password" => "required|confirmed|min:8",
                "phone_number" => "required|digits:10|unique:users,phone_number",
                "name" => "required",
                "role" => "in:user,admin,manager,captain",
                "address" => "required"
            ]);



            $user = User::create([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "name" => $request->name,
                "phone_number" => $request->phone_number,
                "role" => $request->role ?? 'user',
                "address" => $request->address

            ]);


            if ($user) {
                return response()->json(['status' => 'success', 'message' => 'Successfully inserted data'], 201);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

       //{post} form_data login api 
       public function login(Request $request)
       {
           try {
               $validate = $request->validate([
                   "email" => "required|email",
                   "password" => "required|min:8",
               ]);
               if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                   return response()->json(['status' => 'error', 'message' => 'unbale login invalid'], 401);
               }
               $user = Auth::user();
               $token = $user->createToken('apiToken')->plainTextToken;
               return response()->json(['status' => 'success', 'token' => $token, 'role' => $user->role], 200);
           } catch (Exception $e) {
               return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
           }
       }
       public function profile(){
        try{
            $user = Auth::user();
            if($user){
                return response()->json(['status'=>'success','message'=>'true get data','data'=>$user],200);
            }

        }
        catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
        }
       }
       public function changePassword(Request $request)
       {
           try {
               $validatedData = $request->validate([
                   'old_password' => 'required',
                   'new_password' => 'required|confirmed|min:8',
               ]);
   
               $user = Auth::user();
   
               if (!$user) {
                   return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
               }
   
               if (!Hash::check($validatedData['old_password'], $user->password)) {
                   return response()->json(['status' => 'error', 'message' => 'Old password does not match'], 402);
               }
   
               $user->update(['password' => bcrypt($validatedData['new_password'])]);
   
               return response()->json(['status' => 'success', 'message' => 'Password changed successfully'], 200);
           } catch (\Illuminate\Validation\ValidationException $e) {
               return response()->json(['status' => 'error', 'message' => $e->errors()], 400);
           } catch (Exception $e) {
               return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
           }
       }


}
