<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class DirectionEnum extends AbstractEnum
{
	public const NORTH = 1;
	public const NORTH_EAST = 2;
	public const EAST = 3;
	public const SOUTH_EAST = 4;
	public const SOUTH = 5;
	public const SOUTH_WEST = 6;
	public const WEST = 7;
	public const NORTH_WEST = 8;

	public static function labels(): array
	{
		return [
			self::NORTH => 'Север',
			self::NORTH_EAST => 'Северо - Восток',
			self::EAST => 'Восток',
			self::SOUTH_EAST => 'Юго - Восток',
			self::SOUTH => 'Юг',
			self::SOUTH_WEST => 'Юго - Запад',
			self::WEST => 'Запад',
			self::NORTH_WEST => 'Северо - Запад',
		];
	}
}