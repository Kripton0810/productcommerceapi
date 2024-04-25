<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CsvController extends Controller
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
        //
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
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048', // Assuming max file size is 2MB
        ]);

        // Read and process the CSV file
        $file = $request->file('file');
        $csvData = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", $csvData));

        // Skip the first row (headers)
        $headers = array_shift($rows);
        DB::beginTransaction();
        $i = 0;
        try {
            $errorsEntry = [];
            foreach ($rows as $row) {
                // Assuming your CSV has columns named 'column1', 'column2', etc.
                if (!empty($row)) {
                    if (count($headers) != count($row)) {
                        continue;
                    }
                    $rowData = array_combine($headers, $row);
                    $validator = Validator::make($rowData, [
                        'name' => 'required',
                        'sku' => 'required|unique:products',
                        'price' => 'required|numeric',
                        'rate' => 'nullable|numeric',
                        'stock' => 'nullable|integer',
                    ]);
                    if ($validator->fails()) {
                        $errorsEntry[] = ['row' => implode(',', $row), 'errors' => $validator->errors()];
                        // continue;
                    }
                    Product::create([
                        'name' => $row[0] ?? "",
                        'sku' => $row[1] ?? "",
                        'description' => $row[2] ?? "",
                        'price' => $row[3] ?? 0,
                        'rate' => $row[4] ?? 0,
                        'stock' => $row[5] ?? 0,
                    ]);
                }
            }
            if (!empty($errorsEntry)) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error while validation or Duplicatiy', 'errors' => $errorsEntry], 422); // Return validation errors as JSON
            } else {
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Product has been created successfully']);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error encounters', 'errors' => $th->getMessage()], 422); // Return validation errors as JSON

        }
    }
}
