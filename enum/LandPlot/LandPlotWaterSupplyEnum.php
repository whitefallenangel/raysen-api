<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotWaterSupplyEnum extends AbstractEnum
{
	public const NO = 1;
	public const INDIVIDUAL = 2;
	public const RECEIVED_TU = 3;
	public const PAID_TU = 4;
	public const COMPLETED_TU = 5;

	public static function labels(): array
	{
		return [
			self::NO => 'Нет',
			self::INDIVIDUAL => 'Индивидуальное',
			self::RECEIVED_TU => 'Получены ТУ',
			self::PAID_TU => 'Оплачены ТУ',
			self::COMPLETED_TU  => 'Центральное/Выполнены ТУ',
		];
	}
}