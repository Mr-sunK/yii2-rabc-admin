<?php
namespace common\components\Util;
use Yii;
/**
 * Created by PhpStorm.
 * User: Alex-sun
 * Date: 2016/10/14
 * Time: 14:57
 */

 class UploadImage
 {

 	private $file;

 	private $type = array('.jpg','.png','.jpeg','.gif');

 	private $size = 2097152;

 	private $path;

 	public $newFile = array();

 	public $ext;

	public $fsize = 0; //实际大小

	public $name;//名称

 	public function __construct($file,$path,$type = null,$size = null)
 	{
 		$this->file = $file;

 		$this->path = $path;

 		if($type)
 		{
 			$this->type = $type;
 		}
 		if($size)
 		{
 			$this->size = $size;
 		}
 	}

 	public function upload()
 	{
 		if(!$this->checkType())
 		{
 			echo 0;exit();
 		}

 		if(!$this->checkSize())
 		{
 			echo 0;exit();
 		}

 		$this->getFileInfo();

 		if(!move_uploaded_file($this->file['tmp_name'],$this->path.$this->newFile))
	    {
	    	echo 0;exit();
	    }
 	}

 	private function checkSize()
    {
    	$this->fsize = $this->file['size'];
	  	if( $this->size < $this->file['size'])
	  	{
	  		return false;
	  	}
	  	return true;
  	}

	private function checkType()
	{
	  	$type = '.'.$this->getExtendName($this->file['name']);

		if(!in_array(strtolower($type),$this->type))
	  	{
	  		return false;
	  	}
	  	return true;
	}

	private function  getName()
  	{
    	//获取名称
		$title = explode('.',$this->file['name']);
		array_pop($title);
		$title = implode('.', $title);

    	return $title;
  	}

	private function  getExtendName()
  	{
    	$extend = pathinfo($this->file['name']);

    	$extend = strtolower($extend["extension"]);

    	return $extend;
  	}

  	private function getFileInfo()
    {

    	$type = $this->getExtendName();

		$this->newFile = 'image-'.time().'.'.rand(100,9999999).'.'.$type;

	    $this->ext = $type;

		$this->name = $this->getName();

	    if(!is_dir($this->path))
	    {
	    	@mkdir($this->path,0777,true);
			@umask($this->path,0777,true);
	    }

	 }

 }
