<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%offer}}`.
 */
class m260602_134838_add_column_to_offer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
    {
        $this->addColumn('{{%offer}}', 'offer_input_groups_cnt', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип учаска'));
        $this->addColumn('{{%offer}}', 'offer_all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260602_134405_add_column_to_land_plot_table cannot be reverted.\n";

        $this->dropColumn('{{%offer}}', 'offer_input_groups_cnt');
        $this->dropColumn('{{%offer}}', 'offer_all_moved_data');

		return false;
    }
}
