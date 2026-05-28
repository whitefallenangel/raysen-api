<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectFloorTypeEnum extends AbstractEnum
{
	public const ANTI_DUST = 2;
	public const ASPHALT = 1;
	public const SCREED = 5;
	public const CONCRETE_SLABS = 3;
	public const TECHNICAL_LAYOUT = 4;
	public const CARPET = 6;
	public const LAMINATE = 7;
	public const LINOLEUM = 8;
	public const PARQUET_BOARD = 9;

	public static function labels(): array
	{
		return [
			self::ANTI_DUST => 'Антипыль',
			self::ASPHALT => 'Асфальт',
			self::SCREED => 'Стяжка',
			self::CONCRETE_SLABS => 'Бетонные плиты',
			self::TECHNICAL_LAYOUT => 'Техничкая плика',
			self::CARPET => 'Ковролин',
			self::LAMINATE => 'Ламинат',
			self::LINOLEUM => 'Линолеум',
			self::PARQUET_BOARD => 'Паркетная доска',
		];
	}
}
