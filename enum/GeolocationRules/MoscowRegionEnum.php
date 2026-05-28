<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class MoscowRegionEnum extends AbstractEnum
{
	public const CAO = 1;
	public const SAO = 6;
	public const SVAO = 7;
	public const VAO = 9;
	public const YUVAO = 8;
	public const YUAO = 5;
	public const YUZAO = 4;
	public const ZAO = 2;
	public const SZAO = 3;
	public const NEW_MOSCOW = 10;
	public const ZELENOGRAD = 11;

	public static function labels(): array
	{
		return [
			self::CAO => 'ЦАО',
			self::SAO => 'САО',
			self::SVAO => 'СВАО',
			self::VAO => 'ВАО',
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