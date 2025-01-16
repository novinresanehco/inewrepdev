<?php

class payment_payline
{

    public $api = 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
    public $url = 'http://payline.ir/payment-test';

	function __construct()
	{
		global $config;
		$variables = get_object_vars( $this );
		foreach( $variables as $key => $value )
		{
			$this->$key = isset( $config['payline_' . $key] ) ? $config['payline_' . $key] : $value;
		}
	}
	
    //start payment function
    function start( $data )
    {
        $result = array();
        $url    = $this->url. '/gateway-send';

        $redirect = urlencode( eshop::appendQS( eshop::appendQS( eshop::curPageURL(), 'callback', 'payline' ), 'transactionCode', $data['transactionCode'] ) );
        $payline  = $this->send( $url, $this->api, $data['price'], $redirect );
        if ( is_numeric( $payline ) && $payline > 0 )
        {
            $result['status']   = true;
            $result['trackKey'] = $payline;
        }
        else
        {
            $result['status'] = false;
            $result['error']  = $this->sendError( $payline );
        }
        return $result;
    }

    //verify payment function
    function callback( $data )
    {
        $result           = array();
        $result['status'] = false;
        if ( empty( $_POST['trans_id'] ) OR empty( $_POST['id_get'] ) )
        {
            $result['error'] = 'اطلاعات ارسالی ناقص است.';
        }
        else
        {
            $url      = $this->url.'/gateway-result-second';
            $trans_id = $_POST['trans_id'];
            $id_get   = $_POST['id_get'];
            $payline  = $this->get( $url, $this->api, $trans_id, $id_get );
            if ( $payline == 1 )
            {
                //succesfull payment
                $result['status'] = true;
            }
            else
            {
                $result['status'] = false;
                $result['error']  = $this->verifyError( $payline );
            }
        }
        return $result;
    }

    //redirect to bank functionF
    function redirect( $data )
    {
        $go = $this->url . "/gateway-" . $data['trackKey'];
        @header( "Location: $go" );
        exit( "<script>document.location='$go';</script>" );
    }

    /*
     * payline send/get functions
     */

    function send( $url, $api, $price, $redirect )
    {
        $ch  = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, "api=$api&amount=$price&redirect=$redirect" );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $res = curl_exec( $ch );
        curl_close( $ch );
        return$res;
    }

    function get( $url, $api, $trans_id, $id_get )
    {
        $ch  = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, "api=$api&id_get=$id_get&trans_id=$trans_id" );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $res = curl_exec( $ch );
        curl_close( $ch );
        return$res;
    }

    //detect payline error
    function sendError( $message )
    {
        switch ( $message )
        {
            case -1:
                $error = 'شناسه api نامعتبر است.';

                break;
            case -2:
                $error = 'حداقل مبلغ قابل پرداخت ۱۰۰۰ ریال است.';
                break;
            case -3:
                $error = 'آدرس بازگشت نامعتبر است.';
                break;
            case -4:
                $error = 'درگاه فعال نیست.';
                break;
            default:
                $error = 'عملیات با خطا مواجه شده است.';
                break;
        }
        return $error;
    }

    function verifyError( $message )
    {
        switch ( $message )
        {
            case -1:
                $error = 'شناسه api نامعتبر است.';

                break;
            case -2:
                $error = 'اطلاعات ارسالی نامعتبر است.';
                break;
            case -3:
                $error = 'اطلاعات ارسالی نامعتبر است.';
                break;
            case -4:
                $error = 'چنین تراکنشی در سیستم وجود ندارد و یا با خطا مواجه شده است.';
                break;
            default:
                $error = 'عملیات با خطا مواجه شده است.';
                break;
        }
        return $error;
    }

}
