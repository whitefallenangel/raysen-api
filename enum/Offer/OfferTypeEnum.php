<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferTypeEnum extends AbstractEnum
{
	public const RENT = 1;
	public const SALE = 2;
	public const SAFE_STORAGE = 3;
	public const SUBRENT = 4;

	public static function labels(): array
	{
		return [
			self::RENT => 'Аренда',
			self::SALE => 'Продажа',
			self::SAFE_STORAGE => 'Ответственное хранение',
			self::SUBRENT => 'Субаренда',
		];
	}
}