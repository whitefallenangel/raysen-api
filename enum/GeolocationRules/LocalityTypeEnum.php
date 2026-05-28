<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class LocalityTypeEnum extends AbstractEnum
{
	public const TOWNSHIP = 1;
	public const CITY = 2;
	public const COUNTRYSIDE = 3;
	public const WORK_SETTLEMENT = 4;
	public const FACTORY_SETTLEMENT = 5;
	public const FARM_SETTLEMENT = 6;
	public const VILLAGE = 7;
	public const MICRO_DISTRICT = 8;
	public const RURAL_SETTLEMENT = 9;
	public const URBAN_SETTLEMENT = 10;
	public const SETTLEMENT = 11;
	public const SUMMER_COTTAGE_SETTLEMENT = 12;
	public const COTTAGE_SETTLEMENT = 13;
	public const URBAN_TYPE_SETTLEMENT = 14;
	public const SNT = 15;

	public static function labels(): array
	{
		return [
			self::TOWNSHIP => 'Поселок',
			self::CITY => 'Город',
			self::COUNTRYSIDE => 'Деревня',
			self::WORK_SETTLEMENT => 'Рабочий поселок',
			self::FACTORY_SETTLEMENT => 'Поселок завода',
			self::FARM_SETTLEMENT => 'Поселок совхоза',
			self::VILLAGE => 'Село',
			self::MICRO_DISTRICT => 'Микрорайон',
			self::RURAL_SETTLEMENT => 'Сельское поселение',
			self::URBAN_SETTLEMENT => 'Городское поселение',
			self::SETTLEMENT => 'Поселение',
			self::SUMMER_COTTAGE_SETTLEMENT => 'Дачный поселок',
			self::COTTAGE_SETTLEMENT => 'Коттеджный поселок',
			self::URBAN_TYPE_SETTLEMENT => 'Поселок городского типа',
			self::SNT => 'СНТ',
		];
	}
}