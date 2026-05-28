<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingInRelatedPlotEnum extends AbstractEnum
{
	public const NOT_APPLICABLE = 1;
	public const CREATE_AND_ADD = 2;
	public const LINK_TO_EXISTING = 3;

	public static function labels(): array
	{
		return [
			self::NOT_APPLICABLE => 'Не применимо',
			self::CREATE_AND_ADD => 'Создать участок под объектом',
			self::LINK_TO_EXISTING => 'Привязать к существующему участку в базе',
		];
	}
}