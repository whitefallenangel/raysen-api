<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotInfrastructureEnum extends AbstractEnum
{
	public const CANTEEN = 1;
	public const CAFE = 2;
	public const RESTAURANT = 3;
	public const CONFERENCE_ROOM = 4;
	public const BANK_BRANCH = 5;
	public const FITNESS_CENTER = 6;
	public const NOTARY_OFFICE = 7;
	public const DORMITORY = 8;
	public const NOT_AVAILABLE = 9;

	public static function labels(): array
	{
		return [
			self::CANTEEN => 'Столовая',
			self::CAFE => 'Кафе',
			self::RESTAURANT => 'Ресторан',
			self::CONFERENCE_ROOM => 'Конференц-зал',
			self::BANK_BRANCH => 'Отделение банка',
			self::FITNESS_CENTER => 'Фитнес-центр',
			self::NOTARY_OFFICE => 'Нотариальная контора',
			self::DORMITORY => 'Общежитие',
			self::NOT_AVAILABLE => 'Отсутствует',
		];
	}
}