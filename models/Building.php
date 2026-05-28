<?php

namespace app\models;

use Yii;
use app\models\Location;


/**
 * This is the model class for table "building".
 *
 * @property int $id
 * @property int $building_type Тип здания
 * @property int $building_location ID правила локации
 * @property string $building_address Адрес здания
 * @property int $building_line Линия домов
 * @property int $building_ready_status Статус готовности
 * @property string|null $building_kadastr Кадастровый №
 * @property int $building_square S - здания
 * @property int $building_floors_counts Этажность здания
 * @property int $building_class Класс объекта
 * @property int $building_allowed_electro_power Выделенная эл. мощность на здание
 * @property int $building_external_decor Внешняя отделка
 * @property int $building_year_construction Год постройки
 * @property int $building_year_repairs Год кап. ремонта
 * @property int $building_condition Состояние здания
 * @property string $building_restrictions Существующие ограничения/Обременения
 * @property float|null $building_longitude Долгота
 * @property float|null $building_latitude Широта
 * @property int $building_author_id Кто добавил здание
 * @property int $building_from_mkad Расстояние от МКАД
 * @property string $building_in_main_sections Показывать в основных разделах
 * @property int $building_in_complex Входит ли в какой-то комплекс
 * @property int $building_complex_id ID комплекса в который входит
 * @property int $building_in_plot Связанно ли участком
 * @property string|null $building_related_plot ID связанного участка
 * @property string $building_attributes Атрибуты здания
 * @property string $building_infrastructure Инфраструктура здания
 * @property string $building_owner Владелец(ы) здания
 * @property int $building_owner_type Тип владения зданием
 * @property int $building_owner_contact Контакт владения зданием
 * @property int $building_management_company Управляющая компания
 * @property int $building_management_company_type Тип собственности управляющей компанией
 * @property string|null $building_title Название строения/Заголовок
 * @property string|null $building_tags Теги здания
 * @property string|null $building_advantage Преимущества локации
 * @property string|null $building_photo Фото здания
 * @property string|null $building_property_documents Документы на собственность здания
 * @property int|null $building_last_update Дата изменения объекта
 * @property int|null $building_test_only Является ли здание тестовым
 * @property string|null $building_description Описание здания
 * @property string|null $building_layouts Планировка здания
 */
class Building extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['building_type', 'building_location', 'building_address', 'building_ready_status', 'building_restrictions', 'building_in_main_sections', 'building_in_complex', 'building_complex_id', 'building_attributes'], 'required'],
            [['building_type', 'building_location', 'building_line', 'building_ready_status', 'building_square', 'building_floors_counts', 'building_class', 'building_allowed_electro_power', 'building_external_decor', 'building_year_construction', 'building_year_repairs', 'building_condition', 'building_author_id', 'building_from_mkad', 'building_in_complex', 'building_complex_id', 'building_in_plot', 'building_owner_type', 'building_owner_contact', 'building_management_company', 'building_management_company_type', 'building_last_update', 'building_test_only'], 'integer'],
            [['building_longitude', 'building_latitude'], 'number'],
            [['building_photo', 'building_property_documents', 'building_description', 'building_layouts'], 'string'],
            [['building_address', 'building_title', 'building_tags'], 'string', 'max' => 255],
            [['building_kadastr'], 'string', 'max' => 64],
            [['building_restrictions'], 'string', 'max' => 1024],
            [['building_in_main_sections', 'building_related_plot', 'building_attributes', 'building_infrastructure', 'building_owner', 'building_advantage'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'building_type' => 'Building Type',
            'building_location' => 'Building Location',
            'building_address' => 'Building Address',
            'building_line' => 'Building Line',
            'building_ready_status' => 'Building Ready Status',
            'building_kadastr' => 'Building Kadastr',
            'building_square' => 'Building Square',
            'building_floors_counts' => 'Building Floors Counts',
            'building_class' => 'Building Class',
            'building_allowed_electro_power' => 'Building Allowed Electro Power',
            'building_external_decor' => 'Building External Decor',
            'building_year_construction' => 'Building Year Construction',
            'building_year_repairs' => 'Building Year Repairs',
            'building_condition' => 'Building Condition',
            'building_restrictions' => 'Building Restrictions',
            'building_longitude' => 'Building Longitude',
            'building_latitude' => 'Building Latitude',
            'building_author_id' => 'Building Author ID',
            'building_from_mkad' => 'Building From Mkad',
            'building_in_main_sections' => 'Building In Main Sections',
            'building_in_complex' => 'Building In Complex',
            'building_complex_id' => 'Building Complex ID',
            'building_in_plot' => 'Building In Plot',
            'building_related_plot' => 'Building Related Plot',
            'building_attributes' => 'Building Attributes',
            'building_infrastructure' => 'Building Infrastructure',
            'building_owner' => 'Building Owner',
            'building_owner_type' => 'Building Owner Type',
            'building_owner_contact' => 'Building Owner Contact',
            'building_management_company' => 'Building Management Company',
            'building_management_company_type' => 'Building Management Company Type',
            'building_title' => 'Building Title',
            'building_tags' => 'Building Tags',
            'building_advantage' => 'Building Advantage',
            'building_photo' => 'Building Photo',
            'building_property_documents' => 'Building Property Documents',
            'building_last_update' => 'Building Last Update',
            'building_test_only' => 'Building Test Only',
            'building_description' => 'Building Description',
            'building_layouts' => 'Building Layouts',
        ];
    }

	public function getLocation()
    {
		return $this->hasOne(Location::class, ['id' => 'building_location']);
    }

	public function getOffer()
    {
		return $this->hasMany(Offer::class, ['offer_object_id' => 'id']);
    }

	public function getBuildingObject()
    {
		return $this->hasMany(BuildingObject::class, ['b_obj_building_id' => 'id']);
    }

	public function getCommunications()
    {
		return $this->hasOne(Communications::class, ['communications_object_id' => 'id']);
    }

	public function getParking()
    {
		return $this->hasOne(Parking::class, ['parking_object_id' => 'id']);
    }

	public function getEntrance()
    {
		return $this->hasOne(Entrance::class, ['entrance_object_id' => 'id']);
    }

	public function getLiftingMechanisms()
    {
		return $this->hasMany(LiftingMechanisms::class, ['mechanism_object_id' => 'id']);
    }

	public function getSecurity()
    {
		return $this->hasOne(Security::class, ['security_object_id' => 'id']);
    }

	public function getRailway()
    {
		return $this->hasOne(Railway::class, ['railway_object_id' => 'id']);
    }
}
