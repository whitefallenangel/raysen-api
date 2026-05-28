<?php

use app\kernel\console\Migration;

class m260217_094303_create_table_locality extends Migration
{
    public function safeUp()
    {
        $this->createTable('locality', [
            'id' => $this->primaryKey(),
            'locality_title' => $this->string(128)->notNull()->comment('Название населенного пункта'),
            'locality_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип населенного пункта'),
            'locality_district' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID района'),
            'locality_region' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID региона'),
            'locality_is_center' => $this->boolean()->notNull()->defaultValue(0)->comment('Является ли центром региона'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('locality');

        echo "m260217_094303_create_table_locality cannot be reverted.\n";

        return false;
    }
}