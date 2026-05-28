<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingReadyStatusEnum extends AbstractEnum
{
	public const CONSTRUCTION = 1;
	public const IN_PROJECT_STAGE = 2;
	public const BUILT = 3;

	public static function labels(): array
	{
		return [
			self::CONSTRUCTION => 'Строительство',
			self::IN_PROJECT_STAGE => 'В стадии проекта',
			self::BUILT => 'Построено',
		];
	}
}