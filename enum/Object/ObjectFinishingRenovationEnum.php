<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectFinishingRenovationEnum extends AbstractEnum
{
	public const SHELL_AND_CORE = 6;
	public const PRE_FINISHING = 2;
	public const HIGH_QUALITY_REPAIRS = 7;
	public const NEED_COSMETIC_REPAIR = 3;
	public const NEED_MAJOR_REPAIR = 5;
	public const LOFT_STYLE = 8;
	public const STANDARD_OFFICE = 1;
	public const PARTIAL_RENOVATION = 4;

	public static function labels(): array
	{
		return [
			self::SHELL_AND_CORE => 'Shell&Core (В бетоне)',
			self::PRE_FINISHING => 'WhiteBox (Предчистовая)',
			self::PARTIAL_RENOVATION => 'Ремонт выполнен частично',
			self::STANDARD_OFFICE => 'Стандартный офисный ремонт',
			self::HIGH_QUALITY_REPAIRS => 'Высококачественный ремонт',
			self::LOFT_STYLE => 'Ремонт в стиле Loft',
			self::NEED_COSMETIC_REPAIR => 'Требует косметического ремонта',
			self::NEED_MAJOR_REPAIR => 'Требует капитального ремонта',
		];
	}
}
