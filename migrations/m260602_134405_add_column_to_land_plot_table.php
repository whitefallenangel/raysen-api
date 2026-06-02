<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%land_plot}}`.
 */
class m260602_134405_add_column_to_land_plot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
    {
        $this->addColumn('{{%land_plot}}', 'land_plot_type', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип учаска'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260602_134405_add_column_to_land_plot_table cannot be reverted.\n";

        $this->dropColumn('{{%land_plot}}', 'land_plot_type');

		return false;
    }
}
