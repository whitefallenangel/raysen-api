<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferSigningContractEnum extends AbstractEnum
{
	public const REGULAR_SIGNED = 1;
	public const EXCLUSIVE_SIGNED = 2;
	public const NOT_SIGNED = 3;

	public static function labels(): array
	{
		return [
			self::REGULAR_SIGNED => 'Подписан обычный',
			self::EXCLUSIVE_SIGNED => 'Подписан эксклюзив',
			self::NOT_SIGNED => 'Не подписан',
		];
	}
}