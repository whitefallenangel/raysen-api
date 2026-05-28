<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingLineEnum extends AbstractEnum
{
	public const FIRST_LINE   = 1;
	public const IN_COURTYARD = 2;

	public static function labels(): array
	{
		return [
			self::FIRST_LINE => 'Первая линия',
			self::IN_COURTYARD => 'В глубине',
		];
	}
}