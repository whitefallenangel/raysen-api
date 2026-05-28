<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsAirConditioningEnum extends AbstractEnum
{
	public const CENTRAL = 1;
	public const SPLIT_SYSTEMS = 2;
	public const FAN_COIL = 3;
	public const NONE = 4;

	public static function labels(): array
	{
		return [
			self::CENTRAL => 'Центральное',
			self::SPLIT_SYSTEMS	=> 'Сплит-системы',
			self::FAN_COIL => 'Фанкоил',
			self::NONE => 'Отсутствует',
		];
	}
}
