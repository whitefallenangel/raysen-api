<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsHeatingTypeEnum extends AbstractEnum
{
	public const GAS_MAIN = 1;
	public const DIESEL = 2;
	public const WOOD = 3;
	public const COAL = 4;
	public const ELECTRIC = 5;
	public const SOLAR = 6;
	public const WIND = 7;
	public const GAS_HOLDER = 8;

	public static function labels(): array
	{
		return [
			self::GAS_MAIN => 'Газовое (магистральный)',
			self::DIESEL => 'Дизельное',
			self::WOOD => 'Дровяное',
			self::COAL => 'Угольное',
			self::ELECTRIC => 'Электрическое',
			self::SOLAR => 'Солнечные батареи',
			self::WIND => 'Ветряк',
			self::GAS_HOLDER => 'Газгольдер',
		];
	}
}