<?php

use app\kernel\console\Migration;

class m260216_081067_create_table_entrance extends Migration
{
    public function safeUp()
    {
        $this->createTable('entrance', [
            'id' => $this->primaryKey(),
            'entrance_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID объекта к которому привязываются въезд
            'entrance_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта парковки и въезда'), // Тип объекта въезда
            'entrance_object_attributes' => $this->text()->comment('Атрибуты въезда'), // Атрибуты въезда
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('entrance');

        echo "m260216_081067_create_table_entrance cannot be reverted.\n";

        return false;
    }
}