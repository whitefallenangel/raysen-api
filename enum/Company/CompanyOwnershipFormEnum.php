<?php

namespace app\enum\Company;

use app\enum\AbstractEnum;

class CompanyOwnershipFormEnum extends AbstractEnum
{
	public const STATE  = 1;
	public const MUNICIPAL = 2;
	public const PRIVATE = 3;
	public const MIXED = 4;
	public const UNKNOWN = 5;

	public static function labels(): array
	{
		return [
			self::STATE => 'Государственная',
			self::MUNICIPAL  => 'Муниципальная',
			self::PRIVATE => 'Частная',
			self::MIXED => 'Смешанная',
			self::UNKNOWN => 'Неизвестно',
		];
	}
}