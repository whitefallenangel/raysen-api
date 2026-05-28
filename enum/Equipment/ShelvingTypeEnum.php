<?php

namespace app\enum\TerritoryAttribute;

use app\enum\AbstractEnum;

class ShelvingTypeEnum extends AbstractEnum
{
	public const REGULAR_FRONT_ENTRY = 1;
	public const DRIVE_IN = 4;
	public const NARROW_AISLE = 2;
	public const MEZZANINE = 3;
	public const AUTOMATED = 5;

	public static function labels(): array
	{
		return [
			self::REGULAR_FRONT_ENTRY => 'Обычные/фронтальные',
			self::DRIVE_IN => 'Набивные',
			self::NARROW_AISLE => 'Узкопроходные',
			self::MEZZANINE => 'Мезанинные',
			self::AUTOMATED => 'Автоматизированные',
		];
	}
}