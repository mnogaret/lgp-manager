<?php

namespace App\Tools;

class CsvParser
{
    public static function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
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
        return CsvParser::handle_header(array_map(
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
        ));
    }

    private static function handle_header($array)
    {
        $header = null;
        $result = [];
        foreach ($array as $line) {
            if ($header === null) {
                $header = $line;
            } else {
                $entry = [];
                for ($i = 0; $i < count($header); $i++) {
                    $entry[$header[$i]] = $line[$i];
                }
                $result[] = $entry;
            }
        }
        return $result;
    }
}
