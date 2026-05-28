<?php
/**
 * Словарь освещения для объекта
 */

namespace app\enum\Communications;

use app\enum\AbstractEnum;

class CommunicationsLightingEnum extends AbstractEnum
{
	public const LED = 3;
	public const INCANDESCENT = 1;
	public const LIGHT_WELLS = 2;
	public const PANORAMIC_WINDOWS = 4;
	public const PERIMETER_WINDOWS = 5;
	public const NONE = 6;

	public static function labels(): array
	{
		return [
			self::LED => 'Светодиодное',
			self::INCANDESCENT => 'Лампы накаливания',
			self::LIGHT_WELLS => 'Световые колодцы',
			self::PANORAMIC_WINDOWS => 'Панорамные окна',
			self::PERIMETER_WINDOWS => 'Окна по периметру',
			self::NONE => 'Отсутствует',
		];
	}
}