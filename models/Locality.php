<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "locality".
 *
 * @property int $id
 * @property string $locality_title Название населенного пункта
 * @property int $locality_type Тип населенного пункта
 * @property int $locality_district ID района
 * @property int $locality_region ID региона
 * @property int $locality_is_center Является ли центром региона
 */
class Locality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['locality_title'], 'required'],
            [['locality_type', 'locality_district', 'locality_region', 'locality_is_center'], 'integer'],
            [['locality_title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'locality_title' => 'Locality Title',
            'locality_type' => 'Locality Type',
            'locality_district' => 'Locality District',
            'locality_region' => 'Locality Region',
            'locality_is_center' => 'Locality Is Center',
        ];
    }
}
