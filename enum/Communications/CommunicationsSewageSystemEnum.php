<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsSewageSystemEnum extends AbstractEnum
{
	public const SHARED = 1;
	public const INDIVIDUAL = 2;
	public const NONE = 3;

	public static function labels(): array
	{
		return [
			self::SHARED => 'Общий',
			self::INDIVIDUAL => 'Индивидуальный',
			self::NONE => 'Отсутствует',
		];
	}
}
