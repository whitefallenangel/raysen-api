<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectGateModeEnum extends AbstractEnum
{
	public const DOCKSHIPPERS = 1;
	public const ZERO = 2;
	public const ON_THE_RAMP = 3;
	public const RAILWAY_RAMP = 4;

	public static function labels(): array
	{
		return [
			self::DOCKSHIPPERS => 'Докшелтеры',
			self::ZERO	=> 'Нулевые',
			self::ON_THE_RAMP => 'На рампе',
			self::RAILWAY_RAMP => 'Ж/Д рампа',
		];
	}
}
