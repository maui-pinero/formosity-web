<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\UserAddress;

class UserController extends Controller
{

    // AUTH

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->role = 0;
        $user->save();
    
        return response()->json([
            'message' => 'Registered Successfully',
            'data' => $user
        ], 200);
    }

    public function login(Request $request){
        $validator = Validator::make($request -> all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::where('email',$request->email)->first();

        if ($user){
            if(Hash::check($request->password,$user->password)){
                $token=$user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login Successful',
                    'token' => $token,
                    'data' => $user
                ],200);
            }
            else{
                return response()->json([
                    'message' => 'Incorrect Credentials',
                ],400);
            }
        }
        else{
            return response()->json([
                'message' => 'Incorrect Credentials'
            ],400);
        }
    }

    public function user(Request $request){
        return response()->json([
            'message' => 'User Successfully Fetched',
            'data' => $request->user()
        ],200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User Successfully Logged Out',
            'data' =>$request->user()
        ],200);
    }

    // PROFILE

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|min:11|unique:users,mobile,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        return response()->json([
            'message' => 'Profile Updated Successfully',
            'data' => $user
        ], 200);
    }

    public function deleteProfile(Request $request)
    {
        $user = $request->user();

        $user->delete();

        return response()->json([
            'message' => 'User profile deleted successfully',
        ], Response::HTTP_OK);
    }

    // DELIVERY ADDRESS

    // CREATE ADDRESS

    public function createAddress(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'is_default_address' => 'required|boolean',
            'tag' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'mobile_no' => 'required|string|max:11',
            'street_address' => 'required|string|max:100',
            'barangay' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'province' => 'required|string|max:50',
            'zip_code' => 'required|string|max:4',
            'note' => 'nullable|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $address = new UserAddress([
            'is_default_address' => $request->is_default_address,
            'tag' => $request->tag,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_no' => $request->mobile_no,
            'street_address' => $request->street_address,
            'barangay' => $request->barangay,
            'city' => $request->city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
            'note' => $request->note,
        ]);

        $user->addresses()->save($address);

        return response()->json([
            'message' => 'Address created successfully',
            'data' => $address
        ], 201);
    }

    // GET ADDRESS

    public function getAddresses(Request $request)
    {
        $user = $request->user();
        $addresses = $user->addresses()->get();

        return response()->json([
            'message' => 'Addresses fetched successfully',
            'data' => $addresses
        ], 200);
    }

    // UPDATE ADDRESS

    public function updateAddress(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'is_default_address' => 'required|boolean',
            'tag' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'mobile_no' => 'required|string|max:11',
            'street_address' => 'required|string|max:100',
            'barangay' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'province' => 'required|string|max:50',
            'zip_code' => 'required|string|max:4',
            'note' => 'nullable|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $address->update([
            'is_default_address' => $request->is_default_address,
            'tag' => $request->tag,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_no' => $request->mobile_no,
            'street_address' => $request->street_address,
            'barangay' => $request->barangay,
            'city' => $request->city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
            'note' => $request->note,
        ]);

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => $address
        ], 200);
    }

    // DELETE ADDRESS

    public function deleteAddress(Request $request, $id)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);
        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully',
        ], 200);
    }
}