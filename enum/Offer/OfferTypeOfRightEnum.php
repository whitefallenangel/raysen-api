<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferTypeOfRightEnum extends AbstractEnum
{
	public const RENT = 1;
	public const OWNERSHIP = 2;

	public static function labels(): array
	{
		return [
			self::RENT => 'Аренда',
			self::OWNERSHIP => 'Собственность',
		];
	}
}