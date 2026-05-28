<?php

namespace app\enum\Railway;

use app\enum\AbstractEnum;

class RailwayTypeEnum extends AbstractEnum
{
	public const PASSING = 1;
	public const DEAD_END = 2;

	public static function labels(): array
	{
		return [
			self::PASSING => 'Проходящая',
			self::DEAD_END => 'Тупиковая',
		];
	}
}