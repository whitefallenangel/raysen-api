<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferCommunalServicesEnum extends AbstractEnum
{
	public const ENABLED = 1;
	public const NOT_ENABLED = 2;
	public const PARTIALLY_ENABLED = 3;
	public const BY_METER = 4;
	public const NO_FULL_INFO = 5;

	public static function labels(): array
	{
		return [
			self::ENABLED => 'Включено',
			self::NOT_ENABLED => 'Не включено',
			self::PARTIALLY_ENABLED => 'Частично включено',
			self::BY_METER => 'По счетчику',
			self::NO_FULL_INFO => 'Нет полной информации',
		];
	}
}
