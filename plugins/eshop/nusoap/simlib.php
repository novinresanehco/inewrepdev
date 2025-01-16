<?php

// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.
// Main Interfaces:
//
// function InsertFP ($loginid, $x_tran_key, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp) - Returns Fingerprint.
// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
if ( !function_exists( 'hmac' ) )
{

    function hmac( $key, $data )
    {
        return (bin2hex( mhash( MHASH_MD5, $data, $key ) ));
    }

}

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP( $loginid, $x_tran_key, $amount, $sequence, $tstamp, $currency = "" )
{
    return (hmac( $x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency ));
}

// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP( $loginid, $x_tran_key, $amount, $sequence, $currency = "" )
{

    $tstamp = time();

    $fingerprint = hmac( $x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency );

    echo ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '">' );
    echo ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '">' );
    echo ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '">' );


    return (0);
}

function GETFP( $loginid, $x_tran_key, $amount, $sequence, $currency = "", $time = -1 )
{
    if ( $time == -1 )
        $tstamp      = time();
    else
        $tstamp      = $time;
    $fingerprint = hmac( $x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency );
    $out         = array();
    $out[0]      = $fingerprint;
    $out[1]      = '<input type="hidden" name="x_fp_sequence" value="' . $sequence . '">';
    $out[1] .= '<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '">';
    $out[1] .= '<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '">';
    $out[2]      = $tstamp;
    return $out;
}
?>

