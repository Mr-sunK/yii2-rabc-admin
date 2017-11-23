<?php
namespace backend\controllers;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 11:17
 */
use Yii;
use yii\web\Controller;
use common\components\Util\UploadImage;
use common\components\Util\ApiTools;
use common\models\Image;
use common\transformer\ImageTransformer;
use common\components\Util\ImageCrop;

class UploadController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 图片上传
     * extend：拓展目录
     * upload_file：上传文件
     */
    public function actionIndex(){

        $path = Yii::getAlias('@static').'/';
        $basePath = 'attachment/images/';
        if(!empty($_FILES)){
            //扩展路径
            $extend = isset($_POST['extend'])?$_POST['extend']:'';

            if ($_FILES['upload_file']['error'] != UPLOAD_ERR_OK) {
                return ApiTools::Response(2002, "文件上传出错");
            }

            //扩展目录
            if($extend) {
                $basePath .= $extend.'/';
            }

            $file = $_FILES['upload_file'];

            $type = array('.jpg','.png','.jpeg','.gif');
            $size = 2097152000;
            //开始上传
            $u = new UploadImage($file,$path.$basePath,$type,$size);
            $u->upload();

            //返回
            $name = explode('.',$u->newFile);
            $gif = array_pop($name);//后缀
            $name = implode('.', $name);

            $img_size = ceil(filesize($path.$basePath.$name.'.'.$gif)/1000);
            $arr = getimagesize($path.$basePath.$name.'.'.$gif);

            if($arr[0] < 200 || $arr[1] < 100)//宽度
            {
                ApiTools::Response(2001,"图片尺寸太小，请换张图试试！");
            }
            else
            {

                if ($img_size > 2048)
                {
                    ApiTools::Response(2001,'图片大小不超过2M，请换张图试试');
                }else {
                    $w = 300;
                    $h = 300;

                    if($extend == 'category') {
                        //分类
                        $w = 300;
                        $h = 300;
                    }elseif ($extend == 'goods'){
                        $w = 540;
                        $h = 320;
                    }elseif ($extend == 'head'){
                        $w = 300;
                        $h = 300;
                    }elseif ($extend == 'advert'){
                        $w = 750;
                        $h = 350;
                    }
                    //生成中图
                    //ApiTools::resizeImg($path.$basePath.$u->newFile,$path.$basePath.$name.'_middle.'.$gif,intval($w),intval($h));//middle

                    $max = $path.$basePath.$name.'.'.$gif;
                    $mini = $path.$basePath.$name.'_middle.'.$gif;
                    $max_url = $basePath.$name.'.'.$gif;
                    $mini_url = $basePath.$name.'_middle.'.$gif;

                    $ic = new ImageCrop($max, $mini);
                    $ic->Crop(intval($w),intval($h),1);
                    $ic->SaveImage();
                    $ic->destory();

                    $maxSize = getimagesize($max);
                    $miniSize = getimagesize($mini);
                    //将图片地址存入数据库
                    $image = [];
                    $image['max_url'] = $max_url;
                    $image['mini_url'] = $mini_url;
                    $image['max_path'] = $max;
                    $image['mini_path'] = $mini;
                    $image['max_size'] = $maxSize[0] . 'x' . $maxSize[1];
                    $image['mini_size'] = $miniSize[0] . 'x' . $miniSize[1];
                    $image['create_time'] = time();
                    $model = new Image();
                    $model->setAttributes($image);
                    $model->save();
                    $image['id'] = $model->id;
                    $imageTransformer = new ImageTransformer();
                    $image = $imageTransformer->transformer($image);

                    ApiTools::Response(999,'上传成功',$image);
                }
            }
        } else{
            ApiTools::Response(2002,"无上传文件");
        }
    }

}