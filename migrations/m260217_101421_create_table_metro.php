<?php

use app\kernel\console\Migration;

class m260217_101421_create_table_metro extends Migration
{
    public function safeUp()
    {
        $this->createTable('metro', [
            'id' => $this->primaryKey(),
            'metro_title' => $this->string(128)->notNull()->comment('Название метро'),
            'metro_locality_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID населенного пункта'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('metro');

        echo "m260217_101421_create_table_metro cannot be reverted.\n";

        return false;
    }
}