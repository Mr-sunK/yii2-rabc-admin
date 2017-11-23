<?php
namespace common\components\Lib\Weichat;
use common\components\Util\Http;

/**
 * 微信支付服务器端下单
 * 微信APP支付文档地址:  https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=8_6
 * 使用示例
 *  构造方法参数
 *      'appid'     =>  //填写微信分配的公众账号ID
 *      'mch_id'    =>  //填写微信支付分配的商户号
 *      'notify_url'=>  //填写微信支付结果回调地址
 *      'key'       =>  //填写微信商户支付密钥
 *  );
 *  统一下单方法
 *  $WechatAppPay = new wechatAppPay($options);
 *  $params['body'] = '商品描述';                   //商品描述
 *  $params['out_trade_no'] = '1217752501201407';   //自定义的订单号，不能重复
 *  $params['total_fee'] = '100';                   //订单金额 只能为整数 单位为分
 *  $params['trade_type'] = 'APP';                  //交易类型 JSAPI | NATIVE |APP | WAP
 *  $wechatAppPay->unifiedOrder( $params );
 */
class AppPay {
    //接口API URL前缀
    const API_URL_PREFIX = 'https://api.mch.weixin.qq.com';
    //下单地址URL
    const UNIFIEDORDER_URL = "/pay/unifiedorder";
    //查询订单URL
    const ORDERQUERY_URL = "/pay/orderquery";
    //关闭订单URL
    const CLOSEORDER_URL = "/pay/closeorder";
    //申请退款URL
    const REFUND_URL = '/secapi/pay/refund';
    //证书路径
    const SSLCERT_PATH = '/etc/apiclient_cert.pem';
    const SSLKEY_PATH = '/etc/apiclient_key.pem';
    //公众账号ID
    private $appid;
    //商户号
    private $mch_id;
    //随机字符串
    private $nonce_str;
    //签名
    private $sign;
    //商品描述
    private $body;
    //商户订单号
    private $out_trade_no;
    //支付总金额
    private $total_fee;
    //终端IP
    private $spbill_create_ip;
    //支付结果回调通知地址
    private $notify_url;
    //交易类型
    private $trade_type;
    //openid
    private $openid;
    //支付密钥
    private $key;
    //退款金额
    private $refund_fee;
    //商户退款单号
    private $out_refund_no;
    //操作员帐号, 默认为商户号
    private $op_user_id;
    //微信订单号
    private $transaction_id;

