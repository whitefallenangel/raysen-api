<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "communications".
 *
 * @property int $id
 * @property int $communications_object_id ID Объекта
 * @property int $communications_object_type Тип объекта
 * @property int $communications_electricity Электричество
 * @property int $communications_electricity_power Мощность эл-ва (кВт)
 * @property int $communications_water_supply Водоснабжение
 * @property int $communications_water_supply_power Мощность ХВС (м3/с)
 * @property int $communications_sewerage Водоотведение
 * @property int $communications_sewerage_power Мощность водоотведения
 * @property int $communications_stormwater_drainage Ливневое водоотведение
 * @property int $communications_heating Теплоснабжение
 * @property int $communications_heating_power Мощность теплоснабжения, гкалл
 * @property int $communications_gas Газоснабжение
 * @property int $communications_gas_power Мощность газоснабжения (МЧРГ) м3/час
 * @property int $communications_steam Пар
 * @property int $communications_steam_power Мощность давления пара
 * @property string|null $communications_lighting Освещение
 * @property int $communications_ventilation Вентиляция
 * @property int $communications_air_conditioning Кондиционирование
 * @property int $communications_bathroom Санузел
 * @property int $communications_internet Интернет
 */
class Communications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'communications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['communications_object_id', 'communications_object_type', 'communications_electricity', 'communications_electricity_power', 'communications_water_supply', 'communications_water_supply_power', 'communications_sewerage', 'communications_sewerage_power', 'communications_stormwater_drainage', 'communications_heating', 'communications_heating_power', 'communications_gas', 'communications_gas_power', 'communications_steam', 'communications_steam_power', 'communications_ventilation', 'communications_air_conditioning', 'communications_bathroom', 'communications_internet'], 'integer'],
            [['communications_lighting'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'communications_object_id' => 'Communications Object ID',
            'communications_object_type' => 'Communications Object Type',
            'communications_electricity' => 'Communications Electricity',
            'communications_electricity_power' => 'Communications Electricity Power',
            'communications_water_supply' => 'Communications Water Supply',
            'communications_water_supply_power' => 'Communications Water Supply Power',
            'communications_sewerage' => 'Communications Sewerage',
            'communications_sewerage_power' => 'Communications Sewerage Power',
            'communications_stormwater_drainage' => 'Communications Stormwater Drainage',
            'communications_heating' => 'Communications Heating',
            'communications_heating_power' => 'Communications Heating Power',
            'communications_gas' => 'Communications Gas',
            'communications_gas_power' => 'Communications Gas Power',
            'communications_steam' => 'Communications Steam',
            'communications_steam_power' => 'Communications Steam Power',
            'communications_lighting' => 'Communications Lighting',
            'communications_ventilation' => 'Communications Ventilation',
            'communications_air_conditioning' => 'Communications Air Conditioning',
            'communications_bathroom' => 'Communications Bathroom',
            'communications_internet' => 'Communications Internet',
        ];
    }
}
