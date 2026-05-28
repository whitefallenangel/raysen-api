<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%offer%}}, {{%contact%}}, {{%building%}} and {{%building_object}}`.
 */
class m260415_143740_add_columns_to_offer_and_building_obj_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%offer}}', 'offer_department', $this->integer()->unsigned()->notNull()->comment('Отдел, к которому относится здание'));
        $this->addColumn('{{%offer}}', 'offer_department_id', $this->integer()->notNull()->comment('ID рабочее'));
        $this->addColumn('{{%offer}}', 'offer_indexation_type', $this->integer()->notNull()->defaultValue(0)->comment('Тип индексации'));
        $this->addColumn('{{%offer}}', 'offer_indexation', $this->integer()->notNull()->defaultValue(0)->comment('Индексация'));
        $this->addColumn('{{%offer}}', 'offer_free_shuttle_service', $this->boolean()->notNull()->defaultValue(0)->comment('Бесплатный трансфер'));
        $this->addColumn('{{%offer}}', 'offer_contract_term_type', $this->integer()->defaultValue(0)->notNull()->comment('Тип срока договора'));
        $this->addColumn('{{%offer}}', 'offer_contract_term', $this->integer()->defaultValue(0)->notNull()->comment('Срок договора'));
        $this->addColumn('{{%offer}}', 'offer_exit_from_сontract_type', $this->integer()->defaultValue(0)->notNull()->comment('Тип выхода из договора'));
        $this->addColumn('{{%offer}}', 'offer_exit_from_сontract', $this->integer()->defaultValue(0)->notNull()->comment('Выход из договора'));
        $this->addColumn('{{%offer}}', 'offer_real_estate_sales', $this->boolean()->notNull()->defaultValue(0)->comment('Продажа недвижимости'));
        $this->addColumn('{{%offer}}', 'offer_sale_lease_rights', $this->boolean()->notNull()->defaultValue(0)->comment('Продажа права аренды'));
        $this->addColumn('{{%offer}}', 'offer_combined', $this->boolean()->notNull()->defaultValue(0)->comment('Комбинированная'));
        $this->addColumn('{{%offer}}', 'offer_long_term_contracts', $this->integer()->defaultValue(0)->notNull()->comment('Долгосрочные договора'));
        $this->addColumn('{{%offer}}', 'offer_credit', $this->integer()->defaultValue(0)->notNull()->comment('Кредиты'));
        $this->addColumn('{{%offer}}', 'offer_courts', $this->boolean()->notNull()->defaultValue(0)->comment('Суды'));
        $this->addColumn('{{%offer}}', 'offer_servitudes', $this->boolean()->notNull()->defaultValue(0)->comment('Сервитуты'));
        $this->addColumn('{{%offer}}', 'offer_type_of_right', $this->integer()->defaultValue(0)->notNull()->comment('Вид права'));
        $this->addColumn('{{%offer}}', 'offer_term', $this->integer()->defaultValue(0)->notNull()->comment('Срок'));
        $this->addColumn('{{%offer}}', 'offer_management_company_name', $this->string()->comment('Название управляющей компании'));
        $this->addColumn('{{%offer}}', 'offer_management_company_price', $this->integer()->defaultValue(0)->notNull()->comment('Стоимость услуг управляющей компании'));
        $this->addColumn('{{%offer}}', 'offer_one_time_load', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Единовременная нагрузка'));
        $this->addColumn('{{%offer}}', 'offer_storage_type', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Тип хранения'));
        $this->addColumn('{{%offer}}', 'offer_room_integration', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Тип встройки помещения'));
        $this->addColumn('{{%offer}}', 'offer_street_retail', $this->boolean()->notNull()->defaultValue(0)->comment('Стрит-ретейл'));
        $this->addColumn('{{%offer}}', 'offer_calc_area_by', $this->boolean()->notNull()->defaultValue(0)->comment('Расчет площади по BOMA/БТИ'));
        $this->addColumn('{{%offer}}', 'offer_input_groups_cnt', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Кол-во входных групп'));
        $this->addColumn('{{%offer}}', 'offer_wet_points_cnt', $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Кол-во мокрых точек'));
        $this->addColumn('{{%offer}}', 'offer_storage_on_racks', $this->string(64)->comment('Цена хранения на стелажах'));
        $this->addColumn('{{%offer}}', 'offer_all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));

        $this->addColumn('{{%building_object}}', 'b_obj_department', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Отдел, к которому относится здание'));
        $this->addColumn('{{%building_object}}', 'b_obj_department_id', $this->integer()->notNull()->comment('ID рабочее'));
        $this->addColumn('{{%building_object}}', 'b_obj_stained_glass_windows', $this->boolean()->notNull()->defaultValue(0)->comment('Витражное остекление'));
        $this->addColumn('{{%building_object}}', 'b_obj_all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));

        $this->addColumn('{{%building}}', 'building_temperature_conditions', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Температурный режим'));
        $this->addColumn('{{%building}}', 'building_wall_material', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Материал стен'));
        $this->addColumn('{{%building}}', 'building_type_overlap', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип перекрытий'));
        $this->addColumn('{{%building}}', 'building_video_link', $this->string()->comment('Ссылка на видео здания'));
        $this->addColumn('{{%building}}', 'building_all_moved_data', $this->text()->comment('Все остальные данные, что не нашлись в базе'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260415_143740_add_columns_to_offer_and_building_obj_table cannot be reverted.\n";

        $this->dropColumn('{{%offer}}', 'offer_department');
        $this->dropColumn('{{%offer}}', 'offer_department_id');
        $this->dropColumn('{{%offer}}', 'offer_indexation_type');
        $this->dropColumn('{{%offer}}', 'offer_indexation');
        $this->dropColumn('{{%offer}}', 'offer_contract_term_type');
        $this->dropColumn('{{%offer}}', 'offer_contract_term');
        $this->dropColumn('{{%offer}}', 'offer_exit_from_сontract_type');
        $this->dropColumn('{{%offer}}', 'offer_exit_from_сontract');
        $this->dropColumn('{{%offer}}', 'offer_real_estate_sales');
        $this->dropColumn('{{%offer}}', 'offer_sale_lease_rights');
        $this->dropColumn('{{%offer}}', 'offer_combined');
        $this->dropColumn('{{%offer}}', 'offer_long_term_contracts');
        $this->dropColumn('{{%offer}}', 'offer_credit');
        $this->dropColumn('{{%offer}}', 'offer_courts');
        $this->dropColumn('{{%offer}}', 'offer_servitudes');
        $this->dropColumn('{{%offer}}', 'offer_type_of_right');
        $this->dropColumn('{{%offer}}', 'offer_term');
        $this->dropColumn('{{%offer}}', 'offer_management_company_name');
        $this->dropColumn('{{%offer}}', 'offer_management_company_price');
        $this->dropColumn('{{%offer}}', 'offer_one_time_load');
        $this->dropColumn('{{%offer}}', 'offer_all_moved_data');

        $this->dropColumn('{{%building_object}}', 'b_obj_department');
        $this->dropColumn('{{%building_object}}', 'b_obj_department');
        $this->dropColumn('{{%building_object}}', 'b_obj_stained_glass_windows');
        $this->dropColumn('{{%building_object}}', 'b_obj_all_moved_data');

        $this->dropColumn('{{%building}}', 'building_temperature_conditions');
        $this->dropColumn('{{%building}}', 'building_wall_material');
        $this->dropColumn('{{%building}}', 'building_type_overlap');
        $this->dropColumn('{{%building}}', 'building_video_link');
        $this->dropColumn('{{%building}}', 'building_all_moved_data');
    }
}
