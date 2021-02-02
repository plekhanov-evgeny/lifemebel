<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\User;
global $USER;
$request = Application::getInstance()->getContext()->getRequest();

// Наследуемся от класса для увеличения времени жизни кода СМС
class ExtendUser extends CUser {
	const PHONE_CODE_OTP_INTERVAL = 180;
}

if (!empty($request["phone"]) && empty($request["sms_code"])) {
	AuthSms::sendVerifyCode($request["phone"]);
}

if (!empty($request["phone"]) && !empty($request["sms_code"])) {
	AuthSms::checkVerifyCode($request["phone"], $request["sms_code"], $USER);
}

class AuthSms
{
	public static function sendVerifyCode($phone) {
		$phone = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($phone);

		$phoneRecord = self::userProperty($phone);

		if (!$phoneRecord) {
			self::registerUser($phone);
			return 0;
		}

		list($code, $phoneNumber) = ExtendUser::GeneratePhoneCode($phoneRecord->getUserId());

		echo 'true';

		self::sendSMSEvent($phoneNumber, $code, 'SMS_USER_CONFIRM_NUMBER');
	}
	public static function checkVerifyCode($phone, $sms_code, $USER) {

		$phone = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($phone);

		$phoneRecord = self::userProperty($phone);

		if (!$phoneRecord) {
			echo 'Пользователь не найден';
			return 0;
		}

		if (ExtendUser::VerifyPhoneCode($phoneRecord->getPhoneNumber(), $sms_code)) {
			if ($phoneRecord->getUser()->getActive()) {
				$USER->Authorize($phoneRecord->getUserId());
				echo 'auth';
				return true;
			}
		} else {
			echo 'Введен не правильный код подтверждения!';
		}
	}
	public static function registerUser($phone) {
		$user = new ExtendUser;

		$pwd = time().'action';
		$arFields = array(
			"LOGIN" => ltrim($phone, '+'),
			"PHONE_NUMBER" => $phone,
			"PERSONAL_PHONE" => $phone,
			"LID" => SITE_ID,
			"ACTIVE" => "Y",
			"GROUP_ID" => array(3, 4),
			"PASSWORD" => $pwd,
			"CONFIRM_PASSWORD" => $pwd
		);
		$ID = $user->Add($arFields);
		if (intval($ID) > 0) {
			self::sendVerifyCode($phone);
		} else {
			echo '<div class="has-error"><span class="help-block with-errors"><ul class="list-unstyled"><li>' . $user->LAST_ERROR . '</li></ul></span></div>';
		}
	}

	private static function sendSMSEvent($phoneNumber, $code, $event) {
		$sms = new \Bitrix\Main\Sms\Event(
			$event, // SMS_USER_RESTORE_PASSWORD - для восстановления; SMS_USER_CONFIRM_NUMBER - проверочный код
			[
				'USER_PHONE' => $phoneNumber,
				'CODE' => $code,
			]
		);
		$sms->send(true);

	}

	private static function userProperty($phone) {
		$phoneRecord = \Bitrix\Main\UserPhoneAuthTable::getList([
			'filter' => [
				'=PHONE_NUMBER' => $phone
			],
			'select' => ['USER_ID', 'PHONE_NUMBER', 'USER.ID', 'USER.ACTIVE'],
		])->fetchObject();

//		if ($phoneRecord["ATTEMPTS"] > 3)
		return $phoneRecord;
	}
}
