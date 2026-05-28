<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferObjectAccessEnum extends AbstractEnum
{
	public const SHARED_FROM_STREET = 1;
	public const SEPARATE_FROM_STREET = 2;
	public const SHARED_FROM_COURTYARD = 3;
	public const SEPARATE_FROM_COURTYARD = 4;
	public const THROUGH_AN_EASEMENT = 5;

	public static function labels(): array
	{
		return [
			self::SHARED_FROM_STREET => 'Общий вход с улицы',
			self::SEPARATE_FROM_STREET => 'Отдельный вход с улицы',
			self::SHARED_FROM_COURTYARD => 'Общий вход со двора',
			self::SEPARATE_FROM_COURTYARD => 'Отдельный вход со двора',
			self::THROUGH_AN_EASEMENT => 'Вход через сервитут',
		];
	}
}