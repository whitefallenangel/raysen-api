<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "metro".
 *
 * @property int $id
 * @property string $metro_title Название метро
 * @property int $metro_locality_id ID населенного пункта
 */
class Metro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metro_title'], 'required'],
            [['metro_locality_id'], 'integer'],
            [['metro_title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'metro_title' => 'Metro Title',
            'metro_locality_id' => 'Metro Locality ID',
        ];
    }
}
