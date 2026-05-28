<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferProjectStageEnum extends AbstractEnum
{
	public const INITIAL_PRE_PROJECT = 1;
	public const GPRU = 2;
	public const PROJECT_DEVELOPED = 3;
	public const AGR_AGO = 4;
	public const EXAMINATION = 5;
	public const RNS = 6;
	public const UNFINISHED_CONSTRUCTION = 7;

	public static function labels(): array
	{
		return [
			self::INITIAL_PRE_PROJECT => 'Начальная/Предпроект',
			self::GPRU => 'ГПЗУ',
			self::PROJECT_DEVELOPED => 'Разработан проект',
			self::AGR_AGO => 'АГР/АГО',
			self::EXAMINATION => 'Экспертиза',
			self::RNS => 'РнС',
			self::UNFINISHED_CONSTRUCTION => 'Незавершенное строительство',
		];
	}
}
