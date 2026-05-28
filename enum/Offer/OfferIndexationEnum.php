<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferIndexationEnum extends AbstractEnum
{
	public const CPI = 1;
	public const ROSSTAT = 2;
	public const INFLATION_INDEX = 3;
	public const AT_DISCRETION = 4;

	public static function labels(): array
	{
		return [
			self::CPI => 'ИПЦ',
			self::ROSSTAT => 'Росстат',
			self::INFLATION_INDEX => 'На индекс инфляции',
			self::AT_DISCRETION => 'На усмотрение',
		];
	}
}