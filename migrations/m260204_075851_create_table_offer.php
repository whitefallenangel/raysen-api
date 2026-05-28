<?php

use app\kernel\console\Migration;

class m260204_075851_create_table_offer extends Migration
{
    public function safeUp()
    {
        $this->createTable('offer', [
            'id' => $this->primaryKey(),
            'offer_object_id' => $this->integer()->unsigned()->notNull()->comment('ID объекта'),
            'offer_original_id' => $this->integer()->unsigned()->notNull()->comment('ID оригинала сделки'),
            'offer_type' => $this->integer()->unsigned()->notNull()->comment('Тип сделки'),
            'offer_company_id' => $this->integer()->unsigned()->notNull()->comment('ID компании'),
            'offer_contact_id' => $this->integer()->unsigned()->notNull()->comment('Выбор контакта'),
            'offer_hide' => $this->boolean()->notNull()->defaultValue(0)->comment('Скрыть сделку'),
            'offer_consultant_id' => $this->integer()->unsigned()->notNull()->comment('Консультант'),
            'offer_personal_exam' => $this->boolean()->notNull()->defaultValue(0)->comment('Личный осмотр'),
            'offer_signing_contract' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Подписание контракта'),

            'offer_owner_percent' => $this->integer()->unsigned()->notNull()->comment('% от владельца'),
            'offer_owner_percent_type' => $this->integer()->unsigned()->notNull()->comment('% от владельца (р или %)'),
            'offer_owner_percent_type2' => $this->integer()->unsigned()->notNull()->comment('Тип зачислений % от владельца'),
            'offer_client_percent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% от клиента'),
            'offer_client_percent_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% от клиента (р или %)'),
            'offer_client_percent_type2' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип зачислений % от клиента'),
            'offer_agent_percent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% агенту'),
            'offer_agent_percent_type' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% от агенту (р или %)'),
            'offer_agent_percent_type2' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Тип зачислений % агенту'),

            'offer_tax_system' => $this->integer()->unsigned()->notNull()->comment('Система налогобложения'),
            'offer_sale_legal_entity' => $this->boolean()->notNull()->defaultValue(0)->comment('Продажа юр. лица'), // Если Предложение ПРОДАЖА
            'offer_opex' => $this->integer()->notNull()->defaultValue(0)->comment('OPEX (Эксплуатационные)'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_opex_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип OPEX (Эксплуатационные)'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_ku_communal' => $this->integer()->notNull()->defaultValue(0)->comment('КУ (Коммунальные)'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_ku_communal_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип КУ (Коммунальные)'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_vacation' => $this->boolean()->notNull()->defaultValue(0)->comment('Каникулы'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_deposit' => $this->boolean()->notNull()->defaultValue(0)->comment('Обеспечительный платеж'), // Если Предложение АРЕНДА/СУБАРЕНДА

            'offer_built_to_rent' => $this->boolean()->notNull()->defaultValue(0)->comment('Built-to-Rent'), // Если Предложение АРЕНДА/СУБАРЕНДА
            'offer_built_to_suit' => $this->boolean()->notNull()->defaultValue(0)->comment('Built-to-Suit'), // Если Предложение ПРОДАЖА
            'offer_construction_months' => $this->integer()->notNull()->defaultValue(0)->comment('Сроки строительства'), // Если Предложение АРЕНДА/СУБАРЕНДА и ПРОДАЖА
            'offer_project_availability' => $this->boolean()->notNull()->defaultValue(0)->comment('Наличие проекта'), // Если Предложение АРЕНДА/СУБАРЕНДА и ПРОДАЖА

            // Предложение ПРОДАЖА начало
            'offer_with_rental_business' => $this->boolean()->notNull()->defaultValue(0)->comment('С арендным бизнесом'),

            'offer_annual_rental_flow' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Годовой арендный поток (ГАП)'),
            'offer_monthly_rental_flow' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Месячный арендный поток (МАП)'),
            'offer_net_operating_income' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Чистый операционный доход (ЧОД/NOI)'),
            'offer_net_profit' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Чистая прибыль'),
            'offer_capitalization_rate' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Ставка капитализации % (Cap Rate)'),
            'offer_payback_period_chod' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Срок окупаемости по ЧОД'),
            'offer_payback_period_map' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Срок окупаемости по МАП'),
            'offer_long_contracts_percent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% долгих контрактов'),
            'offer_object_occupancy_percent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('% заполненности объекта'),

            'offer_property_tax' => $this->integer()->notNull()->defaultValue(0)->comment('Налог на имущество'),
            'offer_land_tax' => $this->integer()->notNull()->defaultValue(0)->comment('Налог на земельный участок'),
            'offer_land_lease' => $this->integer()->notNull()->defaultValue(0)->comment('Аренда земельного участка'),
            'offer_uncompensated_expenses' => $this->integer()->notNull()->defaultValue(0)->comment('Некомпенсируемые расходы'),

            'offer_investment_project' => $this->boolean()->notNull()->defaultValue(0)->comment('Инвестиционный проект'),
            'offer_project_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип Проекта'),
            'offer_need_change_tou' => $this->boolean()->notNull()->defaultValue(0)->comment('Неообходимость смены ВРИ'),
            'offer_project_stage' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Стадия проекта'),
            'offer_gns_area' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Площадь в ГНС'),
            'offer_unit_price_based_on_area' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Удельная цена за участок от площади участка'),
            'offer_unit_price_based_on_area_gns' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Удельная цена за участок от площади в ГНС '),
            'offer_buildings_presence' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Наличие строений'),
            'offer_current_state' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Существующее положение (м2)'),
            'offer_teps_density' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('Плотность (ТЭПы)'),
            'offer_teps_height' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Высотность (ТЭПы)'),
            'offer_teps_height_unit' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Единица высотности (ТЭПы)'),
            'offer_teps_buildings_percent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Процент застройки (ТЭПы)'),
            'offer_crt' => $this->boolean()->notNull()->defaultValue(0)->comment('КРТ'),
            'offer_szz_zone' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Зона СЗЗ'),
            'offer_oopt_zone' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Зона ООПТ'),
            'offer_okn_zone' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Зона ОКН'),
            'offer_has_exits_to_uds' => $this->boolean()->notNull()->defaultValue(0)->comment('Наличие съездов к УДС'),
            // Предложение ПРОДАЖА конец

