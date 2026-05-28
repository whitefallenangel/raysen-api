<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectFireFightingEnum extends AbstractEnum
{
	public const SPRINKLER = 1;
	public const POWDER = 2;
	public const GAS = 3;
	public const FOAM = 4;
	public const HYDRANTS = 5;
	public const FIRE_EXTINGUISHERS = 6;
	public const MISSING = 7;

	public static function labels(): array
	{
		return [
			self::SPRINKLER => 'Спринклерное',
			self::POWDER => 'Порошковое',
			self::GAS => 'Газовое',
			self::FOAM => 'Пенное',
			self::HYDRANTS => 'Гидранты',
			self::FIRE_EXTINGUISHERS => 'Огнетушители',
			self::MISSING => 'Отсутствует',
		];
	}
}
