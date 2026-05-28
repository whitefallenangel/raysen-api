<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property int $id
 * @property int $offer_company_id ID компании
 * @property int $offer_original_id ID оригинала сделки
 * @property int $offer_contact_id Выбор контакта
 * @property int $offer_hide Скрыть сделку
 * @property int $offer_consultant_id Консультант
 * @property int $offer_personal_exam Личный осмотр
 * @property int $offer_signing_contract Подписание контракта
 * @property int $offer_owner_percent % от владельца
 * @property int $offer_owner_percent_type % от владельца (р или %)
 * @property int $offer_owner_percent_type2 Тип зачислений % от владельца
 * @property int $offer_client_percent % от клиента
 * @property int $offer_client_percent_type % от клиента (р или %)
 * @property int $offer_client_percent_type2 Тип зачислений % от клиента
 * @property int $offer_agent_percent % агенту
 * @property int $offer_agent_percent_type % от агенту (р или %)
 * @property int $offer_agent_percent_type2 Тип зачислений % агенту
 * @property int $offer_tax_system Система налогобложения
 * @property int $offer_sale_legal_entity Продажа юр. лица
 * @property int $offer_opex OPEX (Эксплуатационные)
 * @property int $offer_opex_type Тип OPEX (Эксплуатационные)
 * @property int $offer_ku_communal КУ (Коммунальные)
 * @property int $offer_ku_communal_type Тип КУ (Коммунальные)
 * @property int $offer_vacation Каникулы
 * @property int $offer_deposit Обеспечительный платеж
 * @property int $offer_built_to_rent Built-to-Rent
 * @property int $offer_built_to_suit Built-to-Suit
 * @property int $offer_construction_months Сроки строительства
 * @property int $offer_project_availability Наличие проекта
 * @property int $offer_with_rental_business С арендным бизнесом
 * @property int $offer_annual_rental_flow Годовой арендный поток (ГАП)
 * @property int $offer_monthly_rental_flow Месячный арендный поток (МАП)
 * @property int $offer_net_operating_income Чистый операционный доход (ЧОД/NOI)
 * @property int $offer_net_profit Чистая прибыль
 * @property int $offer_capitalization_rate Ставка капитализации % (Cap Rate)
 * @property int $offer_payback_period_chod Срок окупаемости по ЧОД
 * @property int $offer_payback_period_map Срок окупаемости по МАП
 * @property int $offer_long_contracts_percent % долгих контрактов
 * @property int $offer_object_occupancy_percent % заполненности объекта
 * @property int $offer_property_tax Налог на имущество
 * @property int $offer_land_tax Налог на земельный участок
 * @property int $offer_land_lease Аренда земельного участка
 * @property int $offer_uncompensated_expenses Некомпенсируемые расходы
 * @property int $offer_investment_project Инвестиционный проект
 * @property int $offer_project_type Тип Проекта
 * @property int $offer_need_change_tou Неообходимость смены ВРИ
 * @property int $offer_project_stage Стадия проекта
 * @property int $offer_gns_area Площадь в ГНС
 * @property int $offer_unit_price_based_on_area Удельная цена за участок от площади участка
 * @property int $offer_unit_price_based_on_area_gns Удельная цена за участок от площади в ГНС 
 * @property int $offer_buildings_presence Наличие строений
 * @property int $offer_current_state Существующее положение (м2)
 * @property int $offer_teps_density Плотность (ТЭПы)
 * @property int $offer_teps_height Высотность (ТЭПы)
 * @property int $offer_teps_height_unit Единица высотности (ТЭПы)
 * @property int $offer_teps_buildings_percent Процент застройки (ТЭПы)
 * @property int $offer_crt КРТ
 * @property int $offer_szz_zone Зона СЗЗ
 * @property int $offer_oopt_zone Зона ООПТ
 * @property int $offer_okn_zone Зона ОКН
 * @property int $offer_has_exits_to_uds Наличие съездов к УДС
 * @property int $offer_loading_operations Погрузочные работы
 * @property int $offer_cross_docking Кросс-докинг
 * @property int $offer_boxes_recalc Пересчет по коробкам
 * @property int $offer_product_culling Выбраковка товара
 * @property int $offer_product_repack Переупаковка товара
 * @property int $offer_pallet_formation Формирование паллет
 * @property int $offer_stretch_tape_wrapp Обмотка стретч пленка
 * @property int $offer_batch_accounting Партионный учет
 * @property int $offer_serial_numbers_account Учет серийных номеров
 * @property int $offer_in_fifi_lifi_fefo_lefo Учет в разрезах FIFI,LIFI,FEFO,LEFO
 * @property int $offer_product_choice Подбор товаров
 * @property int $offer_provision_pallets Предоставление паллет
 * @property int $offer_complete_sets Комплектация наборов
 * @property int $offer_labeling Стикеровка и маркировка
 * @property int $offer_product_packaging Упаковка товара
 * @property int $offer_co_packing Ко-пакинг
 * @property int $offer_print_accomp_docks Печать сопроводительных доков
 * @property int $offer_provision_reports Предоставление отчетов
 * @property int $offer_inventory Инвентаризация
 * @property int $offer_disposal_waste Утилизация брака
 * @property int $offer_management_inventory Управление запасами
 * @property int $offer_acceptance_refunds Приемка возвратов
 * @property int $offer_pack_repair Ремонт упаковки
 * @property int $offer_archive_storage Архивное хранение
 * @property int $offer_zpl_services 3PL услуги
 * @property int $offer_delivery_by_city Достава товара по городу
 * @property int $offer_delivery_by_region Достава товара по региону
 * @property int $offer_delivery_by_country Достава товара по России
 * @property string $offer_room_purpose Назначение помещение
 * @property string|null $offer_plot_kadastr Кадастровый №
 * @property string|null $offer_access_date Дата доступа
 * @property string|null $offer_access_to_object Доступ к ТП
 * @property int $offer_electricity_power Мощность эл-ва (кВт)
 * @property int $offer_water_supply_power Мощность ХВС (м3/час)
 * @property int $offer_gas_for_prod Газ для производства
 * @property int $offer_steam_for_prod Пар для производства
 * @property int $offer_warehouse_price_min E - складская от
 * @property int $offer_warehouse_price_max E - складская до
 * @property int $offer_warehouse_price_type Тип складской цены
 * @property int $offer_office_price_min E - офисная от
 * @property int $offer_office_price_max E - офисная до
 * @property int $offer_office_price_type Тип офисной цены
 * @property int $offer_retail_price_min E - торговая от
 * @property int $offer_retail_price_max E - торговая до
 * @property int $offer_retail_price_type Тип торговой цены
 * @property int $offer_technical_price_min E - техническая от
 * @property int $offer_technical_price_max E - техническая до
 * @property int $offer_technical_price_type Тип технической цены
 * @property int $offer_public_price_min E - общего пользования от
 * @property int $offer_public_price_max E - общего пользования до
 * @property int $offer_public_price_type Тип цены общего пользования
 * @property int $offer_land_plot_price_min E - участка от
 * @property int $offer_land_plot_price_max E - участка до
 * @property int $offer_land_plot_price_type Тип цены участка
 * @property string|null $offer_entrance_attributes Атрибут: Парковка и въезд
 * @property string|null $offer_infrastructure_attributes Атрибут: Инфраструктура
 * @property string|null $offer_infrastructure_railway Атрибут: Ж/Д
 * @property string|null $offer_offer_description Описание торгового предложения
 * @property string|null $offer_documents_layouts Документы, планировки
 * @property int $offer_tenant_id ID Арендатора
 * @property int $offer_rental_period Срок аренды в месяцах
 * @property int $offer_status Статус
 * @property string $offer_status_reason Описание статуса
 * @property int|null $offer_last_update Дата изменения объекта
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offer_company_id', 'offer_original_id', 'offer_full_price_min', 'offer_contact_id', 'offer_consultant_id', 'offer_owner_percent', 'offer_owner_percent_type', 'offer_owner_percent_type2', 'offer_tax_system', 'offer_room_purpose', 'offer_status_reason'], 'required'],
            [['offer_company_id', 'offer_original_id', 'offer_full_price_min', 'offer_contact_id', 'offer_hide', 'offer_consultant_id', 'offer_personal_exam', 'offer_signing_contract', 'offer_owner_percent', 'offer_owner_percent_type', 'offer_owner_percent_type2', 'offer_client_percent', 'offer_client_percent_type', 'offer_client_percent_type2', 'offer_agent_percent', 'offer_agent_percent_type', 'offer_agent_percent_type2', 'offer_tax_system', 'offer_sale_legal_entity', 'offer_opex', 'offer_opex_type', 'offer_ku_communal', 'offer_ku_communal_type', 'offer_vacation', 'offer_deposit', 'offer_built_to_rent', 'offer_built_to_suit', 'offer_construction_months', 'offer_project_availability', 'offer_with_rental_business', 'offer_annual_rental_flow', 'offer_monthly_rental_flow', 'offer_net_operating_income', 'offer_net_profit', 'offer_capitalization_rate', 'offer_payback_period_chod', 'offer_payback_period_map', 'offer_long_contracts_percent', 'offer_object_occupancy_percent', 'offer_property_tax', 'offer_land_tax', 'offer_land_lease', 'offer_uncompensated_expenses', 'offer_investment_project', 'offer_project_type', 'offer_need_change_tou', 'offer_project_stage', 'offer_gns_area', 'offer_unit_price_based_on_area', 'offer_unit_price_based_on_area_gns', 'offer_buildings_presence', 'offer_current_state', 'offer_teps_density', 'offer_teps_height', 'offer_teps_height_unit', 'offer_teps_buildings_percent', 'offer_crt', 'offer_szz_zone', 'offer_oopt_zone', 'offer_okn_zone', 'offer_has_exits_to_uds', 'offer_loading_operations', 'offer_cross_docking', 'offer_boxes_recalc', 'offer_product_culling', 'offer_product_repack', 'offer_pallet_formation', 'offer_stretch_tape_wrapp', 'offer_batch_accounting', 'offer_serial_numbers_account', 'offer_in_fifi_lifi_fefo_lefo', 'offer_product_choice', 'offer_provision_pallets', 'offer_complete_sets', 'offer_labeling', 'offer_product_packaging', 'offer_co_packing', 'offer_print_accomp_docks', 'offer_provision_reports', 'offer_inventory', 'offer_disposal_waste', 'offer_management_inventory', 'offer_acceptance_refunds', 'offer_pack_repair', 'offer_archive_storage', 'offer_zpl_services', 'offer_delivery_by_city', 'offer_delivery_by_region', 'offer_delivery_by_country', 'offer_electricity_power', 'offer_water_supply_power', 'offer_gas_for_prod', 'offer_steam_for_prod', 'offer_warehouse_price_min', 'offer_warehouse_price_max', 'offer_warehouse_price_type', 'offer_office_price_min', 'offer_office_price_max', 'offer_office_price_type', 'offer_retail_price_min', 'offer_retail_price_max', 'offer_retail_price_type', 'offer_technical_price_min', 'offer_technical_price_max', 'offer_technical_price_type', 'offer_public_price_min', 'offer_public_price_max', 'offer_public_price_type', 'offer_land_plot_price_min', 'offer_land_plot_price_max', 'offer_land_plot_price_type', 'offer_tenant_id', 'offer_rental_period', 'offer_status', 'offer_last_update'], 'integer'],
            [['offer_access_date'], 'safe'],
            [['offer_offer_description'], 'string'],
            [['offer_room_purpose', 'offer_plot_kadastr', 'offer_access_to_object'], 'string', 'max' => 64],
            [['offer_entrance_attributes', 'offer_infrastructure_attributes', 'offer_infrastructure_railway', 'offer_status_reason'], 'string', 'max' => 128],
            [['offer_documents_layouts'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offer_company_id' => 'Offer Company ID',
            'offer_original_id' => 'Offer Original ID',
			'offer_full_price_min' => 'Offer Full Price Min',
            'offer_contact_id' => 'Offer Contact ID',
            'offer_hide' => 'Offer Hide',
            'offer_consultant_id' => 'Offer Consultant ID',
            'offer_personal_exam' => 'Offer Personal Exam',
            'offer_signing_contract' => 'Offer Signing Contract',
            'offer_owner_percent' => 'Offer Owner Percent',
            'offer_owner_percent_type' => 'Offer Owner Percent Type',
            'offer_owner_percent_type2' => 'Offer Owner Percent Type2',
            'offer_client_percent' => 'Offer Client Percent',
            'offer_client_percent_type' => 'Offer Client Percent Type',
            'offer_client_percent_type2' => 'Offer Client Percent Type2',
            'offer_agent_percent' => 'Offer Agent Percent',
            'offer_agent_percent_type' => 'Offer Agent Percent Type',
            'offer_agent_percent_type2' => 'Offer Agent Percent Type2',
            'offer_tax_system' => 'Offer Tax System',
            'offer_sale_legal_entity' => 'Offer Sale Legal Entity',
            'offer_opex' => 'Offer Opex',
            'offer_opex_type' => 'Offer Opex Type',
            'offer_ku_communal' => 'Offer Ku Communal',
            'offer_ku_communal_type' => 'Offer Ku Communal Type',
            'offer_vacation' => 'Offer Vacation',
            'offer_deposit' => 'Offer Deposit',
            'offer_built_to_rent' => 'Offer Built To Rent',
            'offer_built_to_suit' => 'Offer Built To Suit',
            'offer_construction_months' => 'Offer Construction Months',
            'offer_project_availability' => 'Offer Project Availability',
            'offer_with_rental_business' => 'Offer With Rental Business',
            'offer_annual_rental_flow' => 'Offer Annual Rental Flow',
            'offer_monthly_rental_flow' => 'Offer Monthly Rental Flow',
            'offer_net_operating_income' => 'Offer Net Operating Income',
            'offer_net_profit' => 'Offer Net Profit',
            'offer_capitalization_rate' => 'Offer Capitalization Rate',
            'offer_payback_period_chod' => 'Offer Payback Period Chod',
            'offer_payback_period_map' => 'Offer Payback Period Map',
            'offer_long_contracts_percent' => 'Offer Long Contracts Percent',
            'offer_object_occupancy_percent' => 'Offer Object Occupancy Percent',
            'offer_property_tax' => 'Offer Property Tax',
            'offer_land_tax' => 'Offer Land Tax',
            'offer_land_lease' => 'Offer Land Lease',
            'offer_uncompensated_expenses' => 'Offer Uncompensated Expenses',
            'offer_investment_project' => 'Offer Investment Project',
            'offer_project_type' => 'Offer Project Type',
            'offer_need_change_tou' => 'Offer Need Change Tou',
            'offer_project_stage' => 'Offer Project Stage',
            'offer_gns_area' => 'Offer Gns Area',
            'offer_unit_price_based_on_area' => 'Offer Unit Price Based On Area',
            'offer_unit_price_based_on_area_gns' => 'Offer Unit Price Based On Area Gns',
            'offer_buildings_presence' => 'Offer Buildings Presence',
            'offer_current_state' => 'Offer Current State',
            'offer_teps_density' => 'Offer Teps Density',
            'offer_teps_height' => 'Offer Teps Height',
            'offer_teps_height_unit' => 'Offer Teps Height Unit',
            'offer_teps_buildings_percent' => 'Offer Teps Buildings Percent',
            'offer_crt' => 'Offer Crt',
            'offer_szz_zone' => 'Offer Szz Zone',
            'offer_oopt_zone' => 'Offer Oopt Zone',
            'offer_okn_zone' => 'Offer Okn Zone',
            'offer_has_exits_to_uds' => 'Offer Has Exits To Uds',
            'offer_loading_operations' => 'Offer Loading Operations',
            'offer_cross_docking' => 'Offer Cross Docking',
            'offer_boxes_recalc' => 'Offer Boxes Recalc',
            'offer_product_culling' => 'Offer Product Culling',
            'offer_product_repack' => 'Offer Product Repack',
            'offer_pallet_formation' => 'Offer Pallet Formation',
            'offer_stretch_tape_wrapp' => 'Offer Stretch Tape Wrapp',
            'offer_batch_accounting' => 'Offer Batch Accounting',
            'offer_serial_numbers_account' => 'Offer Serial Numbers Account',
            'offer_in_fifi_lifi_fefo_lefo' => 'Offer In Fifi Lifi Fefo Lefo',
            'offer_product_choice' => 'Offer Product Choice',
            'offer_provision_pallets' => 'Offer Provision Pallets',
            'offer_complete_sets' => 'Offer Complete Sets',
            'offer_labeling' => 'Offer Labeling',
            'offer_product_packaging' => 'Offer Product Packaging',
            'offer_co_packing' => 'Offer Co Packing',
            'offer_print_accomp_docks' => 'Offer Print Accomp Docks',
            'offer_provision_reports' => 'Offer Provision Reports',
            'offer_inventory' => 'Offer Inventory',
            'offer_disposal_waste' => 'Offer Disposal Waste',
            'offer_management_inventory' => 'Offer Management Inventory',
            'offer_acceptance_refunds' => 'Offer Acceptance Refunds',
            'offer_pack_repair' => 'Offer Pack Repair',
            'offer_archive_storage' => 'Offer Archive Storage',
            'offer_zpl_services' => 'Offer Zpl Services',
            'offer_delivery_by_city' => 'Offer Delivery By City',
            'offer_delivery_by_region' => 'Offer Delivery By Region',
            'offer_delivery_by_country' => 'Offer Delivery By Country',
            'offer_room_purpose' => 'Offer Room Purpose',
            'offer_plot_kadastr' => 'Offer Plot Kadastr',
            'offer_access_date' => 'Offer Access Date',
            'offer_access_to_object' => 'Offer Access To Object',
            'offer_electricity_power' => 'Offer Electricity Power',
            'offer_water_supply_power' => 'Offer Water Supply Power',
            'offer_gas_for_prod' => 'Offer Gas For Prod',
            'offer_steam_for_prod' => 'Offer Steam For Prod',
            'offer_warehouse_price_min' => 'Offer Warehouse Price Min',
            'offer_warehouse_price_max' => 'Offer Warehouse Price Max',
            'offer_warehouse_price_type' => 'Offer Warehouse Price Type',
            'offer_office_price_min' => 'Offer Office Price Min',
            'offer_office_price_max' => 'Offer Office Price Max',
            'offer_office_price_type' => 'Offer Office Price Type',
            'offer_retail_price_min' => 'Offer Retail Price Min',
            'offer_retail_price_max' => 'Offer Retail Price Max',
            'offer_retail_price_type' => 'Offer Retail Price Type',
            'offer_technical_price_min' => 'Offer Technical Price Min',
            'offer_technical_price_max' => 'Offer Technical Price Max',
            'offer_technical_price_type' => 'Offer Technical Price Type',
            'offer_public_price_min' => 'Offer Public Price Min',
            'offer_public_price_max' => 'Offer Public Price Max',
            'offer_public_price_type' => 'Offer Public Price Type',
            'offer_land_plot_price_min' => 'Offer Land Plot Price Min',
            'offer_land_plot_price_max' => 'Offer Land Plot Price Max',
            'offer_land_plot_price_type' => 'Offer Land Plot Price Type',
            'offer_entrance_attributes' => 'Offer Entrance Attributes',
            'offer_infrastructure_attributes' => 'Offer Infrastructure Attributes',
            'offer_infrastructure_railway' => 'Offer Infrastructure Railway',
            'offer_offer_description' => 'Offer Offer Description',
            'offer_documents_layouts' => 'Offer Documents Layouts',
            'offer_tenant_id' => 'Offer Tenant ID',
            'offer_rental_period' => 'Offer Rental Period',
            'offer_status' => 'Offer Status',
            'offer_status_reason' => 'Offer Status Reason',
            'offer_last_update' => 'Offer Last Update',
        ];
    }
	
	public function getBuildingObject()
    {
		//return $this->hasMany(BuildingObject::class, ['b_obj_offer_id' => 'offer_original_id']);
		return $this->hasMany(BuildingObject::class, ['b_obj_building_id' => 'offer_object_id']);
    }
}
