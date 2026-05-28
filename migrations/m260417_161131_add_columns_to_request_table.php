<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%request}}`.
 */
class m260417_161131_add_columns_to_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%request}}', 'all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));
        $this->addColumn('{{%request}}', 'request_department', $this->integer()->notNull()->defaultValue(0)->comment('Отдел, к которому относится контакт'));
        $this->addColumn('{{%request}}', 'request_department_id', $this->integer()->notNull()->defaultValue(0)->comment('ID рабочее'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260413_135038_add_columns_to_communications_and_contact_table cannot be reverted.\n";

        $this->dropColumn('{{%request}}', 'all_moved_data');
        $this->dropColumn('{{%request}}', 'request_department');
        $this->dropColumn('{{%request}}', 'request_department_id');
    }
}
