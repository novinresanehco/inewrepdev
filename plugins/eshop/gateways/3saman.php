<?php

class payment_saman
{

    public $mid = '';
    public $logo = 'images/logo.gif';
	function __construct()
	{
		global $config;
		$variables = get_object_vars( $this );
		foreach( $variables as $key => $value )
		{
			$this->$key = isset( $config['saman_' . $key] ) ? $config['saman_' . $key] : $value;
		}
	}
    //start payment function
    function start( $data )
    {
        $result['status'] = true;
        $result['trackKey'] = $this->transactionCodeTonumber( $data['transactionCode'] );
        return $result;
    }

    //verify payment function
    function callback( $data )
    {
        require_once(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'nusoap' . DIRECTORY_SEPARATOR . 'nusoap.php');
        $result = array( );
        $result['status'] = false;
        if ( !isset( $_POST['RefNum'] ) OR !isset( $_POST['MID'] ) OR !isset( $_POST['State'] ) )
        {
            $result['error'] = 'اطلاعات ارسالی ناقص است.';
        }
        else
        {
            $nusoap_client = new soapclient("https://acquirer.samanepay.com/payments/referencepayment.asmx?WSDL");
            $res = $nusoap_client->VerifyTransaction( $_POST['RefNum'], $_POST['MID'] );
            if ( $res > 0 )
            {
                $result['status'] = true;
            }
            else
            {
                $result['error'] = 'پرداخت ناموفق بود.';
            }
        }
        return $result;
    }

    //redirect to bank function
    function redirect( $data )
    {
        $callbackUrl = eshop::appendQS( eshop::appendQS( eshop::curPageURL(), 'callback', 'saman' ), 'transactionCode', $data['transactionCode'] );
        echo '	
	<form name="checkout_confirmation" action="https://acquirer.samanepay.com/Payment.aspx" method="post">
	<input type="hidden" name="MID" value="' . $this->mid . '">
	<input type="hidden" name="RedirectURL" value="' . $callbackUrl . '">
	<input type="hidden" name="TableBorderColor" value="488DCC">
	<input type="hidden" name="TableBGColor" value="488DCC">
	<input type="hidden" name="PageBGColor" value="B6CBEA">
	<input type="hidden" name="PageBorderColor" value="6C788B">
	<input type="hidden" name="TitleFont" value="Tahoma">
	<input type="hidden" name="TitleColor" value="000000">
	<input type="hidden" name="TitleSize" value="5">
	<input type="hidden" name="TextFont" value="Tahoma">
	<input type="hidden" name="TextColor" value="000000">
	<input type="hidden" name="TextSize" value="2">
	<input type="hidden" name="TypeTextFont" value="Tahoma">
	<input type="hidden" name="TypeTextColor" value="000000">
	<input type="hidden" name="TypeTextSize" value="2">
	<input type="hidden" name="LogoURL" value="' . $this->logo . '">
	<input type="hidden" name="price" value="' . $data['price'] . '">
	<input type="hidden" name="ResNum" value="' . $data['trackKey'] . '">
	<script> document.checkout_confirmation.submit(); </script>
	';
        exit;
    }

    function transactionCodeTonumber( $code )
    {
        $result = 1;
        for ( $i = 0, $c = strlen( $code ); $i < $c; $i++ )
        {
            if ( ord( $code[$i] ) > 0 )
            {
                $result *= ord( $code[$i] );
            }
        }
        do
        {
            $result /= 2;
        }
        while ( $result > 100000000 );
        return ( int ) $result;
    }

}