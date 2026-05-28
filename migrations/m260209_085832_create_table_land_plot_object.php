<?php

use app\kernel\console\Migration;

class m260209_085832_create_table_land_plot_object extends Migration
{
    public function safeUp()
    {
        $this->createTable('land_plot_object', [
            'id' => $this->primaryKey(),
            'b_obj_land_plot_id' => $this->integer()->unsigned()->notNull()->comment('ID строения'),
            'lp_object_square_min' => $this->integer()->unsigned()->notNull()->comment('S - участка минимальная'),
            'lp_object_square_max' => $this->integer()->unsigned()->notNull()->comment('S - участка максимальная'),
            'lp_objec_kadastr' => $this->string(64)->comment('Кадастровый №'),
            'lp_objec_permition' => $this->string()->notNull()->comment('Вид разрешенного использования'),

            'lp_object_length' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Длина участка'),
            'lp_object_width' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Ширина участка'),
            'lp_object_coverage' => $this->integer()->unsigned()->notNull()->comment('Покрытие участка'),
            'lp_object_relief' => $this->integer()->unsigned()->notNull()->comment('Рельеф участка'),
            'lp_object_buildings_presence' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Площадь строений на участке'),
            'lp_object_encumbrances' => $this->string()->comment('Обременения'),

            //'lp_object_communications' => $this->string(128)->comment('Параметры коммуникаций'),
            //'lp_object_security' => $this->string(128)->comment('Параметры безопасности'),
            'lp_obj_photo' => $this->text()->comment('Фото объекта'),
            //'lp_object_equipment' => $this->string(128)->comment('Параметры оборудования участка'),
            //'lp_object_lift' => $this->string(128)->comment('Параметры кран/подъемник/лифт'),

       ]);

    }

    public function safeDown()
    {
        $this->dropTable('land_plot');

        echo "m260209_085832_create_table_land_plot_object cannot be reverted.\n";

        return false;
    }
}