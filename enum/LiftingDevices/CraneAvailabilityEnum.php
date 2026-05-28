<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class CraneAvailabilityEnum extends AbstractEnum
{
	public const AVAILABLE = 1;
	public const ONLY_CRANE_TRACKS = 2;

	public static function labels(): array
	{
		return [
			self::AVAILABLE => 'Есть кран',
			self::ONLY_CRANE_TRACKS => 'Есть только подкрановые пути',
		];
	}
}