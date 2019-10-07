<?php
class Echangerate {
    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    function convertCurrency($amount, $from, $to){
        if($from==$to)
        {
                return round($amount, 2);
        }
        else
        {
            $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
            $data = file_get_contents($url);
            preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
            $converted = preg_replace("/[^0-9.]/", "", @$converted[1]);
            return round($converted, 2);
            //return 1.00;
        }
    }
}

