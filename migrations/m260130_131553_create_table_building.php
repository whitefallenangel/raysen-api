<?php

use app\kernel\console\Migration;

class m260130_131553_create_table_building extends Migration
{
    public function safeUp()
    {
        $this->createTable('building', [
            'id' => $this->primaryKey(),
            'building_type' => $this->integer()->unsigned()->notNull()->comment('Тип здания'),
            'building_location' => $this->integer()->unsigned()->notNull()->comment('ID правила локации'),
            'building_address' => $this->string()->notNull()->comment('Адрес здания'), 
            'building_line' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Линия домов'),
            'building_ready_status' => $this->integer()->unsigned()->notNull()->comment('Статус готовности'),
            'building_kadastr' => $this->string(128)->comment('Кадастровый №'),
            'building_square' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('S - здания'),
            'building_floors_counts' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Этажность здания'),
            'building_class' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Класс объекта'),
            'building_allowed_electro_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Выделенная эл. мощность на здание'),
            'building_external_decor' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Внешняя отделка'),
            'building_year_construction' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Год постройки'),
            'building_year_repairs' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Год кап. ремонта'),
            'building_condition' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Состояние здания'),
            'building_restrictions' => $this->string(1024)->notNull()->comment('Существующие ограничения/Обременения'),
            'building_longitude' => $this->double(15,12)->comment('Долгота'),
            'building_latitude' => $this->double(15,12)->comment('Широта'),
            'building_author_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Кто добавил здание'),
            'building_from_mkad' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Расстояние от МКАД'),

            'building_in_main_sections' => $this->string(128)->notNull()->comment('Показывать в основных разделах'),
            'building_in_complex' =>  $this->integer()->unsigned()->notNull()->comment('Входит ли в какой-то комплекс'),
            'building_complex_id' => $this->integer()->unsigned()->notNull()->comment('ID комплекса в который входит'),
            'building_in_plot' =>  $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Связанно ли участком'),
            'building_related_plot' => $this->string(128)->comment('ID связанного участка'),
            'building_attributes' => $this->string(128)->notNull()->comment('Атрибуты здания'),
            'building_infrastructure' => $this->string(128)->notNull()->defaultValue('')->comment('Инфраструктура здания'),
            'building_owner' => $this->string(128)->notNull()->defaultValue("")->comment('Владелец(ы) здания'),
            'building_owner_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип владения зданием'),
            'building_owner_contact' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Контакт владения зданием'),
            'building_management_company' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Управляющая компания'),
            'building_management_company_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип собственности управляющей компанией'),

            'building_title' => $this->string()->comment('Название строения/Заголовок'),
            'building_tags' => $this->string()->comment('Теги здания'),
            'building_advantage' => $this->string(128)->comment('Преимущества локации'),
            'building_photo' => $this->text()->comment('Фото здания'),
            'building_property_documents' => $this->text()->comment('Документы на собственность здания'),
            'building_last_update' => $this->integer()->unsigned()->defaultValue(0)->comment('Дата изменения объекта'),
            'building_test_only' => $this->integer()->unsigned()->defaultValue(0)->comment('Является ли здание тестовым'),
            'building_description' => $this->text()->comment('Описание здания'),
            'building_layouts' => $this->text()->comment('Планировка здания'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('building');

        echo "m260130_131553_create_table_building cannot be reverted.\n";

        return false;
    }
}