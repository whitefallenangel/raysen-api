<?php

namespace app\enum\Security;

use app\enum\AbstractEnum;

final class SecurityFireFightingEnum extends AbstractEnum
{
	public const SPRINKLER = 2;
	public const POWDER = 3;
	public const GAS = 4;
	public const FOAM = 6;
	public const HYDRANTS = 1;
	public const FIRE_EXTINGUISHERS = 5;
	public const NONE = 7;

	public static function labels(): array
	{
		return [
			self::SPRINKLER => 'Спринклерное',
			self::POWDER => 'Порошковое',
			self::GAS => 'Газовое',
			self::FOAM => 'Пенное',
			self::HYDRANTS => 'Гидранты',
			self::FIRE_EXTINGUISHERS => 'Огнетушители',
			self::NONE => 'Отсутствует',
		];
	}
}
