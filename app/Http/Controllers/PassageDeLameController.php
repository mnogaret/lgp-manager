<?php

namespace App\Http\Controllers;

use App\Tools\PassageDeLameImporter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassageDeLameController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $path = $request->file('csv_file')->getRealPath();
        $importer = new PassageDeLameImporter();
        try {
            $importer->from_csv(file_get_contents($path));
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('success', print_r($importer->traces, true));
    }

}
