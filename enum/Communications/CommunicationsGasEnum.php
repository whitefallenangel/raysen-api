<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsGasEnum extends AbstractEnum
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
			self::RECEIVED_TU => 'Магистральный/Получены ТУ',
			self::PAID_TU => 'Магистральный/Оплачены ТУ',
			self::COMPLETED_TU  => 'Магистральный/Выполнены ТУ',
		];
	}
}