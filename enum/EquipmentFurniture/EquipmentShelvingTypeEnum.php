<?php

namespace app\enum\EquipmentFurniture;

use app\enum\AbstractEnum;

final class EquipmentShelvingTypeEnum extends AbstractEnum
{
	public const CONVENTIONAL_FRONT = 1;
	public const FILLING = 2;
	public const NARROW_AISLES = 3;
	public const MEZZANINE = 4;
	public const AUTOMATED = 5;

	public static function labels(): array
	{
		return [
			self::CONVENTIONAL_FRONT => 'Обычные/фронтальные',
			self::FILLING => 'Набивные',
			self::NARROW_AISLES => 'Узкопроходные',
			self::MEZZANINE => 'Мезанинные',
			self::AUTOMATED => 'Автоматизированные',
		];
	}
}
