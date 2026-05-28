<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectFurnitureEquipmentEnum extends AbstractEnum
{
	public const FURNITURE = 1;
	public const COMMERCIAL_EQUIPMENT = 2;
	public const OFFICE_EQUIPMENT = 3;
	public const RACKS = 4;
	public const LOADING_EQUIPMENT = 5;

	public static function labels(): array
	{
		return [
			self::FURNITURE => 'Мебель',
			self::COMMERCIAL_EQUIPMENT => 'Оборудование торговое',
			self::OFFICE_EQUIPMENT => 'Оборудование офисное',
			self::RACKS => 'Стеллажи',
			self::LOADING_EQUIPMENT => 'Погрузочная техника',
		];
	}
}