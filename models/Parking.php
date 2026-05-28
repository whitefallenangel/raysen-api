<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parking_and_entrance".
 *
 * @property int $id
 * @property int $parking_object_id ID объекта
 * @property int $parking_object_type Тип объекта парковки и въезда
 * @property string|null $parking_object_attributes Атрибуты парковки и въезда
 */
class Parking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parking_object_id', 'parking_object_type'], 'integer'],
            [['parking_object_attributes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parking_object_id' => 'Parking Object ID',
            'parking_object_type' => 'Parking Object Type',
            'parking_object_attributes' => 'Parking Object Attributes',
        ];
    }
}
