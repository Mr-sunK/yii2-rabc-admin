<?php
namespace common\transformer;

use common\transformer\Transformer;
use common\components\Util\ApiTools;

/**
 * 图片
 * @author Evan <tangzwgo@163.com>
 * @since 2016-07-30
 */
class ImageTransformer extends Transformer {
	public function transformer($item) {
		return [
				'image_id' => intval($item['id']),
				'max_url' => ApiTools::load_img($item['max_url']),
				'mini_url' => ApiTools::load_img($item['mini_url']),
				'max_size' => strval($item['max_size']),
				'mini_size' => strval($item['mini_size']),
		];
	}
}