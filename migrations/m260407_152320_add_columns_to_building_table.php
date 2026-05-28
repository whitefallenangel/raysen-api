<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%building}}` and `{{%land_plot}}`.
 */
class m260407_152320_add_columns_to_building_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%building}}', 'building_department', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Отдел, к которому относится здание'));
        $this->addColumn('{{%land_plot}}', 'land_plot_department', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Отдел, к которому относится участок'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260407_152320_add_columns_to_building_table cannot be reverted.\n";

        $this->dropColumn('{{%building}}', 'building_department');
        $this->dropColumn('{{%land_plot}}', 'land_plot_department');

		return false;
    }
}
