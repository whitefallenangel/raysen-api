<?php

use app\kernel\console\Migration;

class m260610_094721_create_table_floor extends Migration
{
    public function safeUp()
    {
        $this->createTable('floor', [
            'id' => $this->primaryKey(),
            'floor_building_id' => $this->integer()->unsigned()->notNull()->comment('ID строения'),
            'floor_full_square' => $this->integer()->unsigned()->notNull()->comment('Общая площадь'),
            'floor_storage_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Складская площадь'),
            'floor_office_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Офисная площадь'),
            'floor_retail_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Торговая площадь'),
            'floor_technical_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Техническая площадь'),
            'floor_public_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Площадь общего пользования'),

            'floor_number' => $this->integer()->unsigned()->notNull()->comment('Этаж'),
			'floor_ceiling_height_min' => $this->integer()->unsigned()->notNull()->comment('Минимальная высота потолков'),
			'floor_ceiling_height_max' => $this->integer()->unsigned()->notNull()->comment('Максимальная высота потолков'),
            'floor_floor_types' => $this->string()->comment('Типы пола'),
            'floor_floor_load_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная нагрузка на пол'),
            'floor_floor_load_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная нагрузка на пол'),
            'floor_columns_grid' => $this->string()->comment('Сетка колонн'),
            'floor_gate' => $this->string()->comment('Создать ворота'), // Набор ворот с их типами в JSON
            'floor_cross_docking' => $this->boolean()->notNull()->defaultValue(0)->comment('Кросс-докинг'),
            'floor_charging_room' => $this->boolean()->notNull()->defaultValue(0)->comment('Зарядная комната'),

            'floor_heating' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Теплоснабжение'),
            'floor_heating_type' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Тип теплоснабжения'),
            'floor_water_supply' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Водоснабжение'),
            'floor_water_supply_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип водоснабжения'),
            'floor_sewerage' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Водоотведение'),
            'floor_ventilation' => $this->string()->comment('Вентиляция'),
            'floor_air_conditioning' => $this->string()->comment('Кондиционирование'),

            'floor_firefighting' => $this->string()->comment('Пожаротушение'),
            'floor_smoke_removal' => $this->boolean()->notNull()->defaultValue(0)->comment('Дымоудаление'),
            'floor_video_control' => $this->boolean()->notNull()->defaultValue(0)->comment('Видеонаблюдение'),
            'floor_access_control' => $this->boolean()->notNull()->defaultValue(0)->comment('Контроль доступа'),
            'floor_security_alarm' => $this->boolean()->notNull()->defaultValue(0)->comment('Охранная сигнализация'),
            'floor_fire_alarm' => $this->boolean()->notNull()->defaultValue(0)->comment('Пожарная сигнализация'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('floor');

        echo "m260610_094721_create_table_floor cannot be reverted.\n";

        return false;
    }
}