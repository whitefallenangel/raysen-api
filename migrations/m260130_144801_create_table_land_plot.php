<?php

use app\kernel\console\Migration;

class m260130_144801_create_table_land_plot extends Migration
{
    public function safeUp()
    {
        $this->createTable('land_plot', [
            'id' => $this->primaryKey(),
            'land_plot_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип'),
            'land_plot_location' => $this->integer()->unsigned()->notNull()->comment('ID правила локации'),
            'land_plot_address' => $this->string()->notNull()->comment('Адрес участка'),
            'land_plot_line' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Линия учаска'),
            'land_plot_kadastr' => $this->string(64)->comment('Кадастровый №'),
            'land_plot_square' => $this->integer()->unsigned()->notNull()->comment('S - участка'),
            'land_plot_category' => $this->integer()->unsigned()->notNull()->comment('Категория ЗУ'),
            'land_plot_permition' => $this->string()->notNull()->comment('Вид разрешенного использования'),
            'land_plot_permition_text' => $this->string(1204)->comment('Текст вида разрешенного использования'),
            'land_plot_restrictions' => $this->string(1024)->notNull()->comment('Существующие ограничения/Обременения'),
            'land_plot_longitude' => $this->double(15,12)->comment('Долгота'),
            'land_plot_latitude' => $this->double(15,12)->comment('Широта'),
            'land_plot_author_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Кто добавил участок'),
            'land_plot_from_mkad' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Расстояние от МКАД'),

            'land_plot_in_main_sections' => $this->string(128)->notNull()->comment('Показывать в основных разделах'),
            'land_plot_in_complex' =>  $this->integer()->unsigned()->notNull()->comment('Входит ли в какой-то комплекс'),
            'land_plot_complex_id' => $this->integer()->unsigned()->notNull()->comment('ID комплекса в который входит'),
            'land_plot_in_plot' =>  $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Связанно ли участком'),
            'land_plot_related_plot' =>  $this->string(128)->comment('Связанные участки'),
            'land_plot_attributes' => $this->string(128)->notNull()->comment('Атрибуты участка'),
            'land_plot_infrastructure' => $this->string(128)->comment('Инфраструктура участка'),
            'land_plot_owner' => $this->string(128)->notNull()->defaultValue("")->comment('Владелец(ы) здания'),
            'land_plot_owner_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип владения участка'),
            'land_plot_owner_contact' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Контакт владения участка'),
            'land_plot_management_company' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Управляющая компания'),
            'land_plot_management_company_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип собственности управляющей компанией'),

            'land_plot_title' => $this->string()->comment('Название строения/Заголовок'),
            'land_plot_tags' => $this->string()->comment('Теги участка'),
            'land_plot_advantage' => $this->string(128)->comment('Преимущества локации'),
            'land_plot_photo' => $this->text()->comment('Фото локации'),
            'land_plot_last_update' => $this->integer()->unsigned()->defaultValue(0)->comment('Дата изменения объекта'),
            'land_plot_test_only' => $this->integer()->unsigned()->defaultValue(0)->comment('Является ли участок тестовым'),
            'land_plot_description' => $this->text()->comment('Описание участка'),
            'land_plot_layouts' => $this->text()->comment('Планировка участка'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('land_plot');

        echo "m260130_144801_create_table_land_plot cannot be reverted.\n";

        return false;
    }
}