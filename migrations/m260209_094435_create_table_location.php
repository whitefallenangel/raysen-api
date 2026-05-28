<?php

use app\kernel\console\Migration;

class m260209_094435_create_table_location extends Migration
{

    public function safeUp()
    {
        $this->createTable('location', [
            'id' => $this->primaryKey(),
            'location_metro' => $this->string(128)->comment('Метро'),
            'location_msk_region' => $this->string(128)->comment('Округ Москвы'),
            'location_msk_highway' => $this->string(128)->comment('Шоссе Москвы'),

            'location_locality' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Населенный пункт'),
            'location_near_locality' => $this->string(128)->comment('Населенный пункт, что рядом или крупный город'),
            'location_district' => $this->integer()->unsigned()->notNull()->comment('Район'),
            'location_region' => $this->integer()->unsigned()->notNull()->comment('Регион'),
            'location_district_center' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Районный центр'),
            'location_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип локации'),
            'location_district_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип района'),

            'location_direction_mo' => $this->string(128)->comment('Направление МО'),
            'location_highway_mo' => $this->string(128)->comment('Шоссе МО'),

            'location_direction_distr_center' => $this->string(128)->comment('Направление от РЦ'),
            'location_highway' => $this->string(128)->comment('Шоссе'),

            'location_cyan_region' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Регион ЦИАН'),
            'location_avito_region' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Регион Авито'),
            'location_in_mo' => $this->boolean()->notNull()->defaultValue(0)->comment('Показывать в МО'),
            'location_inside_mkad' => $this->boolean()->notNull()->defaultValue(0)->comment('Показывать внутри МКАД'),
            'location_outside_mkad' => $this->boolean()->notNull()->defaultValue(0)->comment('Показывать снаружи МКАД'),
            'location_inside_ckad' => $this->boolean()->notNull()->defaultValue(0)->comment('Показывать внутри ЦКАД'),
            'location_outside_ckad' => $this->boolean()->notNull()->defaultValue(0)->comment('Показывать снаружи ЦКАД'),
            'location_adjacent_to_mo' => $this->boolean()->notNull()->defaultValue(0)->comment('Прилегает к МО'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('location');

        echo "m260209_094435_create_table_location cannot be reverted.\n";

        return false;
    }
}