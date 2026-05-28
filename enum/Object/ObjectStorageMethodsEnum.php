<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectStorageMethodsEnum extends AbstractEnum
{
	public const SHELVING = 1;
	public const SMALL_CELL = 2;
	public const FLOOR = 3;
	public const OUTDOOR = 4;

	public static function labels(): array
	{
		return [
			self::SHELVING => 'Стеллажное',
			self::SMALL_CELL => 'Напольное',
			self::FLOOR => 'Мелкоячеистое',
			self::OUTDOOR => 'Уличное',
		];
	}
}