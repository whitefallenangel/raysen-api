<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingConditionEnum extends AbstractEnum
{
	public const NEW   = 1;
	public const WORKING = 3;
	public const AFTER_RECONSTRUCT = 2;
	public const NEED_MAJOR_REPAIRS = 5;
	public const NEED_RECONSTRUCTION = 6;
	public const NEED_COSMETIC_REPAIR = 4;

	public static function labels(): array
	{
		return [
			self::NEW => 'Новое',
			self::WORKING => 'Рабочее',
			self::AFTER_RECONSTRUCT => 'После реконструкции',
			self::NEED_MAJOR_REPAIRS => 'Требуется капремонт',
			self::NEED_RECONSTRUCTION => 'Требуется реконструкция',
			self::NEED_COSMETIC_REPAIR => 'Требуется косметический ремонт',
		];
	}
}