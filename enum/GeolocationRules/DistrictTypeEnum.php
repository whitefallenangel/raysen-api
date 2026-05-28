<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class DistrictTypeEnum extends AbstractEnum
{
	public const DISTRICT = 1;
	public const URBAN_DISTRICT = 2;

	public static function labels(): array
	{
		return [
			self::DISTRICT => 'район',
			self::URBAN_DISTRICT => 'городской округ',
		];
	}
}