            // Предложение ОТВЕТХРАНЕНИЕ начало
            'offer_loading_operations' => $this->boolean()->notNull()->defaultValue(0)->comment('Погрузочные работы'),
            'offer_cross_docking' => $this->boolean()->notNull()->defaultValue(0)->comment('Кросс-докинг'),
            'offer_boxes_recalc' => $this->boolean()->notNull()->defaultValue(0)->comment('Пересчет по коробкам'),
            'offer_product_culling' => $this->boolean()->notNull()->defaultValue(0)->comment('Выбраковка товара'),
            'offer_product_repack' => $this->boolean()->notNull()->defaultValue(0)->comment('Переупаковка товара'),
            'offer_pallet_formation' => $this->boolean()->notNull()->defaultValue(0)->comment('Формирование паллет'),
            'offer_stretch_tape_wrapp' => $this->boolean()->notNull()->defaultValue(0)->comment('Обмотка стретч пленка'),
            'offer_batch_accounting' => $this->boolean()->notNull()->defaultValue(0)->comment('Партионный учет'),
            'offer_serial_numbers_account' => $this->boolean()->notNull()->defaultValue(0)->comment('Учет серийных номеров'),
            'offer_in_fifi_lifi_fefo_lefo' => $this->boolean()->notNull()->defaultValue(0)->comment('Учет в разрезах FIFI,LIFI,FEFO,LEFO'),
            'offer_product_choice' => $this->boolean()->notNull()->defaultValue(0)->comment('Подбор товаров'),
            'offer_provision_pallets' => $this->boolean()->notNull()->defaultValue(0)->comment('Предоставление паллет'),
            'offer_complete_sets' => $this->boolean()->notNull()->defaultValue(0)->comment('Комплектация наборов'),
            'offer_labeling' => $this->boolean()->notNull()->defaultValue(0)->comment('Стикеровка и маркировка'),
            'offer_product_packaging' => $this->boolean()->notNull()->defaultValue(0)->comment('Упаковка товара'),
            'offer_co_packing' => $this->boolean()->notNull()->defaultValue(0)->comment('Ко-пакинг'),
            'offer_print_accomp_docks' => $this->boolean()->notNull()->defaultValue(0)->comment('Печать сопроводительных доков'),
            'offer_provision_reports' => $this->boolean()->notNull()->defaultValue(0)->comment('Предоставление отчетов'),
            'offer_inventory' => $this->boolean()->notNull()->defaultValue(0)->comment('Инвентаризация'),
            'offer_disposal_waste' => $this->boolean()->notNull()->defaultValue(0)->comment('Утилизация брака'),
            'offer_management_inventory' => $this->boolean()->notNull()->defaultValue(0)->comment('Управление запасами'),
            'offer_acceptance_refunds' => $this->boolean()->notNull()->defaultValue(0)->comment('Приемка возвратов'),
            'offer_pack_repair' => $this->boolean()->notNull()->defaultValue(0)->comment('Ремонт упаковки'),
            'offer_archive_storage' => $this->boolean()->notNull()->defaultValue(0)->comment('Архивное хранение'),
            'offer_zpl_services' => $this->boolean()->notNull()->defaultValue(0)->comment('Логистика третьей стороны'),
            'offer_delivery_by_city' => $this->boolean()->notNull()->defaultValue(0)->comment('Доставка товара по городу'),
            'offer_delivery_by_region' => $this->boolean()->notNull()->defaultValue(0)->comment('Доставка товара по региону'),
            'offer_delivery_by_country' => $this->boolean()->notNull()->defaultValue(0)->comment('Доставка товара по России'),
            // Предложение ОТВЕТХРАНЕНИЕ конец

