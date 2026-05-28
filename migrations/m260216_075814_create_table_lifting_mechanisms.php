<?php

use app\kernel\console\Migration;

class m260216_075814_create_table_lifting_mechanisms extends Migration
{
    public function safeUp()
    {
        $this->createTable('lifting_mechanisms', [
            'id' => $this->primaryKey(),
            'mechanism_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID Объекта к которому привязываются механизм
            'mechanism_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта механизма'), // Тип объекта механизма
            'mechanism_object_attributes' => $this->text()->comment('Атрибуты механизма'), // Атрибуты механизма
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('lifting_mechanisms');

        echo "m260216_075814_create_table_lifting_mechanisms cannot be reverted.\n";

        return false;
    }
}