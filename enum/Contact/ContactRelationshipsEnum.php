<?php
/**
 * Словарь Взаимоотношения для контакта
 */

namespace app\enum\Contact;

use app\enum\AbstractEnum;

class ContactRelationshipsEnum extends AbstractEnum
{
	public const TRUSTING_RELATIONSHIP  = 1;
	public const FACE_TO_FACE_MEETING = 2;
	public const REINFORCED_BY_BONUS = 3;
	public const ATTENTION = 4;

	public static function labels(): array
	{
		return [
			self::TRUSTING_RELATIONSHIP  => 'Доверительные взаимоотношения',
			self::FACE_TO_FACE_MEETING => 'Очная встреча',
			self::REINFORCED_BY_BONUS => 'Подкреплен бонусом',
			self::ATTENTION => 'Внимание',
		];
	}
}