    //所有参数
    private $params = array();
    public function __construct($appid, $mch_id, $notify_url = '', $key) {
        $this->appid = $appid;
        $this->mch_id = $mch_id;
        $this->notify_url = $notify_url;
        $this->key = $key;
    }
    /**
     * 下单方法
     * @param   $params 下单参数
     */
    public function unifiedOrder($params) {
        $this->body = $params['body'];
        $this->out_trade_no = $params['out_trade_no'];
        $this->total_fee = $params['total_fee'];
        $this->trade_type = $params['trade_type'];
        $this->openid = $params['openid'];
        $this->nonce_str = self::random_string(32);
        $this->spbill_create_ip = $params['spbill_create_ip'];
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = $this->nonce_str;
        $this->params['body'] = $this->body;
        $this->params['out_trade_no'] = $this->out_trade_no;
        $this->params['total_fee'] = $this->total_fee;
        $this->params['spbill_create_ip'] = $this->spbill_create_ip;
        $this->params['notify_url'] = $this->notify_url;
        $this->params['trade_type'] = $this->trade_type;
        $this->params['openid'] = $this->openid;
        //获取签名数据
        $this->sign = $this->MakeSign($this->params);
        $this->params['sign'] = $this->sign;
        $xml = self::array2xml($this->params);
        $response = Http::ihttp_post(self::API_URL_PREFIX . self::UNIFIEDORDER_URL, $xml);
        if (!$response) {
            return false;
        }

        $result = self::xml2array($response['content']);
        if (!empty($result['result_code']) && !empty($result['err_code'])) {
            $result['err_msg'] = $this->error_code($result['err_code']);
        }
        return $result;
    }
    /**
     * 查询订单信息
     * @param $out_trade_no     订单号
     * @return array
     */
    public function orderQuery($out_trade_no) {
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = self::random_string(32);
        $this->params['out_trade_no'] = $out_trade_no;
        //获取签名数据
        $this->sign = $this->MakeSign($this->params);
        $this->params['sign'] = $this->sign;
        $xml = self::array2xml($this->params);
        $response = Http::ihttp_post(self::API_URL_PREFIX . self::ORDERQUERY_URL, $xml);
        if (!$response) {
            return false;
        }
        $result = self::xml2array($response['content']);
        if (!empty($result['result_code']) && !empty($result['err_code'])) {
            $result['err_msg'] = $this->error_code($result['err_code']);
        }
        return $result;
    }
    /**
     * 关闭订单
     * @param $out_trade_no     订单号
     * @return array
     */
    public function closeOrder($out_trade_no) {
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = self::random_string(32);
        $this->params['out_trade_no'] = $out_trade_no;
        $this->params['total_fee'] = $this->total_fee;
        //获取签名数据
        $this->sign = $this->MakeSign($this->params);
        $this->params['sign'] = $this->sign;
        $xml = self::array2xml($this->params);
        $response = self::curl_post_ssl(self::API_URL_PREFIX . self::CLOSEORDER_URL, $xml);
        return $response;
        if (!$response) {
            return false;
        }
        $result = self::xml2array($response['content']);
        return $result;
    }
    /**
     *
     * 获取支付结果通知数据
     * return array
     */
    public function getNotifyData() {
        //获取通知的数据
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        $data = array();
        if (empty($xml)) {
            return false;
        }
        $data = self::xml2array($xml);
        if (!empty($data['return_code'])) {
            if ($data['return_code'] == 'FAIL') {
                return false;
            }
        }
        return $data;
    }

