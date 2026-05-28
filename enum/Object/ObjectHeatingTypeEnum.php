<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectHeatingTypeEnum extends AbstractEnum
{
	public const GAS = 1;
	public const DIESEL = 2;
	public const WOOD = 3;
	public const COAL = 4;
	public const ELECTRIC = 5;
	public const SOLAR = 6;
	public const WIND = 7;

	public static function labels(): array
	{
		return [
			self::GAS => 'Газовое',
			self::DIESEL => 'Дизельное',
			self::WOOD => 'Дровяное',
			self::COAL => 'Угольное',
			self::ELECTRIC => 'Электрическое',
			self::SOLAR => 'Солнечные батареи',
			self::WIND => 'Ветряк',
		];
	}
}