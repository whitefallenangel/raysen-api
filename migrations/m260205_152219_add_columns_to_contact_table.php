<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `contact`.
 */
class m260205_152219_add_columns_to_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contact}}', 'relationships', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип взаимоотношений'));
        $this->addColumn('{{%contact}}', 'availability', $this->string()->comment('Доступность'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%contact}}', 'relationships');
        $this->dropColumn('{{%contact}}', 'availability');
    }
}
