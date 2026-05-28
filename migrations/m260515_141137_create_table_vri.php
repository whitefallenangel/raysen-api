<?php

use app\kernel\console\Migration;

class m260515_141137_create_table_vri extends Migration
{
    public function safeUp()
    {
        $this->createTable('vri', [
            'id' => $this->primaryKey(),
            'vri_title' => $this->string()->comment('Название ВРИ'), // Название ВРИ
            'vri_description' => $this->text()->comment('Описание ВРИ'), // Описание ВРИ
            'vri_code' => $this->string(16)->comment('Код ВРИ'), // Код ВРИ
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('vri');

        echo "m260515_141137_create_table_vri cannot be reverted.\n";

        return false;
    }
}