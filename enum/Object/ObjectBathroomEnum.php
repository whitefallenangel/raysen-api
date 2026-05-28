<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectBathroomEnum extends AbstractEnum
{
	public const GENERAL = 1;
	public const INDIVIDUAL = 2;

	public static function labels(): array
	{
		return [
			self::GENERAL => 'Общий',
			self::INDIVIDUAL => 'Индивидуальный',
		];
	}
}
