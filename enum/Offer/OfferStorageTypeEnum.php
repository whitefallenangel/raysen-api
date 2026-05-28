<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferStorageTypeEnum extends AbstractEnum
{
	public const ON_RACKS = 1;
	public const ON_THE_FLOOR = 2;

	public static function labels(): array
	{
		return [
			self::ON_RACKS => 'На стеллажах',
			self::ON_THE_FLOOR => 'Напольно',
		];
	}
}
