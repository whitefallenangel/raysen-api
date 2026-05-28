<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsWaterSupplyTypeEnum extends AbstractEnum
{
	public const COLD = 1;
	public const HOT_CENTERAL_COLD = 2;
	public const HOT_BOILER_COLD = 3;

	public static function labels(): array
	{
		return [
			self::COLD => 'Холодное',
			self::HOT_CENTERAL_COLD => 'Холодное + Горячее (центральное)',
			self::HOT_BOILER_COLD => 'Холодное + Горячее (индивидуальное)',
		];
	}
}
