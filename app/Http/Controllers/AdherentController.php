<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdherentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $path = $request->file('csv_file')->getRealPath();
        $content = file_get_contents($path);
        $content = $this->parse_csv($content);
        $content = $this->handle_header($content);
        dd($content);

        return redirect()->back()->with('success', 'Todo !');
    }

    function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
        $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
        $enc = preg_replace_callback(
            '/"(.*?)"/s',
            function ($field) {
                return urlencode($field[1]);
            },
            $enc
        );
        $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
        return array_map(
            function ($line) use ($delimiter, $trim_fields) {
                $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
                return array_map(
                    function ($field) {
                        return str_replace('!!Q!!', '"', urldecode($field));
                    },
                    $fields
                );
            },
            $lines
        );
    }

    function handle_header($array)
    {
        $header = null;
        $result = [];
        foreach ($array as $line)
        {
            if ($header === null)
            {
                $header = $line;
            }
            else
            {
                $entry = [];
                for ($i = 0; $i < count($header); $i++)
                {
                    $entry[$header[$i]] = $line[$i];
                }
                $result[] = $entry;
            }
        }
        return $result;
    }
}
