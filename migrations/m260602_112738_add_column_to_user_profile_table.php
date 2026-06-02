<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_profile}}`.
 */
class m260602_112738_add_column_to_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
    {
        $this->addColumn('{{%user_profile}}', 'temp_name', $this->string()->comment('Временное имя'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260602_112738_add_column_to_user_profile_table cannot be reverted.\n";

        $this->dropColumn('{{%user_profile}}', 'temp_name');

		return false;
    }
}
