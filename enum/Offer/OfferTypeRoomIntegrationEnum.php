<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferTypeRoomIntegrationEnum extends AbstractEnum
{
	public const BUILT_IN = 1;
	public const ATTACHED = 2;
	public const BUILT_IN_ATTACHED = 3;

	public static function labels(): array
	{
		return [
			self::BUILT_IN  => 'Встроенное',
			self::ATTACHED => 'Пристроенное',
			self::BUILT_IN_ATTACHED => 'Встроенно-пристроенное',
		];
	}
}