<?php

namespace api\model\libs;

use api\model\AbtractBiz;
use api\model\PaymentInterface;
use api\model\dao\shop\ShopPaymentDao;
use JUri;

class VnpayGateway extends AbtractBiz implements PaymentInterface
{
    private $token = 'XHGDDBYKDHVCFZPEIQHRHOMHLSNDJOSB';
    private $merchant = 'MINHCAU1';
    private $url_request = '';
    private $url_base = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html?';
    private $error;
    private $order_id;
    private $requestParams = array(
        'vnp_Version' => '2.0.0',
        'vnp_CurrCode' => 'VND',
        'vnp_Command' => 'pay',
        'vnp_TmnCode' => '',
        'vnp_CreateDate' => '',
        'vnp_Locale' => 'vn',
        'vnp_ReturnUrl' => '',
        'vnp_TxnRef' => '',
        'vnp_OrderInfo' => '',
        'vnp_Amount' => '',
        'vnp_IpAddr' => ''
    );

    private $sendParams = array();

    private $responeCode = '';

    public function __construct($config)
    {
        if ($config->get('enviroment') === 'dev') {
            $this->token = 'XHGDDBYKDHVCFZPEIQHRHOMHLSNDJOSB';
            $this->merchant = 'MINHCAU1';
            $this->url_base = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html?';
        } else {
            $this->token = $config->get('token');
            $this->merchant = $config->get('merchant_id');
            $this->url_base = 'https://pay.vnpay.vn/vpcpay.html?';
        }
    }


    public function purchase()
    {
        // TODO: Implement processPayment() method.
        $url = $this->url_request;
        $dao = new ShopPaymentDao();
        CommonGateway::updateOrder(array(
            'set' => array(
                'payment_request = ' . $dao->db->quote($url),
                'payment_request_date = ' . $dao->db->quote(\JFactory::getDate()->toSql())
            ),
            'where' => array(
                'id = ' . $this->order_id
            )
        ));
        return $url;
    }

    public function setParams($params = array())
    {
        $this->order_id = $params['order_id'];
        $params['order_id'] .= '_' . time();
        $this->requestParams['vnp_TmnCode'] = $this->merchant;

        $this->requestParams['vnp_TxnRef'] = $params['order_id'];
        $this->requestParams['vnp_OrderInfo'] = $params['order_no'];
        $this->requestParams['vnp_Amount'] = (int)$params['order_amount'] * 100;
        $this->requestParams['vnp_IpAddr'] = @$_SERVER['REMOTE_HOST'] ? @$_SERVER['REMOTE_HOST'] : @$_SERVER['REMOTE_ADDR'];
        $this->requestParams['vnp_CreateDate'] = date('YmdHis', strtotime("+7 hours"));
        $this->requestParams['vnp_ReturnUrl'] = $params['return_url'];
        // TODO: Implement setParams() method.
        $this->sendParams = array();
        $hashParams = array();
        ksort($this->requestParams);
        foreach ($this->requestParams as $key => $val) {
            $val = trim($val);
            $this->sendParams[] = "{$key}=" . urlencode($val);
            $hashParams[] = "{$key}={$val}";

        }
        $this->sendParams[] = 'vnp_SecureHashType=SHA256';
        $this->sendParams[] = 'vnp_SecureHash=' . hash('sha256', $this->token . implode('&', $hashParams));

        $this->url_request = $this->url_base . implode('&', $this->sendParams);
    }


    public function validatePayment($params = array())
    {

        ksort($params);
        $hashParams = array();
        foreach ($params as $key => $value) {
            if ($key == 'vnp_SecureHashType' || $key == 'vnp_SecureHash') {
                continue;
            }
            if (substr($key, 0, 4) == "vnp_") {
                $hashParams[] = "{$key}={$value}";
            }
        }
        $secureHash = hash('sha256', $this->token . implode('&', $hashParams));
        $this->responeCode = $params['vnp_ResponseCode'];
        return $secureHash == $params['vnp_SecureHash'];
    }

    public function isSuccessPayment()
    {
        return $this->responeCode === '00';
    }


    public function getError()
    {
        return static::getPaymentError($this->responeCode);
    }

    public static function getPaymentError($responseCode)
    {

        switch ($responseCode) {
            case "00" :
                $result = "Giao d???ch th??nh c??ng";
                break;
            case "01" :
                $result = "Giao d???ch ???? t???n t???i";
                break;
            case "02" :
                $result = "Merchant kh??ng h???p l??? (ki???m tra l???i vnp_TmnCode)";
                break;
            case "03" :
                $result = "D??? li???u g???i sang kh??ng ????ng ?????nh d???ng";
                break;
            case "04" :
                $result = "Kh???i t???o GD kh??ng th??nh c??ng do Website ??ang b??? t???m kh??a";
                break;
            case "05" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: Qu?? kh??ch nh???p sai m???t kh???u qu?? s??? l???n quy ?????nh. Xin qu?? kh??ch vui l??ng th???c hi???n l???i giao d???ch";
                break;
            case "13" :
                $result = "Giao d???ch kh??ng th??nh c??ng do Qu?? kh??ch nh???p sai m???t kh???u x??c th???c giao d???ch (OTP). Xin qu?? kh??ch vui l??ng th???c hi???n l???i giao d???ch.";
                break;
            case "07" :
                $result = "Giao d???ch b??? nghi ng??? l?? giao d???ch gian l???n";
                break;
            case "09" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: Th???/T??i kho???n c???a kh??ch h??ng ch??a ????ng k?? d???ch v??? InternetBanking t???i ng??n h??ng.";
                break;
            case "10" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: Kh??ch h??ng x??c th???c th??ng tin th???/t??i kho???n kh??ng ????ng qu?? 3 l???n";
                break;
            case "11" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: ???? h???t h???n ch??? thanh to??n. Xin qu?? kh??ch vui l??ng th???c hi???n l???i giao d???ch.";
                break;
            case "12" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: Th???/T??i kho???n c???a kh??ch h??ng b??? kh??a.";
                break;
            case "51" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: T??i kho???n c???a qu?? kh??ch kh??ng ????? s??? d?? ????? th???c hi???n giao d???ch.";
                break;
            case "65" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: T??i kho???n c???a Qu?? kh??ch ???? v?????t qu?? h???n m???c giao d???ch trong ng??y.";
                break;
            case "08" :
                $result = "Giao d???ch kh??ng th??nh c??ng do: H??? th???ng Ng??n h??ng ??ang b???o tr??. Xin qu?? kh??ch t???m th???i kh??ng th???c hi???n giao d???ch b???ng th???/t??i kho???n c???a Ng??n h??ng n??y.";
                break;
            case "99" :
                $result = "C??c l???i kh??c (l???i c??n l???i, kh??ng c?? trong danh s??ch m?? l???i ???? li???t k??)";
                break;
            default :
                $result = "Giao d???ch th???t b???i";
        }
        return $result;
    }
}
