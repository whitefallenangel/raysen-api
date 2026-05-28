<?php

use app\kernel\console\Migration;

class m260513_090628_add_columns_offer_and_building_table extends Migration
{
	public function safeUp()
    {
        $this->addColumn('{{%building}}', 'real_estate_type', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип недвижимости'));
        $this->addColumn('{{%offer}}', 'offer_price_min', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('E - предложения от'));
        $this->addColumn('{{%offer}}', 'offer_price_max', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('E - предложения до'));
        $this->addColumn('{{%offer}}', 'offer_gpzu', $this->string()->comment('ГПЗУ'));
        $this->addColumn('{{%offer}}', 'responsible_storage_price_15', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Ответ. хранение. Цена за до 1,5 метра высоты'));
        $this->addColumn('{{%offer}}', 'responsible_storage_price_18', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Ответ. хранение. Цена за до 1,8 метра высоты'));
        $this->addColumn('{{%offer}}', 'responsible_storage_price_22', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Ответ. хранение. Цена за до 2,2 метра высоты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		echo "m260513_090628_add_columns_offer_and_building_table cannot be reverted.\n";

        $this->dropColumn('{{%building}}', 'real_estate_type');
        $this->dropColumn('{{%offer}}', 'offer_price_min');
        $this->dropColumn('{{%offer}}', 'offer_price_max');
        $this->dropColumn('{{%offer}}', 'inv_gpzu');
        $this->dropColumn('{{%offer}}', 'responsible_storage_price_15');
        $this->dropColumn('{{%offer}}', 'responsible_storage_price_18');
        $this->dropColumn('{{%offer}}', 'responsible_storage_price_22');

		return false;
    }
}