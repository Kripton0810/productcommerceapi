<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use GuzzleHttp\Client;
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
        return response()->json(['success' => true, 'data' => Customer::all(), 'message' => 'Customer has been fetched successfully']);
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
                'search' => 'required|string|max:255',
                'country_code' => 'string|max:255',
            ]);
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error while validation', 'errors' => $validator->errors()], 422); // Return validation errors as JSON
            }
            // call api
            $client = new Client();
            $response = $client->request('GET', 'https://testapi.trevex.io/apis/companies/search', [
                'query' => [
                    'search' => $request->search,
                    'country_code' => 'IN'
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'api-key 1e732e8a253b76937e22380b3e7b86e34bc21b7e'
                    // Add any other headers as needed
                ]
            ]);

            // echo $response->getBody();
            $data = json_decode($response->getBody());

            $companyID = $data->data[0]->companyID;
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'api-key 1e732e8a253b76937e22380b3e7b86e34bc21b7e'
            ];
            $body = [
                'companyID' => $companyID
            ];

            $response = $client->request('GET', 'https://testapi.trevex.io/apis/companies/detail_data/', [
                'headers' => $headers,
                'json' => $body
            ]);
            $data = json_decode($response->getBody());
            $companyID = $data->data->companyID;
            $companyName = $data->data->registered_company_name;
            $country = $data->data->country_code;
            $estdDate = $data->data->date_of_establishment;
            $state = $data->data->companyAddress[0]->company_address_detailsID->state;
            $al1 = $data->data->companyAddress[0]->company_address_detailsID->address_line_1;
            $al2 = $data->data->companyAddress[0]->company_address_detailsID->address_line_2;
            // first check weather $companyID is present in the DB or not
            $data = Customer::where('company_id', $companyID)->first();
            if ($data) {
                return response()->json(['status' => false, 'message' => 'Company Id already present'], 400); // Return validation errors as JSON
            }

            $data = Customer::create([
                "name" => $companyName,
                "company_name" => $companyName,
                "company_id" => $companyID,
                "country" => $country,
                "estd_date" => $estdDate,
                "state" => $state,
                "address_line1" => $al1,
                "address_line2" => $al2,
            ]);
            DB::commit();
            return response()->json(['success' => true, 'data' => $data, 'message' => 'Customer has been created successfully']);
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
    public function show(Customer  $customer)
    {
        $companyID = $customer->company_id;
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'api-key 1e732e8a253b76937e22380b3e7b86e34bc21b7e'
        ];
        $body = [
            'companyID' => $companyID
        ];

        $response = $client->request('GET', 'https://testapi.trevex.io/apis/companies/detail_data/', [
            'headers' => $headers,
            'json' => $body
        ]);
        $data = json_decode($response->getBody());
        $companyData = $data->data->companyAddress[0]->company_address_detailsID;
        $response = [];

        $data_keys = [
            "wb_win",
            "banner",
            "logo",
            "registered_company_name",
            "date_of_establishment",
            "company_description",
            "currency",
            "number_of_employees",
            "twitter",
            "facebook",
            "instagram",
            "linkedin",
            "website",
            "trade_license",
            "date_of_issue",
            "date_of_expiry",
            "license_no",
            "registration_body",
            "legal_form",
            "activities",
            "TRN_no"
        ];

        foreach ($data_keys as $key) {
            if (isset($data->data->{$key})) {
                $response[$key] = $data->data->{$key};
            } else {
                $response[$key] = null; // Or any default value you prefer
            }
        }
        $response = array_merge(json_decode(json_encode($companyData), true), $response);


        try {
            return response()->json(['success' => true, 'data' => $response, 'message' => 'Customer has been fetched successfully']);
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
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => true, 'message' => 'Customer has been deleted successfully']);
    }
}
