<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class CraneMechanismTypeEnum extends AbstractEnum
{
	public const SUSPENDED  = 1;
	public const OUTSIDE = 2;

	public static function labels(): array
	{
		return [
			self::SUSPENDED => 'Подвесной',
			self::SUPPORTED_FROM_ABOVE => 'Опирается сверху (на тележке)',
		];
	}
}