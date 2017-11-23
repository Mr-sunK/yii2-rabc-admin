<?php
namespace common\transformer;

/**
 * 转化器
 * @author Evan <tangzwgo@foxmail.com>
 * @since 2016年7月10日
 */
abstract class Transformer {
	public function TransformerCollection($items) {
		return array_map([$this, 'transformer'], $items);
	}
	
	public abstract function transformer($item);
}