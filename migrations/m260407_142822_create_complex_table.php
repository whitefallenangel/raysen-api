<?php

use app\kernel\console\Migration;

class m260407_142822_create_complex_table extends Migration
{
    public function safeUp()
    {
        $tableName = '{{%complex}}';
        $this->table($tableName, [
            'id' => $this->primaryKey(),
            'complex_title' => $this->string()->comment('Название комплекса'),
            'complex_location' => $this->integer()->unsigned()->notNull()->comment('ID правила локации'),
        ]);
    }

    public function safeDown()
    {
        $tableName = '{{%complex}}';
        $this->dropTable($tableName);
    }
}
