<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferOpexEnum extends AbstractEnum
{
	public const ENABLED = 1;
	public const NOT_ENABLED = 2;
	public const PARTIALLY_ENABLED = 3;
	public const NO_FULL_INFO = 4;

	public static function labels(): array
	{
		return [
			self::ENABLED => 'Включено',
			self::NOT_ENABLED => 'Не включено',
			self::PARTIALLY_ENABLED => 'Частично включено',
			self::NO_FULL_INFO => 'Нет полной информации',
		];
	}
}
