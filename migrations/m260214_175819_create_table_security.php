<?php

use app\kernel\console\Migration;

class m260214_175819_create_table_security extends Migration
{
    public function safeUp()
    {
        $this->createTable('security', [
            'id' => $this->primaryKey(),
            'security_object_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('ID объекта'), // ID Объекта к которому привязываются безопасность
            'security_object_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип объекта безопасности'), // Тип объекта безопасности
            'security_object_attributes' => $this->text()->comment('Атрибуты безопасности'), // Атрибуты безопасности
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('security');

        echo "m260214_175819_create_table_security cannot be reverted.\n";

        return false;
    }
}