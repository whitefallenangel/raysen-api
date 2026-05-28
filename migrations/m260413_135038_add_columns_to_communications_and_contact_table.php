<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%communications}} and {{%contact}}`.
 */
class m260413_135038_add_columns_to_communications_and_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%communications}}', 'communications_one_time_load', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Единовременная нагрузка'));
        $this->addColumn('{{%communications}}', 'communications_electr_reliability_cat', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Категория надежности эл-ва'));
        $this->addColumn('{{%communications}}', 'communications_reserve_power_supply', $this->boolean()->notNull()->defaultValue(0)->comment('Резервный источник питания'));
        $this->addColumn('{{%communications}}', 'communications_reserve_power_supply_type', $this->integer()->notNull()->defaultValue(0)->comment('Тип резервного источника питания'));

        $this->addColumn('{{%contact}}', 'all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));
        $this->addColumn('{{%contact}}', 'contact_department', $this->integer()->notNull()->defaultValue(0)->comment('Отдел, к которому относится контакт'));
        $this->addColumn('{{%contact}}', 'contact_department_id', $this->integer()->notNull()->defaultValue(0)->comment('ID рабочее'));
        $this->addColumn('{{%contact}}', 'contact_availability', $this->integer()->notNull()->defaultValue(0)->comment('Доступность'));
        $this->addColumn('{{%contact}}', 'temp_name', $this->string()->comment('Перенос ФИО со старой базы'));

        $this->addColumn('{{%company}}', 'company_availability', $this->integer()->notNull()->defaultValue(0)->comment('Доступность'));
        $this->addColumn('{{%company}}', 'all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));
        $this->addColumn('{{%company}}', 'company_department', $this->integer()->notNull()->defaultValue(0)->comment('Отдел, к которому относится компания'));
        $this->addColumn('{{%company}}', 'company_department_id', $this->integer()->notNull()->defaultValue(0)->comment('ID рабочее'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260413_135038_add_columns_to_communications_and_contact_table cannot be reverted.\n";

        $this->dropColumn('{{%communications}}', 'communications_one_time_load');
        $this->dropColumn('{{%communications}}', 'communications_electr_reliability_cat');
        $this->dropColumn('{{%communications}}', 'communications_reserve_power_supply');
        $this->dropColumn('{{%communications}}', 'communications_reserve_power_supply_type');
        $this->dropColumn('{{%contact}}', 'all_moved_data');
        $this->dropColumn('{{%contact}}', 'contact_department');
        $this->dropColumn('{{%contact}}', 'contact_department_id');
        $this->dropColumn('{{%contact}}', 'contact_availability');
        $this->dropColumn('{{%company}}', 'company_availability');
        $this->dropColumn('{{%company}}', 'all_moved_data');
    }
}
