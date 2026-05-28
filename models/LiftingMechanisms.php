<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lifting_mechanisms".
 *
 * @property int $id
 * @property int $mechanism_object_id ID объекта
 * @property int $mechanism_object_type Тип объекта механизма
 * @property string|null $mechanism_object_attributes Атрибуты механизма
 */
class LiftingMechanisms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lifting_mechanisms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mechanism_object_id', 'mechanism_object_type'], 'integer'],
            [['mechanism_object_attributes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mechanism_object_id' => 'Mechanism Object ID',
            'mechanism_object_type' => 'Mechanism Object Type',
            'mechanism_object_attributes' => 'Mechanism Object Attributes',
        ];
    }
}
