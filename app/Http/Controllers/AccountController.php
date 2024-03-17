<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if($request->method()=='POST'){
            $request->validate([
                'first_name' => 'required|max:25',
                'last_name' => 'required|max:25',
                'mobile' => 'required|max:11',
                'email' => 'required|email'
            ]);
            
            $user = User::find(auth()->user()->id);
            $user -> first_name = $request -> first_name;
            $user -> last_name = $request -> last_name;
            $user -> mobile = $request -> mobile;
            $user -> email = $request -> email;
            $user->save();

            return back()->withSuccess('Updated successfully.');
        }

        $orders = [];
        $addresses = [];

        if(auth()->check()) {
            $user_id = auth()->user()->id;

            $addresses = UserAddress::where('user_id', auth()->user()->id)->get();

            $orders = Order::query()
                ->with('items:order_id')
                ->where('user_id', $user_id)
                ->get();
        }

        foreach ($orders as $k => $order) {

            $products = Product::whereIn('id',
                fn($q)=>$q->select('product_id')
            )
                ->with('oldestImage')
                ->get();

                $images = array_column($products->toArray(), 'oldest_image');
                $orders[$k]['images'] = array_column($images, 'path');
        }

        // return $orders;
        return view('account', compact('orders','addresses'));
    }

    public function deleteAccount()
    {
        $user = Auth::user();

        $addresses = $user->addresses;

        foreach ($addresses as $address) {
            $address->delete();
        }

        $user->delete();

        Auth::logout();

        return Redirect::to('/')->withSuccess('Your account has been successfully deleted.');
    }

    // Address ===================================

    public function newAddress(Request $request)
    {
        if($request->method() == 'GET') return view('new_address');

        abort_if(!auth()->check(), 404);

        $request->validate([
            'is_default_address' => 'required',
            'tag' => 'required|max:50',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile_no' => 'required|max:11',
            'street_address' => 'required|max:100',
            'barangay' => 'required|max:50',
            'city' => 'required|max:50',
            'province' => 'required|max:50',
            'zip_code' => 'required|max:4',
            'note' => 'max:250',
        ]);

        $address = new UserAddress;
        $address->user_id = auth()->user()->id;
        $address->is_default_address = $request->is_default_address;
        $address->tag = $request->tag;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->mobile_no = $request->mobile_no;
        $address->street_address = $request->street_address;
        $address->barangay = $request->barangay;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->zip_code = $request->zip_code;
        $address->note = $request->note;
        $address->save();

        if($address->is_default_address) self::setDefaultAddress($address->id);

        return redirect()->route('account.index', ['tab' => 'address'])->withSuccess('New delivery address added.');
    }

    public function editAddress(Request $request, $id)
    {
        if($request->method() == 'GET')
        {
            $data = auth()->check() ? UserAddress::find($id) : null;
            return view('edit_address', compact('data'));
        }

        abort_if(!auth()->check(), 404);

        if($request->method() == 'GET') return view('new_address');

        $request->validate([
            'is_default_address' => 'required',
            'tag' => 'required|max:50',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile_no' => 'required|max:11',
            'street_address' => 'required|max:100',
            'barangay' => 'required|max:50',
            'city' => 'required|max:50',
            'province' => 'required|max:50',
            'zip_code' => 'required|max:4',
            'note' => 'max:250',
        ]);

        $address = UserAddress::find($id);
        $address->is_default_address = $request->is_default_address;
        $address->tag = $request->tag;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->mobile_no = $request->mobile_no;
        $address->street_address = $request->street_address;
        $address->barangay = $request->barangay;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->zip_code = $request->zip_code;
        $address->note = $request->note;
        $address->save();

        if($address->is_default_address) self::setDefaultAddress($address->id);

        return redirect()->route('account.index', ['tab' => 'address'])->withSuccess('Delivery address updated.');
    }

    public function deleteAddress(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);

        if ($request->user()->id !== $address->user_id) {
            return response()->json([
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully.'
        ]);
    }

    private static function setDefaultAddress($address_id)
    {
        UserAddress::where('user_id', auth()->user()->id)->where('id', '!=', $address_id)->update(['is_default_address'=>false]);
    }

    // Address End =======================================

    // ORDER =======================================

    public function showOrder($id)
    {

        $order = [];

        if (auth()->check()) {
            $order = Order::with([
                'items.product:id,title',
                'items.product:oldestImage'
            ])
                ->where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->first();
        }

            return view('show_order', compact('order'));
    }

    // SHOW ORDER End =======================================
}
