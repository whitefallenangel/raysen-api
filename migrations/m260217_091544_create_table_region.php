<?php

use app\kernel\console\Migration;

class m260217_091544_create_table_region extends Migration
{
    public function safeUp()
    {
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'region_title' => $this->string()->notNull()->comment('Название региона'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('region');

        echo "m260217_091544_create_table_region cannot be reverted.\n";

        return false;
    }
}