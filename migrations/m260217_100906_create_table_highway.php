<?php

use app\kernel\console\Migration;

class m260217_100906_create_table_highway extends Migration
{
    public function safeUp()
    {
        $this->createTable('highway', [
            'id' => $this->primaryKey(),
            'highway_title' => $this->string()->notNull()->comment('Название шоссе'),
            'highway_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип шоссе'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('highway');

        echo "m260217_100906_create_table_highway cannot be reverted.\n";

        return false;
    }
}