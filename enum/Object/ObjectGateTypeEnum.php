<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectGateTypeEnum extends AbstractEnum
{
	public const GENERAL = 1;
	public const INDIVIDUAL = 2;

	public static function labels(): array
	{
		return [
			self::GENERAL => 'Общие',
			self::INDIVIDUAL => 'Индивидуальные',
		];
	}
}
