<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectSpecialFloorEnum extends AbstractEnum
{
	public const ENTRESOL_1_TIER = 1;
	public const ENTRESOL_2_TIER = 2;
	public const ENTRESOL_3_TIER = 3;
	public const ATTIC = 4;
	public const BASEMENT = 5;
	public const UNDERGROUND_PARKING = 6;
	public const TECHNICAL_FLOOR = 7;
	public const FOUNDATION = 8;
	public const LOFT = 9;
	public const OPERABLE_ROOF = 10;

	public static function labels(): array
	{
		return [
			self::ENTRESOL_1_TIER => 'Антресоль 1 ярус',
			self::ENTRESOL_2_TIER => 'Антресоль 2 ярус',
			self::ENTRESOL_3_TIER => 'Антресоль 3 ярус',
			self::ATTIC => 'Мансарда',
			self::BASEMENT => 'Подвал',
			self::UNDERGROUND_PARKING => 'Подземная парковка',
			self::TECHNICAL_FLOOR => 'Технический этаж',
			self::FOUNDATION => 'Цоколь',
			self::LOFT => 'Чердак',
			self::OPERABLE_ROOF => 'Эксплуатируемая кровля',
		];
	}
}