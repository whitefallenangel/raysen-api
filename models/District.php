<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property int $id
 * @property string $districts_title Название района
 * @property int $district_type Тип района
 * @property int $districts_region ID региона
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['districts_title'], 'required'],
            [['district_type', 'districts_region'], 'integer'],
            [['districts_title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'districts_title' => 'Districts Title',
            'district_type' => 'District Type',
            'districts_region' => 'Districts Region',
        ];
    }
}
