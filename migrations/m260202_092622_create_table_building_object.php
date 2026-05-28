<?php

use app\kernel\console\Migration;

class m260202_092622_create_table_building_object extends Migration
{
    public function safeUp()
    {
        $this->createTable('building_object', [
            'id' => $this->primaryKey(),
            'b_obj_building_id' => $this->integer()->unsigned()->notNull()->comment('ID строения'),
            'b_obj_offer_id' => $this->integer()->unsigned()->notNull()->comment('ID сделки'),
            'b_obj_full_square_min' => $this->integer()->unsigned()->notNull()->comment('Минимальная общая площадь'),
            'b_obj_full_square_max' => $this->integer()->unsigned()->notNull()->comment('Максимальная общая площадь'),
            'b_obj_storage_square_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная складская площадь'),
            'b_obj_storage_square_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная складская площадь'),
            'b_obj_office_square_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная офисная площадь'),
            'b_obj_office_square_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная офисная площадь'),
            'b_obj_retail_square_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная торговая площадь'),
            'b_obj_retail_square_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная торговая площадь'),
            'b_obj_technical_square_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная техническая площадь'),
            'b_obj_technical_square_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная техническая площадь'),
            'b_obj_public_square_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная площадь общего пользования'),
            'b_obj_public_square_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная площадь общего пользования'),
            'b_obj_kadastr' => $this->string(64)->comment('Кадастровый №'),

            'b_obj_floor' => $this->integer()->notNull()->comment('Этаж'),
            'b_obj_special_floor' => $this->integer()->unsigned()->notNull()->comment('Специальный этаж'),
            'b_obj_ceiling_height_min' => $this->integer()->unsigned()->notNull()->comment('Минимальная высота потолков'),
            'b_obj_ceiling_height_max' => $this->integer()->unsigned()->notNull()->comment('Максимальная высота потолков'),
            'b_obj_floor_type' => $this->string(64)->comment('Типы пола'),
            'b_obj_floor_load_min' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Минимальная нагрузка на пол'),
            'b_obj_floor_load_max' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Максимальная нагрузка на пол'),
            'b_obj_columns_grid' => $this->string(64)->comment('Сетка колонн'),
            'b_obj_gate' => $this->string(128)->comment('Создать ворота'), // Набор ворот с их типами в JSON
            'b_obj_cross_docking' => $this->boolean()->notNull()->defaultValue(0)->comment('Кросс-докинг'),
            'b_obj_layout_features' => $this->string(128)->notNull()->defaultValue('')->comment('Особенности планировки'),
            'b_obj_finishing_renovation' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Отделка/Ремонт'),
            'b_obj_charging_room' => $this->boolean()->notNull()->defaultValue(0)->comment('Зарядная комната'),
            'b_obj_storage_methods' => $this->string(64)->comment('Способы хранения'),
            'b_obj_non_capital_walls' => $this->boolean()->notNull()->defaultValue(0)->comment('Наличие некапитальных перегородок'),

            //'b_obj_communications' => $this->string(128)->comment('Параметры коммуникаций'),
            //'b_obj_security' => $this->string(128)->comment('Параметры безопасности'),
            'b_obj_photo' => $this->text()->comment('Фото объекта'),
            //'b_obj_equipment' => $this->string(128)->comment('Параметры оборудования участка'),
            //'b_obj_lift' => $this->string(128)->comment('Параметры кран/подъемник/лифт'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('object');

        echo "m260202_092622_create_table_building_object cannot be reverted.\n";

        return false;
    }
}