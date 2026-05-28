<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferProjectTypeEnum extends AbstractEnum
{
	public const HIGH_RISE_RESIDENT_DEVELOPMENT = 1;
	public const LOW_RISE_RESIDENT_DEVELOPMENT = 2;
	public const COTTAGE_SETTLEMENT = 3;
	public const APARTMENT_BUILDING = 4;
	public const HOTEL_BUILDING = 5;
	public const OFFICE_BUILDING = 6;
	public const RETAIL_BUILDING = 7;
	public const CATERING_BUILDING = 8;
	public const WAREHOUSING_PROD_BUILDING = 9;
	public const MEDICAL_CENTER = 10;
	public const RIF_CONSTRUCTION = 11;
	public const MFC_CONSTRUCTION = 12;
	public const FOK_CONSTRUCTION = 13;

	public static function labels(): array
	{
		return [
			self::HIGH_RISE_RESIDENT_DEVELOPMENT => 'Многоэтажная жилая застройка',
			self::LOW_RISE_RESIDENT_DEVELOPMENT => 'Малоэтажная жилая застройка',
			self::COTTAGE_SETTLEMENT => 'Коттеджный поселок',
			self::APARTMENT_BUILDING => 'Строительство апартаментов',
			self::HOTEL_BUILDING => 'Строительство гостиницы',
			self::OFFICE_BUILDING => 'Строительство офисных площадей',
			self::RETAIL_BUILDING => 'Строительство торговых площадей',
			self::CATERING_BUILDING => 'Строительство предприятия общественного питания',
			self::WAREHOUSING_PROD_BUILDING => 'Строительство складских / производственных площадей',
			self::MEDICAL_CENTER => 'Медицинский центр',
			self::RIF_CONSTRUCTION => 'Строительство объекта дорожной инфраструктуры',
			self::MFC_CONSTRUCTION => 'Строительство МФК',
			self::FOK_CONSTRUCTION => 'Строительство ФОК',
		];
	}
}
