<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class CraneClassificationEnum extends AbstractEnum
{
	public const OVERHEAD_CRANE = 1;
	public const BRIDGE_CRANE = 2;
	public const GANTRY_CRANE = 3;
	public const TELPHER_WINCH_FIXED = 4;

	public static function labels(): array
	{
		return [
			self::OVERHEAD_CRANE => 'Кран-балка',
			self::BRIDGE_CRANE => 'Мостовой',
			self::GANTRY_CRANE => 'Козловой',
			self::TELPHER_WINCH_FIXED => 'Тельфер/Лебедка (неподвижный)',
		];
	}
}