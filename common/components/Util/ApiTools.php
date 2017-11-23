<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 11:36
 * 公共函数
 */

namespace common\components\Util;
use Yii;

class ApiTools
{
    /**
     * 接口权限检测
     */
    public static function api_check() {
        //兼容json数据和普通数据
        if(isset($_SERVER['CONTENT_TYPE']) && stristr($_SERVER['CONTENT_TYPE'],'application/json')) {
            $requestData = $GLOBALS['HTTP_RAW_POST_DATA'];
            $requestData = json_decode($requestData, true);
        } else {
            $requestData = $_POST;
        }

        $app_id = isset($requestData['appid']) ? $requestData['appid'] : '';

        if(!$app_id) {
            return self::Response(1001, '参数异常');
        }
        if(!in_array($app_id,['KK_IOS','KK_ANDROID'])){
            return self::Response(1003, '您没有访问权限');
        }
        //查询应用信息
        $appInfo = Yii::$app->params[$app_id];

        if(!$appInfo) {
            return self::Response(1002, '您没有访问权限');
        }

        //验证token是否合法
        $sign = $requestData['sign'];
        if(!$sign) {
            return self::Response(1005, '您没有访问权限');
        }

        $timeStamp = $requestData['timestamp'];
        if(!$timeStamp) {
            return self::Response(1006, '请求不合法');
        }

        $newSign = md5($appInfo['token'] . $timeStamp . $appInfo['token']);

        if($newSign != $sign) {
            return self::Response(1007, '您没有访问权限');
        }

        $requestData['_AppInfo'] = $appInfo;

        return $requestData;
    }

    /**
     * 返回数据
     * @param int $ResponseCode    响应码
     * @param string $ResponseMsg   响应消息
     * @param array $ResponseData   响应数据
     * @return array|bool
     */
    public static function ReturnMsg($ResponseCode = 999,$ResponseMsg = '调用成功',$ResponseData = array()){
        if(!is_numeric($ResponseCode)) {
            return false;
        }
        $result = array(
            'Code'=>$ResponseCode,
            'Msg'=>$ResponseMsg,
            'Data'=>$ResponseData
        );
        return $result;
    }

    /**
     * 接口数据响应
     * @param int $ResponseCode 响应码
     * @param string $ResponseMsg 响应消息
     * @param array $ResponseData 响应数据
     * @param string $ResponseType 响应数据类型
     * @return string
     */
    public static function Response($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array(),$ResponseType = 'json'){
        if(!is_numeric($ResponseCode)) {
            return '';
        }

        $ResponseType = isset($_GET['format']) ? $_GET['format'] : $ResponseType;

        $result = array(
            'Code'=>$ResponseCode,
            'Msg'=>$ResponseMsg,
            'Data'=>$ResponseData
        );

        if($ResponseType == 'json') {
            self::json($ResponseCode, $ResponseMsg, $ResponseData);
            exit();
        } else if($ResponseType == 'xml') {
            self::xmlencode($ResponseCode, $ResponseMsg, $ResponseData);
            exit();
        } else if($ResponseType == 'array') {
            var_dump($result);
            exit();
        } else {
            self::json($ResponseCode, $ResponseMsg, $ResponseData);
            exit();
        }
    }

    /**
     * 响应Json格式数据
     * @param int $ResponseCode 响应码
     * @param string $ResponseMsg  响应消息
     * @param array $ResponseData  响应数据
     * @return string
     */
    public static function json($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array()){
        if(!is_numeric($ResponseCode)) {
            return '';
        }

        $result = array(
            'Code'=>$ResponseCode,
            'Msg'=>$ResponseMsg,
            'Data'=>$ResponseData,
            'Type'=>'json'
        );

        //header("Content-type: text/html; charset=utf-8");
        echo json_encode($result);
        exit();
    }

    /**
     * 响应xml格式数据
     * @param int $ResponseCode 响应码
     * @param string $ResponseMsg 响应消息
     * @param array $ResponseData 响应数据
     * @return string
     */
    public static function xmlencode($ResponseCode = 999,$ResponseMsg = '接口请求成功',$ResponseData = array()) {
        if (!is_numeric($ResponseCode)) {
            return '';
        }

        $result = array(
            'Code'=>$ResponseCode,
            'Msg'=>$ResponseMsg,
            'Data'=>$ResponseData,
            'Type'=>'xml'
        );

        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";

        $xml .= self::xml_to_encode($result);

        $xml .= "</root>";
        echo $xml;
    }

