<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferTaxSystemEnum extends AbstractEnum
{
	public const TRIPLE_NET = 1;
	public const WITH_VAT_22 = 2;
	public const NO_VAT = 3;
	public const USN = 4;
	public const USN_AND_5_VAT = 5;
	public const USN_AND_7_VAT = 6;

	public static function labels(): array
	{
		return [
			self::TRIPLE_NET => 'TripleNET',
			self::WITH_VAT_22 => 'С НДС (22%)',
			self::NO_VAT => 'Без НДС',
			self::USN => 'УСН',
			self::USN_AND_5_VAT => 'УСН + 5% (НДС)',
			self::USN_AND_7_VAT => 'УСН + 7% (НДС)',
		];
	}
}