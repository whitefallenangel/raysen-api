<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectColumnsGridEnum extends AbstractEnum
{
	public const GRID_6_ON_6 = 1;
	public const GRID_6_ON_9 = 2;
	public const GRID_6_ON_12 = 3;
	public const GRID_6_ON_18 = 4;
	public const GRID_6_ON_24 = 5;
	public const GRID_9_ON_9 = 6;
	public const GRID_9_ON_12 = 7;
	public const GRID_9_ON_18 = 8;
	public const GRID_9_ON_24 = 9;
	public const GRID_12_ON_12 = 10;
	public const GRID_12_ON_18 = 11;
	public const GRID_12_ON_24 = 12;
	public const NO_GRID = 13;

	public static function labels(): array
	{
		return [
			self::GRID_6_ON_6 => '6x6',
			self::GRID_6_ON_9 => '6x9',
			self::GRID_6_ON_12 => '6x12',
			self::GRID_6_ON_18 => '6x18',
			self::GRID_6_ON_24 => '6x24',
			self::GRID_9_ON_9 => '9x9',
			self::GRID_9_ON_12 => '9x12',
			self::GRID_9_ON_18 => '9x18',
			self::GRID_9_ON_24 => '9x24',
			self::GRID_12_ON_12 => '12x12',
			self::GRID_12_ON_18 => '12x18',
			self::GRID_12_ON_24 => '12x24',
			self::NO_GRID => 'без колонн',
		];
	}
}