            // Торговое предложение (создание) ОБЩЕЕ ДЛЯ ВСЕХ начало
            'offer_room_purpose' => $this->string(128)->notNull()->comment('Назначение помещение'),
            'offer_plot_kadastr' => $this->string(64)->comment('Кадастровый №'),
            'offer_access_date' => $this->dateTime()->comment('Дата готовности к сделке'),
            'offer_access_to_object' => $this->string(64)->comment('Доступ к ТП'),

            'offer_electricity_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Мощность эл-ва (кВт)'),
            'offer_water_supply_power' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Мощность ХВС (м3/час)'),
            'offer_gas_for_prod' => $this->boolean()->notNull()->defaultValue(0)->comment('Газ для производства'),
            'offer_steam_for_prod' => $this->boolean()->notNull()->defaultValue(0)->comment('Пар для производства'),

            'offer_full_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - суммарная от'),
            'offer_full_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - суммарная до'),
            'offer_warehouse_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - складская от'),
            'offer_warehouse_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - складская до'),
            'offer_warehouse_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип складской цены'),
            'offer_office_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - офисная от'),
            'offer_office_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - офисная до'),
            'offer_office_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип офисной цены'),
            'offer_retail_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - торговая от'),
            'offer_retail_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - торговая до'),
            'offer_retail_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип торговой цены'),
            'offer_technical_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - техническая от'),
            'offer_technical_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - техническая до'),
            'offer_technical_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип технической цены'),
            'offer_public_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - общего пользования от'),
            'offer_public_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - общего пользования до'),
            'offer_public_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип цены общего пользования'),
            'offer_land_plot_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('E - участка от'),
            'offer_land_plot_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('E - участка до'),
            'offer_land_plot_price_type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип цены участка'),

			'offer_hand_loading_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. ручная погрузка от'),
			'offer_hand_loading_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. ручная погрузка до'),
			'offer_hand_unloading_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. ручная разгрузка от'),
			'offer_hand_unloading_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. ручная разгрузка до'),
			'offer_mech_loading_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. мех. погрузка от'),
			'offer_mech_loading_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. мех. погрузка до'),
			'offer_mech_unloading_price_min' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. мех. разгрузка от'),
			'offer_mech_unloading_price_max' => $this->integer()->notNull()->defaultValue(0)->comment('Отет. хран. мех. разгрузка до'),

            'offer_entrance_attributes' => $this->string(128)->comment('Атрибут: Парковка и въезд'),
            'offer_infrastructure_attributes' => $this->string(128)->comment('Атрибут: Инфраструктура'),
            'offer_infrastructure_railway' => $this->string(128)->comment('Атрибут: Ж/Д'),

            'offer_offer_description' => $this->text()->comment('Описание торгового предложения'),
            'offer_documents_layouts' => $this->string()->comment('Документы, планировки'),
            'offer_tenant_id' => $this->integer()->notNull()->defaultValue(0)->comment('ID Арендатора'),
            'offer_rental_period' => $this->integer()->notNull()->defaultValue(0)->comment('Срок аренды в месяцах'),
            'offer_status' => $this->integer()->defaultValue(0)->notNull()->comment('Статус'),
            'offer_status_reason' => $this->string(128)->notNull()->comment('Описание статуса'),
            'offer_last_update' => $this->integer()->unsigned()->defaultValue(0)->comment('Дата изменения объекта'),
            // Торговое предложение (создание) ОБЩЕЕ ДЛЯ ВСЕХ конец
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('offer');

        echo "m260204_075851_create_table_offer cannot be reverted.\n";

        return false;
    }
}