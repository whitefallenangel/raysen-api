<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class MoscowRegionAdjacentEnum extends AbstractEnum
{
	public const CAO = 1;
	public const SAO = 2;
	public const SWAO = 3;
	public const WAO = 4;
	public const YUVAO = 5;
	public const YUZAO = 6;
	public const ZAO = 7;
	public const SZAO = 8;
	public const NEW_MOSCOW = 9;
	public const ZELENOGRAD = 10;

	public static function labels(): array
	{
		return [
			self::CAO => 'ЦАО',
			self::SAO => 'САО',
			self::SWAO => 'СВАО',
			self::WAO => 'ВАО',
			self::YUVAO => 'ЮВАО',
			self::YUAO => 'ЮАО',
			self::YUZAO => 'ЮЗАО',
			self::ZAO => 'ЗАО',
			self::SZAO => 'СЗАО',
			self::NEW_MOSCOW => 'Новая Москва',
			self::ZELENOGRAD => 'Зеленоград',
		];
	}
}