<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsVentilationEnum extends AbstractEnum
{
	public const NATURAL = 1;
	public const SUPPLY = 4;
	public const SUPPLY_AND_EXHAUST = 3;
	public const EXHAUST = 2;

	public static function labels(): array
	{
		return [
			self::NATURAL => 'Естественная',
			self::SUPPLY => 'Приточная',
			self::SUPPLY_AND_EXHAUST => 'Приточно-вытяжная',
			self::EXHAUST => 'Вытяжная',
		];
	}
}
