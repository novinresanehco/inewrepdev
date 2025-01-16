<?php

class payment_melli
{

    public $loginid = '';
    public $tran_key = '';
    public $currency_code = 'Rial';
	function __construct()
	{
		global $config;
		$variables = get_object_vars( $this );
		foreach( $variables as $key => $value )
		{
			$this->$key = isset( $config['melli_' . $key] ) ? $config['melli_' . $key] : $value;
		}
	}
    //start payment function
    function start( $data )
    {
        $result['status'] = true;
        $sequence = $this->transactionCodeTonumber( $data['transactionCode'] );
        require( dirname( __FILE__ ) . '/../nusoap/simlib.php');
        $result['sendData'] = GETFP( $this->loginid, $this->tran_key, $data['price'], $sequence, $this->currency_code );
        $result['trackKey'] = $result['sendData'][0];
        $result['sendData'] = base64_encode( json_encode( $result['sendData'] ) );
        return $result;
    }

    //verify payment function
    function callback( $data )
    {
        require_once(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'nusoap' . DIRECTORY_SEPARATOR . 'nusoap.php');
        $result = array( );
        $result['status'] = false;
        if ( !isset( $_POST['ResNum'] ) OR !isset( $_POST['MID'] ) OR !isset( $_POST['State'] ) )
        {
            $result['error'] = 'اطلاعات ارسالی ناقص است.';
        }
        else
        {
            $nusoap_client = new nusoap_client( 'https://Acquirer.sb24.com/ref-payment/ws/ReferencePayment?WSDL', 'wsdl' );
            $res = $nusoap_client->call( 'VerifyTransaction', array( $_POST['ResNum'], $_POST['MID'] ) );
            //$res = $nusoap_client->VerifyTransaction( $_POST['ResNum'], $_POST['MID'] );
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
        $sign = json_decode( base64_decode( $data['sendData'] ), true );
        echo '	
	<FORM name="checkout_confirmation" action="https://Damoon.bankmelli-iran.com/DamoonPrePaymentController" method="POST">
	<input type="hidden" name="x_description" value="خرید ' . $data["transactionCode"] . '">
	<input type="hidden" name="x_login" value="' . $this->loginid . '">
	<input type="hidden" name="x_price" value="' . $data['price'] . '">
	<input type="hidden" name="x_currency_code" value="' . $this->currency_code . '">
        ' . $sign[1] . '
	<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">
	<INPUT type="hidden" name="x_test_request" value="true">
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

    function hmac( $key, $data )
    {
        return (bin2hex( mhash( MHASH_MD5, $data, $key ) ));
    }

}