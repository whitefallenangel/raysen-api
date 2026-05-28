<?php

use app\kernel\console\Migration;

class m260216_081347_create_table_equipment_furniture extends Migration
{
    public function safeUp()
    {
        $this->createTable('equipment_furniture', [
            'id' => $this->primaryKey(),
            'equipment_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID объекта к которому привязываются оборудование и мебели
            'equipment_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта парковки и въезда'), // Тип объекта оборудования и мебели
            'equipment_object_attributes' => $this->text()->comment('Атрибуты оборудования и мебели'), // Атрибуты оборудования и мебели
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('equipment_furniture');

        echo "m260216_081347_create_table_equipment_furniture cannot be reverted.\n";

        return false;
    }
}