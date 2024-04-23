<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendBillMail;
use App\Models\Billing;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['success' => true, 'data' => Billing::with('products', 'customer')->get(), 'message' => 'Bills has been fetched successfully']);
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
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
        }

        $bill = new Billing();
        $bill->customer_id = $request->customer_id;
        $customer = Customer::findOrFail($request->customer_id);
        $random_char1 = chr(rand(65, 90));
        $random_char2 = chr(rand(65, 90));
        $bill->bill_number = 'BILL' . $random_char1 . date('YmdHis') . $random_char2;
        $bill->total_amount = 0;
        $bill->save();
        $totalAmount = 0;
        $rows = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $quantity = $item['quantity'];

            if ($product->stock < $quantity) {
                return response()->json(['message' => 'Insufficient stock for ' . $product->name], 400);
            }

            $product->stock -= $quantity;
            $product->save();

            $totalAmount += $product->price * $quantity;
            $bill->products()->attach($product->id, ['quantity' => $quantity, 'price' => $product->price, 'billing_id' => $bill->id]);
            $rows[] = ['name' => $product->name, 'qty' => $quantity, 'price' => $product->price];
        }

        $bill->total_amount = $totalAmount;
        $bill->save();

        $mail = Mail::to("subhankar0810@gmail.com")->send(new SendBillMail($customer->name, $bill->bill_number, $totalAmount, $rows));

        return response()->json(['success' => true, 'data' => $bill, 'message' => 'Bill has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Billing::with('products', 'customer')->where('id', $id)->get()->first();
        //
        try {
            return response()->json(['success' => true, 'data' => $bill, 'message' => 'Customer has been fetched successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error ', 'errors' => $th->getMessage()], 422); // Return validation errors as JSON
        }
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
