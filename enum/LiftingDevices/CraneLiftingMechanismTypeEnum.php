<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class CraneLiftingMechanismTypeEnum extends AbstractEnum
{
	public const SUSPENDED = 1;
	public const OUTSIDE = 2;

	public static function labels(): array
	{
		return [
			self::SUSPENDED => 'Подвесной',
			self::TOP_MOUNTED_ON_TROLLEY => 'Опирается сверху (на тележке)',
		];
	}
}