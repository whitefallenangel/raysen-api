<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "railway".
 *
 * @property int $id
 * @property int $railway_object_id ID объекта
 * @property int $railway_object_type Тип объекта ж/д
 * @property string|null $railway_object_attributes Атрибуты ж/д
 */
class Railway extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'railway';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['railway_object_id', 'railway_object_type'], 'integer'],
            [['railway_object_attributes'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'railway_object_id' => 'Railway Object ID',
            'railway_object_type' => 'Railway Object Type',
            'railway_object_attributes' => 'Railway Object Attributes',
        ];
    }
}
