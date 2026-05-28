<?php

use app\kernel\console\Migration;

class m260216_082541_create_table_railway extends Migration
{
    public function safeUp()
    {
        $this->createTable('railway', [
            'id' => $this->primaryKey(),
            'railway_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID объекта к которому привязываются ж/д
            'railway_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта ж/д'), // Тип объекта ж/д
            'railway_object_attributes' => $this->string(512)->comment('Атрибуты ж/д'), // Атрибуты ж/д
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('railway');

        echo "m260216_082541_create_table_railway cannot be reverted.\n";

        return false;
    }
}