<?php

class payment_parsian
{

    public $pin         = '';
    public $sendUrl     = 'https://www.pecco24.com:27635/pecpaymentgateway/eeshopservice.asmx?wsdl';
    public $redirecturl = 'https://www.pecco24.com:27635/pecpaymentgateway/?au=';

	function __construct()
	{
		global $config;
		$variables = get_object_vars( $this );
		foreach( $variables as $key => $value )
		{
			$this->$key = isset( $config['parsian_' . $key] ) ? $config['parsian_' . $key] : $value;
		}
	}
	
    //start payment function
    function start( $data )
    {
        require_once(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'nusoap' . DIRECTORY_SEPARATOR . 'nusoap.php');
        $result        = array();
        $nusoap_client = new nusoap_client( $this->sendUrl, 'wsdl' );
        if ( !$err           = $nusoap_client->getError() )
        {
            $soapProxy = $nusoap_client->getProxy();
        }
        if ( (!$nusoap_client) OR ($err = $nusoap_client->getError()) )
        {
            $result['status'] = false;
            $result['error']  = 'خطا در اتصال به بانک';
        }
        else
        {
            $authority   = 0;
            $status      = 1;
            $callbackUrl = eshop::appendQS( eshop::appendQS( eshop::curPageURL(), 'callback', 'parsian' ), 'transactionCode', $data['transactionCode'] );
            $params      = array(
                'pin'         => $this->pin,
                'price'       => $data['price'],
                'orderId'     => $this->transactionCodeTonumber( $data['transactionCode'] ),
                'callbackUrl' => $callbackUrl,
                'authority'   => $authority,
                'status'      => $status
            );
            $sendParams  = array( $params );
            $res         = $nusoap_client->call( 'PinPaymentRequest', $sendParams );
            $authority   = @$res['authority'];
            $status      = @$res['status'];
            if ( ( $authority ) and ( $status == 0 ) )
            {
                $result['status']   = true;
                $result['trackKey'] = $authority;
            }
            else
            {
                $result['status'] = false;
                $result['error']  = 'عدم دریافت پاسخ از بانک.';
            }
        }
        return $result;
    }

    //verify payment function
    function callback( $data )
    {
        require_once(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'nusoap' . DIRECTORY_SEPARATOR . 'nusoap.php');
        $result           = array();
        $result['status'] = false;
        if ( empty( $_REQUEST['au'] ) || !isset( $_REQUEST['rs'] ) || !isset( $_REQUEST['transactionCode'] ) )
        {
            $result['error'] = 'اطلاعات ارسالی ناقص است.';
        }
        else
        {
            if ( $_REQUEST['rs'] != 0 )
            {
                $result['error'] = 'پرداخت موفقیت آمیز نبود.';
            }
            else
            {
                $soapclient = new nusoap_client( 'https://www.pecco24.com:27635/pecpaymentgateway/eeshopservice.asmx?wsdl', 'wsdl' );
                if ( (!$soapclient) OR ($err        = $soapclient->getError()) )
                {
                    $result['error'] = 'خطا در برقراری ارتباط با بانک پارسیان.';
                }
                else
                {
                    $params     = array(
                        'pin'       => $this->pin,
                        'authority' => $data['trackKey'],
                        'status'    => 1 );
                    $sendParams = array( $params );
                    $res        = $soapclient->call( 'PinPaymentEnquiry', $sendParams );
                    $status     = $res['status'];
                    if ( $status === 0 || $status === '0' )
                    {
                        $result['status'] = true;
                    }
                    else
                    {
                        $result['error'] = 'پرداخت موفقیت آمیز نبود.';
                    }
                }
            }
        }
        return $result;
    }

    //redirect to bank function
    function redirect( $data )
    {
        $go = $this->redirecturl . $data['trackKey'];
        @header( "Location: $go" );
        exit( "<script>document.location='$go';</script>" );
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
