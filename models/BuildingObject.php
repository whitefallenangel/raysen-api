<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "building_object".
 *
 * @property int $id
 * @property int $b_obj_building_id ID строения
 * @property int $b_obj_offer_id ID сделки
 * @property int $b_obj_storage_square_min Минимальная складская площадь
 * @property int $b_obj_storage_square_max Максимальная складская площадь
 * @property int $b_obj_office_square_min Минимальная офисная площадь
 * @property int $b_obj_office_square_max Максимальная офисная площадь
 * @property int $b_obj_retail_square_min Минимальная торговая площадь
 * @property int $b_obj_retail_square_max Максимальная торговая площадь
 * @property int $b_obj_technical_square_min Минимальная техническая площадь
 * @property int $b_obj_technical_square_max Максимальная техническая площадь
 * @property int $b_obj_public_square_min Минимальная площадь общего пользования
 * @property int $b_obj_public_square_max Максимальная площадь общего пользования
 * @property string|null $b_obj_kadastr Кадастровый №
 * @property int $b_obj_floor Этаж
 * @property int $b_obj_special_floor Специальный этаж
 * @property int $b_obj_ceiling_height_min Минимальная высота потолков
 * @property int $b_obj_ceiling_height_max Максимальная высота потолков
 * @property string|null $b_obj_floor_type Типы пола
 * @property int $b_obj_floor_load_min Минимальная нагрузка на пол
 * @property int $b_obj_floor_load_max Максимальная нагрузка на пол
 * @property string|null $b_obj_columns_grid Сетка колонн
 * @property string|null $b_obj_gate Создать ворота
 * @property int $b_obj_cross_docking Кросс-докинг
 * @property string $b_obj_layout_features Особенности планировки
 * @property int $b_obj_finishing_renovation Отделка/Ремонт
 * @property int $b_obj_charging_room Зарядная комната
 * @property string|null $b_obj_storage_methods Способы хранения
 * @property int $b_obj_non_capital_walls Наличие некапитальных перегородок
 * @property string|null $b_obj_photo Фото объекта
 */
class BuildingObject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'building_object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['b_obj_building_id', 'b_obj_offer_id', 'b_obj_storage_square_min', 'b_obj_storage_square_max', 'b_obj_office_square_min', 'b_obj_office_square_max', 'b_obj_retail_square_min', 'b_obj_retail_square_max', 'b_obj_technical_square_min', 'b_obj_technical_square_max', 'b_obj_public_square_min', 'b_obj_public_square_max', 'b_obj_floor', 'b_obj_special_floor', 'b_obj_ceiling_height_min', 'b_obj_ceiling_height_max'], 'required'],
            [['b_obj_building_id', 'b_obj_offer_id', 'b_obj_storage_square_min', 'b_obj_storage_square_max', 'b_obj_office_square_min', 'b_obj_office_square_max', 'b_obj_retail_square_min', 'b_obj_retail_square_max', 'b_obj_technical_square_min', 'b_obj_technical_square_max', 'b_obj_public_square_min', 'b_obj_public_square_max', 'b_obj_floor', 'b_obj_special_floor', 'b_obj_ceiling_height_min', 'b_obj_ceiling_height_max', 'b_obj_floor_load_min', 'b_obj_floor_load_max', 'b_obj_cross_docking', 'b_obj_finishing_renovation', 'b_obj_charging_room', 'b_obj_non_capital_walls'], 'integer'],
            [['b_obj_photo'], 'string'],
            [['b_obj_kadastr', 'b_obj_floor_type', 'b_obj_columns_grid', 'b_obj_storage_methods'], 'string', 'max' => 64],
            [['b_obj_gate', 'b_obj_layout_features'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b_obj_building_id' => 'B Obj Building ID',
            'b_obj_offer_id' => 'B Obj Offer ID',
            'b_obj_storage_square_min' => 'B Obj Storage Square Min',
            'b_obj_storage_square_max' => 'B Obj Storage Square Max',
            'b_obj_office_square_min' => 'B Obj Office Square Min',
            'b_obj_office_square_max' => 'B Obj Office Square Max',
            'b_obj_retail_square_min' => 'B Obj Retail Square Min',
            'b_obj_retail_square_max' => 'B Obj Retail Square Max',
            'b_obj_technical_square_min' => 'B Obj Technical Square Min',
            'b_obj_technical_square_max' => 'B Obj Technical Square Max',
            'b_obj_public_square_min' => 'B Obj Public Square Min',
            'b_obj_public_square_max' => 'B Obj Public Square Max',
            'b_obj_kadastr' => 'B Obj Kadastr',
            'b_obj_floor' => 'B Obj Floor',
            'b_obj_special_floor' => 'B Obj Special Floor',
            'b_obj_ceiling_height_min' => 'B Obj Ceiling Height Min',
            'b_obj_ceiling_height_max' => 'B Obj Ceiling Height Max',
            'b_obj_floor_type' => 'B Obj Floor Type',
            'b_obj_floor_load_min' => 'B Obj Floor Load Min',
            'b_obj_floor_load_max' => 'B Obj Floor Load Max',
            'b_obj_columns_grid' => 'B Obj Columns Grid',
            'b_obj_gate' => 'B Obj Gate',
            'b_obj_cross_docking' => 'B Obj Cross Docking',
            'b_obj_layout_features' => 'B Obj Layout Features',
            'b_obj_finishing_renovation' => 'B Obj Finishing Renovation',
            'b_obj_charging_room' => 'B Obj Charging Room',
            'b_obj_storage_methods' => 'B Obj Storage Methods',
            'b_obj_non_capital_walls' => 'B Obj Non Capital Walls',
            'b_obj_photo' => 'B Obj Photo',
        ];
    }
}
