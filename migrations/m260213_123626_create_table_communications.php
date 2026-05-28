<?php

use app\kernel\console\Migration;

class m260213_123626_create_table_communications extends Migration
{
    public function safeUp()
    {
        $this->createTable('communications', [
            'id' => $this->primaryKey(),
            'communications_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID Объекта'), // ID Объекта к которому привязываются коммуникации
            'communications_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта'), // тип объекта
            'communications_electricity' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Электричество'),
            'communications_electricity_power' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Мощность эл-ва (кВт)'),
            'communications_water_supply' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Водоснабжение'),
            'communications_water_supply_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Мощность ХВС (м3/с)'),
            'communications_sewerage' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Водоотведение'),
            'communications_sewerage_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Мощность водоотведения'),
            'communications_stormwater_drainage' => $this->boolean()->unsigned()->notNull()->defaultValue(0)->comment('Ливневое водоотведение'),
            'communications_heating' => $this->string(64)->comment('Теплоснабжение'),
            'communications_heating_power' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Мощность теплоснабжения, гкалл'),
            'communications_gas' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Газоснабжение'),
            'communications_gas_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Мощность газоснабжения (МЧРГ) м3/час'),
            'communications_steam' => $this->boolean()->notNull()->defaultValue(0)->comment('Пар'),
            'communications_steam_power' => $this->integer()->notNull()->defaultValue(0)->comment('Мощность давления пара'),
            'communications_lighting' => $this->string(64)->comment('Освещение'),
            'communications_ventilation' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Вентиляция'),
            'communications_air_conditioning' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Кондиционирование'),
            'communications_bathroom' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Санузел'),
            'communications_internet' => $this->string(64)->comment('Интернет'),
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('communications');

        echo "m260213_123626_create_table_communications cannot be reverted.\n";

        return false;
    }
}