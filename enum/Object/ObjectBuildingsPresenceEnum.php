<?php

namespace app\enum\Object;

use app\enum\AbstractEnum;

class ObjectBuildingsPresenceEnum extends AbstractEnum
{
	public const NONE = 1;
	public const DEMOLITION_REQUIRED = 2;
	public const RECONSTRUCTION_OR_REPAIRS = 3;

	public static function labels(): array
	{
		return [
			self::NONE => 'Отсутствуют',
			self::DEMOLITION_REQUIRED => 'Требуется снос',
			self::RECONSTRUCTION_OR_REPAIRS => 'Реконструкция/Кап.ремонт',
		];
	}
}
