<?php
namespace common\components\Util;
/**
 * Created by PhpStorm.
 * User: Alex-sun
 * Date: 2016/10/14
 * Time: 14:56
 */
use Yii;
use OSS\OssClient;
use OSS\Core\OssException;

class OSS{
    private $accessKeyId;
    private $accessKeySecret;
    private $endpoint;
    private $bucket;
    private $client;

    public function __construct()
    {
        $this->accessKeyId = Yii::$app->params['oss']['accessKeyId'];
        $this->accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];
        $this->endpoint = Yii::$app->params['oss']['endpoint'];
        $this->bucket = Yii::$app->params['oss']['bucket'];
        try {
            $this->client = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }


    //$object,$file
    //$object 上传到oss的目录和文件名(数组和字符串)
    //$file要上传的文件(数组和字符串 ，和object 一一对应)
    //$domain 上传的文件的第一级目录默认为oss
    public function ossUpFiles($object,$file,$domain = 'oss'){
        if(is_array($object) && is_array($file) && count($object) == count($file)){
            foreach ($object as $k=>$v){
                if($v && isset($file[$k]))
                    $this->ossUpfile($domain."/".$v, $file[$k]);
            }
        }else if(is_array($object)){
            foreach ($object as $v){
                $this->ossUpfile($domain."/".$v, $file);
            }
        }else{
            $this->ossUpfile($domain."/".$object, $file);
        }
    }

    /**
     * 单文件上传
     * @param $object
     * @param $file
     */
    private function ossUpfile($object,$file){
        if(is_file($file)){
            $res = $this->client->uploadFile($this->bucket, $object,$file);
            unlink($file);
        }
    }

    /**
     * 文件删除
     * @param $objects
     * @param string $domain
     */
    public function ossDeleteFiles($objects,$domain = 'oss'){
        if(is_array($objects)){
            foreach ($objects as &$v){
                $v = $domain.'/'.$v;
            }
            $res = $this->client->deleteObjects($this->bucket, $objects);
        }elseif(is_string($objects) && $objects){
            $objects = $domain.'/'.$objects;
            $res = $this->client->deleteObject($this->bucket, $objects);
        }else{
            return;
        }
    }
}