    /**
    *
    * 申请退款
    * return array
    */
    public function refundOrder($params){
        $this->transaction_id = $params['transaction_id'];
        $this->total_fee = $params['total_fee'];
        $this->refund_fee = $params['refund_fee'];//
        $this->out_refund_no = $params['out_refund_no'];
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = self::random_string(32);
        $this->params['transaction_id'] =  $this->transaction_id;
        $this->params['out_refund_no'] =  $this->out_refund_no;//
        $this->params['total_fee'] = $this->total_fee;
        $this->params['refund_fee'] = $this->refund_fee;
        $this->params['op_user_id'] = $this->mch_id;
        //获取签名数据
        $this->sign = $this->MakeSign($this->params);
        $this->params['sign'] = $this->sign;
        $xml = self::array2xml($this->params);
        $response = $this->curl_post_ssl(self::API_URL_PREFIX . self::REFUND_URL, $xml);
        if (!$response) {
            return false;
        }
        $result = self::xml2array($response);
        if (!empty($result['result_code']) && !empty($result['err_code'])) {
            $result['err_msg'] = $this->error_code($result['err_code']);
        }
        return $result;
    }
    /**
     * 接收通知成功后应答输出XML数据
     * @param string $xml
     */
    public function replyNotify($data = array()) {
        if (empty($data)) {
            $data['return_code'] = 'SUCCESS';
            $data['return_msg'] = 'OK';
        }
        $xml = self::array2xml($data);
        echo $xml;
        die();
    }
    /**
     * 生成APP端支付参数
     * @param  $prepayid   预支付id
     */
    public function getAppPayParams($prepayid) {
        $data['appid'] = $this->appid;
        $data['partnerid'] = $this->mch_id;
        $data['prepayid'] = $prepayid;
        $data['package'] = 'Sign=WXPay';
        $data['noncestr'] = self::random_string(32);
        $data['timestamp'] = time();
        $data['sign'] = $this->MakeSign($data);
        return $data;
    }
    /**
     * 生成签名
     *  @return 签名
     */
    public function MakeSign($params) {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams($params) {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond() {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        return $time;
    }

    private function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        
        //以下两种方式需选择一种
        
        //第一种方法，cert 与 key 分别属于两个.pem文件
        curl_setopt($ch,CURLOPT_SSLCERT,self::SSLCERT_PATH);
        curl_setopt($ch,CURLOPT_SSLKEY,self::SSLKEY_PATH);
        
        //第二种方式，两个文件合成一个.pem文件
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
     
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
     
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else { 
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n"; 
            curl_close($ch);
            return false;
        }
    }

    /**
     * 错误代码
     * @param  $code       服务器输出的错误代码
     * return string
     */
    public function error_code($code) {
        $errList = array(
            'NOAUTH' => '商户未开通此接口权限', 
            'NOTENOUGH' => '用户帐号余额不足', 
            'ORDERNOTEXIST' => '订单号不存在',
            'ORDERPAID' => '商户订单已支付，无需重复操作', 
            'ORDERCLOSED' => '当前订单已关闭，无法支付', 
            'SYSTEMERROR' => '系统错误!系统超时', 
            'APPID_NOT_EXIST' => '参数中缺少APPID', 
            'MCHID_NOT_EXIST' => '参数中缺少MCHID', 
            'APPID_MCHID_NOT_MATCH' => 'appid和mch_id不匹配', 
            'LACK_PARAMS' => '缺少必要的请求参数', 
            'OUT_TRADE_NO_USED' => '同一笔交易不能多次提交', 
            'SIGNERROR' => '参数签名结果不正确', 
            'XML_FORMAT_ERROR' => 'XML格式错误', 
            'REQUIRE_POST_METHOD' => '未使用post传递参数 ', 
            'POST_DATA_EMPTY' => 'post数据不能为空', 
            'NOT_UTF8' => '未使用指定编码格式',
            'USER_ACCOUNT_ABNORMAL' => '退款请求失败',
            'NOTENOUGH' => '余额不足',
            'INVALID_TRANSACTIONID' => '无效transaction_id',
            'PARAM_ERROR' => '参数错误',
        );
        if (array_key_exists($code, $errList)) {
            return $errList[$code];
        }
    }

    /**
     * 数组转xml
     * @param $arr
     * @param int $level
     * @return mixed|string
     */
    public static function array2xml($arr, $level = 1) {
        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . self::array2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;
    }

    /**
     * @param $xml
     * @return array|mixed|string
     */
    public static function xml2array($xml) {
        if (empty($xml)) {
            return array();
        }
        $result = array();
        $xmlobj = self::isimplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($xmlobj instanceof SimpleXMLElement) {
            $result = json_decode(json_encode($xmlobj), true);
            if (is_array($result)) {
                return $result;
            } else {
                return '';
            }
        } else {
            return $result;
        }
    }

    public static function isimplexml_load_string($string, $class_name = 'SimpleXMLElement', $options = 0, $ns = '', $is_prefix = false) {
        libxml_disable_entity_loader(true);
        if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
            return false;
        }
        return simplexml_load_string($string, $class_name, $options, $ns, $is_prefix);
    }



    /**
     * 随机字符串生成
     * @param int $len 生成的字符串长度
     * @param int $rule 字符串规则 1.纯数字 2.纯小写字母 3.纯大写字母 4.大小写字母混合 5.大小写字母混合加数字(default)
     * @return string
     */
    public static function random_string($len = 6,$rule = 0) {
        $chars = array();

        $lower = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z"
        );

        $capital = array(
            "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z"
        );

        $nums = array("0", "1", "2","3", "4", "5", "6", "7", "8", "9");

        switch ($rule) {
            case '1':
                $chars = $nums;
                break;
            case '2':
                $chars = $lower;
                break;
            case '3':
                $chars = $capital;
                break;
            case '4':
                $chars = array_merge($lower,$capital);
                break;
            case '5':
                $chars = array_merge($lower,$capital,$nums);
                break;
            default:
                $chars = array_merge($lower,$capital,$nums);
                break;
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}