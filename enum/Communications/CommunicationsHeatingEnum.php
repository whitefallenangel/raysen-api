<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsHeatingEnum extends AbstractEnum
{
	public const NONE = 1;
	public const INDIVIDUAL = 2;
	public const RECEIVED_TU = 3;
	public const PAID_TU = 4;
	public const COMPLETED_TU = 5;

	public static function labels(): array
	{
		return [
			self::NONE => 'Нет',
			self::INDIVIDUAL => 'Индивидуальное',
			self::RECEIVED_TU => 'Центральное/Получены ТУ',
			self::PAID_TU => 'Центральное/Оплачены ТУ',
			self::COMPLETED_TU  => 'Центральное/Выполнены ТУ',
		];
	}
}