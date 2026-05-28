<?php

namespace app\enum\LiftingDevices;

use app\enum\AbstractEnum;

class ElevatorLiftTypeEnum extends AbstractEnum
{
	public const LIFT = 1;
	public const FREIGHT_ELEVATOR = 2;
	public const HYDROPLATFORM = 3;
	public const PASSENGER_LIFT = 4;
	public const ESCALATOR = 5;
	public const TRAVELLER = 6;

	public static function labels(): array
	{
		return [
			self::LIFT => 'Подъемник',
			self::FREIGHT_ELEVATOR => 'Грузовой лифт',
			self::HYDROPLATFORM => 'Гидроплатформа',
			self::PASSENGER_LIFT => 'Пассажирский лифт',
			self::ESCALATOR => 'Эскалатор',
			self::TRAVELLER => 'Траволатор',
		];
	}
}
