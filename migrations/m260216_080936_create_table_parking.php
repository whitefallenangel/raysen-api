<?php

use app\kernel\console\Migration;

class m260216_080936_create_table_parking extends Migration
{
    public function safeUp()
    {
        $this->createTable('parking', [
            'id' => $this->primaryKey(),
            'parking_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID объекта к которому привязываются парковка
            'parking_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта парковки'), // Тип объекта парковки
            'parking_object_attributes' => $this->text()->comment('Атрибуты парковки'), // Атрибуты парковки
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('parking');

        echo "m260216_080936_create_table_parking cannot be reverted.\n";

        return false;
    }
}