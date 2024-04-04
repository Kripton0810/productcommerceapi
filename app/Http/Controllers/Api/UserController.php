<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
            }
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ]);
            //create user token
            $tokenResult = $user->createToken('UserToken')->accessToken;
            $user->accessToken = $tokenResult;
            DB::commit();
            return response()->json(['success' => true, 'data' => $user, 'message' => 'User has been created successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // echo "<pre>";
            // print_r($th);
            // echo "</pre>";

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
        return User::findOrFail($id);
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
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
        }
        $findUser = User::where('email', $request->input('email'))->first();
        if (!$findUser || !Hash::check($request->input('password'), $findUser->password)) {
            return response()->json(
                ['status' => false, "message" => "Email or Password is incorrect!"]
            )->setStatusCode(401);
        } else {
            $findUser->accessToken = $findUser->createToken('UserToken')->accessToken;
            $response = ['success' => true, 'data' => $findUser, 'message' => 'Login successfully'];
            return response($response)->setStatusCode(201);
        }
    }
}
