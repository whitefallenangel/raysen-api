<?php

use app\kernel\console\Migration;

class m260217_101421_create_table_railway_station extends Migration
{
    public function safeUp()
    {
        $this->createTable('railway_station', [
            'id' => $this->primaryKey(),
            'railway_station_title' => $this->string()->notNull()->comment('Название ж/д станции'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('railway_station');

        echo "m260217_101421_create_table_railway_station cannot be reverted.\n";

        return false;
    }
}