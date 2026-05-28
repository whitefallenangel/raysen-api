<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferPurposeRoomEnum extends AbstractEnum
{
	public const OFFICE = 1;
	public const BANK = 2;
	public const PHARMACY = 3;
	public const RESTAURANT_CAFE_BAR = 4;
	public const BEAUTY_SALON = 5;
	public const FLOWER_SHOP = 6;
	public const CLOTHES = 7;
	public const FURNITURE = 8;
	public const SHOPPING_CENTER = 9;
	public const MEDICAL_CENTER = 10;
	public const FITNESS = 11;
	public const HOTEL = 11;
	public const FOOD_STORE = 12;
	public const NON_FOOD_STORE = 13;
	public const DRY_CLEANING = 14;
	public const ORDER_PICK_UP_POINT = 15;
	public const DARK_STORE = 16;
	public const AUTO_TECHNICAL_CENTER = 17;

	public static function labels(): array
	{
		return [
			self::OFFICE => 'Офисное',
			self::BANK => 'Банк',
			self::PHARMACY => 'Аптека',
			self::RESTAURANT_CAFE_BAR => 'Ресторан, кафе, бар',
			self::BEAUTY_SALON => 'Салон красоты',
			self::FLOWER_SHOP => 'Цветочный',
			self::CLOTHES => 'Одежда',
			self::FURNITURE => 'Мебельный',
			self::SHOPPING_CENTER => 'Торговый центр',
			self::MEDICAL_CENTER => 'Медцентр',
			self::FITNESS => 'Фитнес',
			self::HOTEL => 'Гостиница',
			self::FOOD_STORE => 'Продовольственный магазин',
			self::NON_FOOD_STORE => 'Непродовольственный магазин',
			self::DRY_CLEANING => 'Химчистка',
			self::ORDER_PICK_UP_POINT => 'Пункт выдачи заказов (ПВЗ)',
			self::DARK_STORE => 'Dark Store',
			self::AUTO_TECHNICAL_CENTER => 'Автотехцентр',
		];
	}
}