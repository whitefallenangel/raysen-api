<?php

use app\kernel\console\Migration;

class m260217_093333_create_table_districts extends Migration
{
    public function safeUp()
    {
        $this->createTable('district', [
            'id' => $this->primaryKey(),
            'districts_title' => $this->string(128)->notNull()->comment('Название района'),
            'district_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип района'),
            'districts_region' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID региона'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('district');

        echo "m260217_093333_create_table_districts cannot be reverted.\n";

        return false;
    }
}