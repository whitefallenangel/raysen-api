<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%building}}` and `{{%land_plot}}`.
 */
class m260408_141204_add_columns_to_building_table2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%building}}', 'building_department_id', $this->integer()->notNull()->comment('ID рабочее'));
        $this->addColumn('{{%land_plot}}', 'land_plot_department_id', $this->integer()->notNull()->comment('ID рабочее'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260408_141204_add_columns_to_building_table2 cannot be reverted.\n";

        $this->dropColumn('{{%building}}', 'building_department_id');
        $this->dropColumn('{{%land_plot}}', 'land_plot_department_id');

		return false;
    }
}
