<?php

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsElectricityEnum extends AbstractEnum
{
	public const NO = 1;
	public const RECEIVED_TU = 2;
	public const PAID_TU = 3;
	public const COMPLETED_TU = 4;

	public static function labels(): array
	{
		return [
			self::NO => 'Отсутствует',
			self::RECEIVED_TU => 'Отсутствует/Получены ТУ',
			self::PAID_TU => 'Отсутствует/Оплачены ТУ',
			self::COMPLETED_TU  => 'Есть/Выполнены ТУ',
		];
	}
}