    /**
     * 将数据编码成xml格式
     * @param $data
     * @return string
     */
    public static function xml_to_encode($data) {
        $xml = $attr = "";
        foreach($data as $key => $value) {
            if(is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xml_to_encode($value) : $value;
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }

    /**
     * 生成一个目录下唯一的文件名
     * @param type $path    路径
     * @param type $name    名称
     * @param type $suffix  后缀
     * @param type $num     数字扩展
     */
    public static function only_file_name($path,$name,$suffix,$num = 0){
        $file_path = $path.$name.'.'.$suffix;
        $file_name = $name.'.'.$suffix;
        if($num > 0) {
            $file_path = $path.$name.$num.'.'.$suffix;
            $file_name = $name.$num.'.'.$suffix;
        }

        if(file_exists($file_path)) {
            $num ++;
            return self::only_file_name($path,$name,$suffix,$num);
        } else {
            return $file_name;
        }
    }

    /**
     * 加载图片
     */
    public static function load_img($img) {
        //$img_domain = Yii::$app->params['STATIC_CDN_DOMAIN'];
        $img_domain = Yii::$app->params['STATIC_DOMAIN'];
        $img_version = Yii::$app->params['IMG_VERSION'];
        $url = 'http://' . $img_domain . '/' . $img . '?v=' . $img_version;
        return $url;
    }

    /**
     * 生成一个订单编号
     */
    public static function create_order_code() {
        $code = substr(date('Y'),-2) . date('mdHis') . rand(1000, 9999);
        return $code;
    }
    //移动图片

    public static function  moveFile($a,$b) {
        $path = dirname($b);
        if(!is_dir($path))
        {
            @mkdir($path,0755,true);
            @umask($path,0755,true);
        }

        if(!file_exists($a))
        {
            return false; // 文件不存在
        }

        if(copy($a,$b))
        {
            //@unlink($a);
            $a = explode('.',$a);
            $gif = array_pop($a);//后缀
            $a = implode('.', $a);
            $a_small = $a.'_small.'.$gif;
            $a_middle = $a.'_middle.'.$gif;

            $b = explode('.',$b);
            $gif = array_pop($b);//后缀
            $b = implode('.', $b);
            $b_small = $b.'_small.'.$gif;
            $b_middle = $b.'_middle.'.$gif;
            if(file_exists($a_small)){
                copy($a_small,$b_small);
            }
            if(file_exists($a_middle)){
                copy($a_middle,$b_middle);
            }

            return true;
        }
        else return false;
    }

    #获取IP地址
    public static  function getIp(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }

    /**
     * 生成图片缩略图
     */
    public static function resizeImg($srcName,$newName,$newWidth,$newHeight)
    {
        $thumArray = pathinfo($srcName);

        $info = "";
        $data = getimagesize($srcName,$info);
        switch ($data[2])
        {
            case 1:
                if(!function_exists("imagecreatefromgif")){
                    echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！返回";
                    exit();
                }
                $im = ImageCreateFromGIF($srcName);
                break;
            case 2:
                if(!function_exists("imagecreatefromjpeg")){
                    echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！返回";
                    exit();
                }
                $im = ImageCreateFromJpeg($srcName);
                break;
            case 3:
                $im = ImageCreateFromPNG($srcName);
                break;
        }
        $srcW=ImageSX($im);
        $srcH=ImageSY($im);
        $newWidthH=$newWidth/$newHeight;
        $srcWH=$srcW/$srcH;
        if($newWidthH<=$srcWH){
            $ftoW=$newWidth;
            $ftoH=$ftoW*($srcH/$srcW);
        }
        else{
            $ftoH=$newHeight;
            $ftoW=$ftoH*($srcW/$srcH);
        }
        if($srcW>$newWidth||$srcH>$newHeight)
        {
            if(function_exists("imagecreatetruecolor"))
            {
                @$ni = ImageCreateTrueColor($ftoW,$ftoH);
                if($ni) ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
                else
                {
                    $ni=ImageCreate($ftoW,$ftoH);
                    ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
                }
            }
            else
            {
                $ni=ImageCreate($ftoW,$ftoH);
                ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
            }

            if(function_exists('imagejpeg')) ImageJpeg($ni,$newName);
            else ImagePNG($ni,$newName);
            ImageDestroy($ni);
        }else{
            $urlInfo = file_get_contents($srcName);
            file_put_contents($newName,$urlInfo);
        }
        ImageDestroy($im);
    }

    /**
     * html 过滤
     * @param $string
     * @param int $isUrl
     * @return array|mixed
     */
    public static function dhtmlspecialchars($string, $isUrl = 0)
    {
        if(is_array($string))
        {
            foreach($string as $key => $val)
            {
                $string[$key] = self::dhtmlspecialchars($val);
            }
        } else {
            if (!$isUrl)
            {
                $string = str_replace('&', '&', $string);
            }
            $string = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
                str_replace(array('&', '"', '<', '>'), array('&', '"', '<', '>'), $string));
        }
        return $string;
    }
    //格式化数组
    public static function  arrayFormat($arr,$parm,$type=1){
        if(!$arr || !is_array($arr)){
            return '';
        }
        $return = array();
        switch ($type){
            case 1://查询数据格式化
                foreach ($arr as $v){
                    $return[$v->{$parm['key']}] = $v->{$parm['val']};
                }

                break;
            case 2:
                foreach ($arr as $v){
                    $return[] = array($parm['key']=>$v->{$parm['key']},$parm['val']=>$v->{$parm['val']}) ;
                }
                break;
        }

        return $return;
    }

    /**
     * 读取/dev/urandom获取随机数
     * @param $len
     * @return mixed|string
     */
    public static function randomFromDev($len) {
        $fp = @fopen('/dev/urandom','rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        }
        else
        {
            trigger_error('Can not open /dev/urandom.');
            return substr(time().MD5(time().rand()), 0, $len);
        }
        // convert from binary to string
        $result = base64_encode($result);
        // remove none url chars
        $result = strtr($result, '+/', '-_');

        return substr($result, 0, $len);
    }

    /**
     * 字符串含有检测
     * @param $string 源字符串
     * @param $find 检测字符串
     * @return bool
     */
    public static function strexists($string, $find) {
        return !(strpos($string, $find) === FALSE);
    }

}