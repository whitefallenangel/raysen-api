<?php

use app\kernel\console\Migration;

class m260430_115055_add_columns_to_user_tables extends Migration
{
	public function safeUp()
	{
        $this->addColumn('{{%user}}', 'user_department', $this->integer()->notNull()->defaultValue(0)->comment('Отдел, к которому относится контакт'));
        $this->addColumn('{{%user}}', 'user_department_id', $this->integer()->notNull()->defaultValue(0)->comment('ID рабочее'));
        $this->addColumn('{{%user_profile}}', 'temp_name', $this->string()->comment('Перенос ФИО со старой базы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260430_115055_add_columns_to_user_tables cannot be reverted.\n";

        $this->dropColumn('{{%user}}', 'user_department');
        $this->dropColumn('{{%user}}', 'user_department_id');
        $this->dropColumn('{{%user_profile}}', 'temp_name');
    }
}