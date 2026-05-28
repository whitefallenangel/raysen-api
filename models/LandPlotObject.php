<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "land_plot_object".
 *
 * @property int $id
 * @property int $b_obj_land_plot_id ID строения
 * @property int $lp_object_square_min S - участка минимальная
 * @property int $lp_object_square_max S - участка максимальная
 * @property string|null $land_plot_kadastr Кадастровый №
 * @property int $land_plot_permition Вид разрешенного использования
 * @property int $lp_object_length Длина участка
 * @property int $lp_object_width Ширина участка
 * @property int $lp_object_coverage Покрытие участка
 * @property int $lp_object_relief Рельеф участка
 * @property int $lp_object_buildings_presence Площадь строений на участке
 * @property string|null $lp_object_encumbrances Обременения
 * @property string|null $lp_obj_photo Фото объекта
 */
class LandPlotObject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'land_plot_object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['b_obj_land_plot_id', 'lp_object_square_min', 'lp_object_square_max', 'land_plot_permition', 'lp_object_coverage', 'lp_object_relief'], 'required'],
            [['b_obj_land_plot_id', 'lp_object_square_min', 'lp_object_square_max', 'land_plot_permition', 'lp_object_length', 'lp_object_width', 'lp_object_coverage', 'lp_object_relief', 'lp_object_buildings_presence'], 'integer'],
            [['lp_obj_photo'], 'string'],
            [['land_plot_kadastr'], 'string', 'max' => 64],
            [['lp_object_encumbrances'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b_obj_land_plot_id' => 'B Obj Land Plot ID',
            'lp_object_square_min' => 'Lp Object Square Min',
            'lp_object_square_max' => 'Lp Object Square Max',
            'land_plot_kadastr' => 'Land Plot Kadastr',
            'land_plot_permition' => 'Land Plot Permition',
            'lp_object_length' => 'Lp Object Length',
            'lp_object_width' => 'Lp Object Width',
            'lp_object_coverage' => 'Lp Object Coverage',
            'lp_object_relief' => 'Lp Object Relief',
            'lp_object_buildings_presence' => 'Lp Object Buildings Presence',
            'lp_object_encumbrances' => 'Lp Object Encumbrances',
            'lp_obj_photo' => 'Lp Obj Photo',
        ];
    }
}
