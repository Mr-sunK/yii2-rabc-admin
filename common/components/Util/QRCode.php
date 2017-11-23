<?php
namespace common\components\Util;

/**
 * 二维码
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016年12月1日
 */
class QRCode {
    /**
     * 生成店铺二维码
     * @param  [string] $url  [url地址]
     * @param  [string] $logo [图片地址]
     * @return [array]       [二维码图片信息]
     */
    public static function createShopQRCode($url, $logo) {
        if(!$url || !$logo) {
            return false;
        }

        //二维码存放位置
        $file_name = time() . '-' . rand(1000, 9999) . ".png";
        $real_path = "attachment/images/qrcode/";
        $img_path = C('STATIC_PATH') . $real_path;
        !is_dir($img_path) && @mkdir($img_path);

        $params = [];
        $params['url'] = $url;
        $params['logo'] = $logo;
        $params['level'] = 'Q';//纠错级别：L、M、Q、H
        $params['size'] = 10;//点的大小
        $params['img_path'] = $img_path . $file_name;//存放路径
        //$result = self::createLogoQRCode($params);//带头像二维码
        $result = self::createQRCode($params);//不带头像二维码
        if(!$result) {
            return false;
        }

        $imgSize = getimagesize($img_path . $file_name);

        $image = [];
        $image['max_url'] = $real_path . $file_name;
        $image['mini_url'] = $real_path . $file_name;
        $image['max_path'] = $img_path . $file_name;
        $image['mini_path'] = $img_path . $file_name;
        $image['max_size'] = $imgSize[0] . 'x' . $imgSize[1];
        $image['mini_size'] = $imgSize[0] . 'x' . $imgSize[1];
        $image_id = D('Resource/Image', 'Service')->addImage($image);
        $image['id'] = $image_id;
        return $image;
    }

    /**
     * 生成带logo的二维码
     * @param  [array] $params [参数]
     * @return [string]         [二维码图片地址]
     */
    public static function createLogoQRCode($params) {
        if(!isset($params['url']) || !isset($params['logo']) || !isset($params['img_path'])) {
            return false;
        }

        //生成二维码
        $result = self::createQRCode($params);
        if(!$result) {
            return false;
        }

        $url = $params['url'];
        $logo = $params['logo'];
        $img_path = $params['img_path'];

        //将logo添加到二维码中
        $QR = imagecreatefromstring(file_get_contents($img_path));
        $logo = imagecreatefromstring(file_get_contents($logo));
        $QR_width = imagesx($QR);//二维码图片宽度
        $QR_height = imagesy($QR);//二维码图片高度
        $logo_width = imagesx($logo);//logo图片宽度
        $logo_height = imagesy($logo);//logo图片高度
        $logo_qr_width = $QR_width / 5;
        $scale = $logo_width/$logo_qr_width;
        $logo_qr_height = $logo_height/$scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        //重新组合图片并调整大小
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        imagepng($QR, $img_path);

        return true;
    }

    /**
     * 生成二维码
     * @param  [array] $params [参数]
     * @return [string]         [二维码图片地址]
     */
    public static function createQRCode($params) {
        if(!isset($params['url']) || !isset($params['img_path'])) {
            return false;
        }
        $url = $params['url'];
        $img_path = $params['img_path'];
        $level = 'L';
        $size = 5;
        isset($params['level']) && in_array($params['level'], ['L','M','Q','H']) && $level = $params['level'];
        isset($params['size']) && in_array($params['size'], [1,2,3,4,5,6,7,8,9,10]) && $size = $params['size'];

        Vendor('phpqrcode.phpqrcode');
        \QRcode::png($url, $img_path, $level, $size);

        return true;
    }
}
