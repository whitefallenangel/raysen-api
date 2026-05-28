<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class ElevatorLiftControlTypeEnum extends AbstractEnum
{
	public const FROM_FLOOR = 1;
	public const FROM_CABIN = 2;

	public static function labels(): array
	{
		return [
			self::FROM_FLOOR => 'С пола',
			self::FROM_CABIN => 'Из кабины',
		];
	}
}