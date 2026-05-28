<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectLayoutFeaturesEnum extends AbstractEnum
{
	public const OPENSPACE = 1;
	public const CORRIDOR = 2;
	public const ADJACENT_ROOMS = 3;
	public const OFFICE = 4;
	public const MIXED = 5;

	public static function labels(): array
	{
		return [
			self::OPENSPACE => 'OpenSpace',
			self::CORRIDOR => 'Корридорная',
			self::ADJACENT_ROOMS => 'Смежные помещения',
			self::OFFICE => 'Кабинетная',
			self::MIXED => 'Смешанная',
		];
	}
}