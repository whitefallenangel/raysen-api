<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "security".
 *
 * @property int $id
 * @property int $security_object_id ID объекта
 * @property int $security_object_type Тип объекта безопасности
 * @property string|null $security_object_attributes Атрибуты безопасности
 */
class Security extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'security';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['security_object_id', 'security_object_type'], 'integer'],
            [['security_object_attributes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'security_object_id' => 'Security Object ID',
            'security_object_type' => 'Security Object Type',
            'security_object_attributes' => 'Security Object Attributes',
        ];
    }
}
