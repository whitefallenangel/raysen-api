<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsWaterSupplyEnum extends AbstractEnum
{
	public const INDIVIDUAL_WELL = 1;
	public const INDIVIDUAL_OTHER = 2;
	public const CENTRAL_RECEIVED_TU = 3;
	public const CENTRAL_PAID_TU = 4;
	public const CENTRAL_COMPLETED_TU = 5;
	public const NONE = 6;

	public static function labels(): array
	{
		return [
			self::INDIVIDUAL_WELL => 'Индивидуальное (скважина)',
			self::INDIVIDUAL_OTHER => 'Индивидуальное (иное)',
			self::CENTRAL_RECEIVED_TU => 'Центральное/Получены ТУ',
			self::CENTRAL_PAID_TU => 'Центральное/Оплачены ТУ',
			self::CENTRAL_COMPLETED_TU => 'Центральное/Выполнены ТУ',
			self::NONE => 'Нет',
		];
	}
}