<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingExternalDecorEnum extends AbstractEnum
{
	public const BRICK = 1;
	public const SIDING = 2;
	public const PLASTER = 4;
	public const CONCRETE_PANELS = 6;
	public const TILE = 7;
	public const SANDWICH_PANELS = 3;

	public static function labels(): array
	{
		return [
			self::BRICK => 'Кирпич',
			self::SIDING => 'Сайдинг',
			self::PLASTER => 'Штукатурка',
			self::CONCRETE_PANELS => 'Бетонные панели',
			self::TILE => 'Плитка',
			self::SANDWICH_PANELS => 'Сэндвич-панели',
		];
	}
}