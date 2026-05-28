<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class CraneLocationEnum extends AbstractEnum
{
	public const INSIDE  = 1;
	public const OUTSIDE = 2;

	public static function labels(): array
	{
		return [
			self::INSIDE => 'Внутри',
			self::OUTSIDE => 'Снаружи',
		];
	}
}