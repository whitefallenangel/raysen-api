<?php

namespace app\models;

use Yii;
use app\models\Region;
use app\models\District;
use app\models\Metro;


/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string|null $location_metro Метро
 * @property string|null $location_msk_region Округ Москвы
 * @property string|null $location_msk_highway Шоссе Москвы
 * @property int $location_locality Населенный пункт
 * @property string|null $location_near_locality Населенный пункт, что рядом или крупный город
 * @property int $location_district Район
 * @property int $location_region Регион
 * @property int $location_district_center Районный центр
 * @property int $location_type Тип локации
 * @property int $location_district_type Тип района
 * @property string|null $location_direction_mo Направление МО
 * @property string|null $location_highway_mo Шоссе МО
 * @property string|null $location_direction_distr_center Направление от РЦ
 * @property string|null $location_highway Шоссе
 * @property int $location_cyan_region Регион ЦИАН
 * @property int $location_avito_region Регион Авито
 * @property int $location_in_mo Показывать в МО
 * @property int $location_inside_mkad Показывать внутри МКАД
 * @property int $location_outside_mkad Показывать снаружи МКАД
 * @property int $location_inside_ckad Показывать внутри ЦКАД
 * @property int $location_outside_ckad Показывать снаружи ЦКАД
 * @property int $location_adjacent_to_mo Прилегает к МО
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_locality', 'location_district', 'location_region', 'location_district_center', 'location_type', 'location_district_type', 'location_cyan_region', 'location_avito_region', 'location_in_mo', 'location_inside_mkad', 'location_outside_mkad', 'location_inside_ckad', 'location_outside_ckad', 'location_adjacent_to_mo'], 'integer'],
            [['location_district', 'location_region'], 'required'],
            [['location_metro', 'location_msk_region', 'location_msk_highway', 'location_near_locality', 'location_direction_mo', 'location_highway_mo', 'location_direction_distr_center', 'location_highway'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_metro' => 'Location Metro',
            'location_msk_region' => 'Location Msk Region',
            'location_msk_highway' => 'Location Msk Highway',
            'location_locality' => 'Location Locality',
            'location_near_locality' => 'Location Near Locality',
            'location_district' => 'Location District',
            'location_region' => 'Location Region',
            'location_district_center' => 'Location District Center',
            'location_type' => 'Location Type',
            'location_district_type' => 'Location District Type',
            'location_direction_mo' => 'Location Direction Mo',
            'location_highway_mo' => 'Location Highway Mo',
            'location_direction_distr_center' => 'Location Direction Distr Center',
            'location_highway' => 'Location Highway',
            'location_cyan_region' => 'Location Cyan Region',
            'location_avito_region' => 'Location Avito Region',
            'location_in_mo' => 'Location In Mo',
            'location_inside_mkad' => 'Location Inside Mkad',
            'location_outside_mkad' => 'Location Outside Mkad',
            'location_inside_ckad' => 'Location Inside Ckad',
            'location_outside_ckad' => 'Location Outside Ckad',
            'location_adjacent_to_mo' => 'Location Adjacent To Mo',
        ];
    }
	
	/**
	 * @return regiosn data
	 */
	public function getRegion()
	{
		return $this->hasOne(Region::class, ['id' => 'location_region']);
	}

	/**
	 * @return district data
	 */
	public function getDistrict()
	{
		return $this->hasOne(District::class, ['id' => 'location_district']);
	}

	/**
	 * @return metro data
	 */
	public function getMetro()
	{
		return $this->hasOne(Metro::class, ['id' => 'location_metro']);
	}
}
