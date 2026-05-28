<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsInternetEnum extends AbstractEnum
{
	public const FIBER = 1;
	public const WIRELESS = 2;
	public const NONE = 3;

	public static function labels(): array
	{
		return [
			self::FIBER => 'Оптоволокно',
			self::WIRELESS => 'Беспроводной',
			self::NONE => 'Отсутствует',
		];
	}
}
