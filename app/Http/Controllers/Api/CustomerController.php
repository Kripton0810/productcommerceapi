<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'email|max:255',
                'phone' => 'required|numeric|min:10',
                'state_id' => 'numeric',
                'city_id' => 'numeric',
            ]);
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
            }
            $customer = Customer::create([
                'name' => $request['name'] ?? null,
                'email' => $request['email'] ?? null,
                'phone' => $request['phone'] ?? null,
                'state_id' => $request['state_id'] ?? null,
                'city_id' => $request['city_id'] ?? null,
            ]);
            DB::commit();
            return response()->json(['success' => true, 'data' => $customer, 'message' => 'Customer has been created successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Server error', 'errors' => $th->getMessage()], 400); // Return validation errors as JSON
        }
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
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'email' => 'email|max:255',
                'phone' => 'numeric|min:10',
                'state_id' => 'numeric',
                'city_id' => 'numeric',
            ]);
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
            }
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());
            DB::commit();
            return response()->json(['success' => true, 'data' => $customer, 'message' => 'Customer has been updated successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Server error', 'errors' => $th->getMessage()], 400); // Return validation errors as JSON
        }
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
