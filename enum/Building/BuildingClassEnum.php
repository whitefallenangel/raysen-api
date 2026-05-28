<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingClassEnum extends AbstractEnum
{
	public const A_PLUS = 5;
	public const A = 1;
	public const B_PLUS = 6;
	public const B = 2;
	public const C = 3;
	public const D = 4;

	public static function labels(): array
	{
		return [
			self::A_PLUS => 'А+',
			self::A => 'А',
			self::B_PLUS => 'B+',
			self::B => 'B',
			self::C => 'C',
			self::D => 'D',
		];
	}
}