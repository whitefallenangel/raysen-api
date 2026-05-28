<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "land_plot".
 *
 * @property int $id
 * @property int $land_plot_location ID правила локации
 * @property string $land_plot_address Адрес участка
 * @property int $land_plot_line Линия учаска
 * @property string|null $land_plot_kadastr Кадастровый №
 * @property int $land_plot_square S - участка
 * @property int $land_plot_category Категория ЗУ
 * @property int $land_plot_permition Вид разрешенного использования
 * @property string|null $land_plot_permition_text Текст вида разрешенного использования
 * @property string $land_plot_restrictions Существующие ограничения/Обременения
 * @property float|null $land_plot_longitude Долгота
 * @property float|null $land_plot_latitude Широта
 * @property int $land_plot_author_id Кто добавил участок
 * @property int $land_plot_from_mkad Расстояние от МКАД
 * @property string $land_plot_in_main_sections Показывать в основных разделах
 * @property int $land_plot_in_complex Входит ли в какой-то комплекс
 * @property int $land_plot_complex_id ID комплекса в который входит
 * @property int $land_plot_in_plot Связанно ли участком
 * @property string|null $land_plot_related_plot Связанные участки
 * @property string $land_plot_attributes Атрибуты участка
 * @property string|null $land_plot_infrastructure Инфраструктура участка
 * @property string $land_plot_owner Владелец(ы) здания
 * @property int $land_plot_owner_type Тип владения участка
 * @property int $land_plot_owner_contact Контакт владения участка
 * @property int $land_plot_management_company Управляющая компания
 * @property int $land_plot_management_company_type Тип собственности управляющей компанией
 * @property string|null $land_plot_title Название строения/Заголовок
 * @property string|null $land_plot_tags Теги участка
 * @property string|null $land_plot_advantage Преимущества локации
 * @property string|null $land_plot_photo Фото локации
 * @property int|null $land_plot_last_update Дата изменения объекта
 * @property int|null $land_plot_test_only Является ли участок тестовым
 * @property string|null $land_plot_description Описание участка
 * @property string|null $land_plot_layouts Планировка участка
 */
class LandPlot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'land_plot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['land_plot_location', 'land_plot_address', 'land_plot_square', 'land_plot_category', 'land_plot_permition', 'land_plot_restrictions', 'land_plot_in_main_sections', 'land_plot_in_complex', 'land_plot_complex_id', 'land_plot_attributes'], 'required'],
            [['land_plot_location', 'land_plot_line', 'land_plot_square', 'land_plot_category', 'land_plot_permition', 'land_plot_author_id', 'land_plot_from_mkad', 'land_plot_in_complex', 'land_plot_complex_id', 'land_plot_in_plot', 'land_plot_owner_type', 'land_plot_owner_contact', 'land_plot_management_company', 'land_plot_management_company_type', 'land_plot_last_update', 'land_plot_test_only'], 'integer'],
            [['land_plot_longitude', 'land_plot_latitude'], 'number'],
            [['land_plot_photo', 'land_plot_description', 'land_plot_layouts'], 'string'],
            [['land_plot_address', 'land_plot_title', 'land_plot_tags'], 'string', 'max' => 255],
            [['land_plot_kadastr'], 'string', 'max' => 64],
            [['land_plot_permition_text'], 'string', 'max' => 1204],
            [['land_plot_restrictions'], 'string', 'max' => 1024],
            [['land_plot_in_main_sections', 'land_plot_related_plot', 'land_plot_attributes', 'land_plot_infrastructure', 'land_plot_owner', 'land_plot_advantage'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'land_plot_location' => 'Land Plot Location',
            'land_plot_address' => 'Land Plot Address',
            'land_plot_line' => 'Land Plot Line',
            'land_plot_kadastr' => 'Land Plot Kadastr',
            'land_plot_square' => 'Land Plot Square',
            'land_plot_category' => 'Land Plot Category',
            'land_plot_permition' => 'Land Plot Permition',
            'land_plot_permition_text' => 'Land Plot Permition Text',
            'land_plot_restrictions' => 'Land Plot Restrictions',
            'land_plot_longitude' => 'Land Plot Longitude',
            'land_plot_latitude' => 'Land Plot Latitude',
            'land_plot_author_id' => 'Land Plot Author ID',
            'land_plot_from_mkad' => 'Land Plot From Mkad',
            'land_plot_in_main_sections' => 'Land Plot In Main Sections',
            'land_plot_in_complex' => 'Land Plot In Complex',
            'land_plot_complex_id' => 'Land Plot Complex ID',
            'land_plot_in_plot' => 'Land Plot In Plot',
            'land_plot_related_plot' => 'Land Plot Related Plot',
            'land_plot_attributes' => 'Land Plot Attributes',
            'land_plot_infrastructure' => 'Land Plot Infrastructure',
            'land_plot_owner' => 'Land Plot Owner',
            'land_plot_owner_type' => 'Land Plot Owner Type',
            'land_plot_owner_contact' => 'Land Plot Owner Contact',
            'land_plot_management_company' => 'Land Plot Management Company',
            'land_plot_management_company_type' => 'Land Plot Management Company Type',
            'land_plot_title' => 'Land Plot Title',
            'land_plot_tags' => 'Land Plot Tags',
            'land_plot_advantage' => 'Land Plot Advantage',
            'land_plot_photo' => 'Land Plot Photo',
            'land_plot_last_update' => 'Land Plot Last Update',
            'land_plot_test_only' => 'Land Plot Test Only',
            'land_plot_description' => 'Land Plot Description',
            'land_plot_layouts' => 'Land Plot Layouts',
        ];
    }

	public function getLocation()
    {
		return $this->hasOne(Location::class, ['id' => 'land_plot_location']);
    }
	
	public function getOffer()
    {
		return $this->hasMany(Offer::class, ['offer_object_id' => 'id']);
    }

	public function getLandPlotObject()
    {
		return $this->hasMany(LandPlotObject::class, ['b_obj_land_plot_id' => 'id']);
    }

	public function getCommunications()
    {
		return $this->hasOne(Communications::class, ['communications_object_id' => 'id']);
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
