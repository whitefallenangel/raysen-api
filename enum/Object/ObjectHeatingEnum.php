<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectHeatingEnum extends AbstractEnum
{
	public const AUTONOMOUS = 1;
	public const CENTRAL = 2;
	public const NONE = 3;

	public static function labels(): array
	{
		return [
			self::AUTONOMOUS => 'Автономное',
			self::CENTRAL => 'Центральное',
			self::NONE => 'Отсутствует',
		];
	}
}