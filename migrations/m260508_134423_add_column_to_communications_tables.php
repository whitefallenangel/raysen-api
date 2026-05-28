<?php

use app\kernel\console\Migration;

class m260508_134423_add_column_to_communications_tables extends Migration
{
	public function safeUp()
	{
        $this->addColumn('{{%communications}}', 'communications_water_supply_type', $this->integer()->notNull()->defaultValue(0)->comment('Тип водоснабжения'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260508_134423_add_column_to_communications_tables cannot be reverted.\n";

        $this->dropColumn('{{%communications}}', 'communications_water_supply_type');
	}
}
