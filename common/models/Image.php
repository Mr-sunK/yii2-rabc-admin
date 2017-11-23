<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property string $max_url
 * @property string $mini_url
 * @property string $max_path
 * @property string $mini_path
 * @property integer $create_time
 * @property string $max_size
 * @property string $mini_size
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'integer'],
            [['max_url', 'mini_url', 'max_path', 'mini_path'], 'string', 'max' => 128],
            [['max_size', 'mini_size'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'max_url' => 'Max Url',
            'mini_url' => 'Mini Url',
            'max_path' => 'Max Path',
            'mini_path' => 'Mini Path',
            'create_time' => 'Create Time',
            'max_size' => 'Max Size',
            'mini_size' => 'Mini Size',
        ];
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getImageInfo($id){
        $imgPath = $this->find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return $imgPath?$imgPath:[];
    }
}
