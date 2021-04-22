<?php
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
use Bitrix\Main\Security\Random;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Create export user");
?>


<?php
$user = new CUser();

$exampleUser = [
	['customer_id' => '347','customer_group_id' => '1','store_id' => '0','language_id' => '2','firstname' => 'Яна ','lastname' => 'Комкова','gender' => '2','email' => 'Diamondnails@ya.ru','telephone' => '+7 (903) 514-08-85','fax' => '','password' => '28d2f35741b8b0d80e09ea075ec9c66c23160206','salt' => 'tPekCzY9S','cart' => NULL,'wishlist' => NULL,'newsletter' => '0','address_id' => '0','custom_field' => '','ip' => '91.224.132.213','status' => '1','approved' => '1','safe' => '0','token' => '','code' => '','date_added' => '2017-03-15 22:01:02']
];

foreach ($exampleUser as $userOpenCart) {
	switch ($userOpenCart['customer_group_id']) {
		case 2:
			$userGroup = [2,3,4,6,12];
			break;
		case 3:
			$userGroup = [2,3,4,6,11];
			break;
		case 4:
			$userGroup = [2,3,4,6,10];
			break;
		case 5:
			$userGroup = [2,3,4,6,9];
			break;
		default:
			$userGroup = [2,3,4,6];
			break;
	}

	switch ($userOpenCart['language_id']) {
		case 0:
			$language = '';
			break;
		case 1:
			$language = '';
			break;
		default:
			$language = 'ru';
			break;
	}

	$password = Random::getString(10, true);
	if ($userOpenCart["telephone"]) {
		$phone = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($userOpenCart["telephone"], "ru");
	} else {
		$phone = '';
	}

	$isUserFind = false;

	$rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), array("PERSONAL_PHONE" => $phone));
	while($arUser = $rsUsers->fetch()) {
		$strToTime = strtotime($arUser["DATE_REGISTER"]);
		$strToTimeOC = strtotime($userOpenCart["date_added"]);
		if ($strToTime >= $strToTimeOC) {
			echo $strToTime . ' >= ' . $strToTimeOC;
			echo '<br>'.$arUser["ID"].' - phone<br>';
		} else {
			$fields = Array(
				"NAME"			=> trim($userOpenCart["firstname"]),
				"LAST_NAME"		=> trim($userOpenCart["lastname"]),
				"DATE_REGISTER"	=> date('d.m.Y H:i:s',strtotime($userOpenCart["date_added"])),
				"EMAIL"			=> trim($userOpenCart["email"]),
				"LOGIN"			=> trim($userOpenCart["email"]),
				"LID"			=> $language,
				"ACTIVE"			=> ($userOpenCart['status'] == 1)?'Y':'N',
				"GROUP_ID"		=> $userGroup
			);
			$user->Update($arUser["ID"], $fields);
			$strError .= $user->LAST_ERROR;
		}
		$isUserFind = true;
	}

	if ($isUserFind != true && $userOpenCart["email"]) {
		$rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), array("EMAIL" => trim($userOpenCart["email"])));
		while($arUser = $rsUsers->fetch()) {
			$strToTime = strtotime($arUser["DATE_REGISTER"]);
			$strToTimeOC = strtotime($userOpenCart["date_added"]);
			if ($strToTime >= $strToTimeOC) {
				echo $strToTime . ' >= ' . $strToTimeOC;
				echo '<br>'.$arUser["ID"].' - email<br>';
			} else {
				$fields = Array(
					"NAME"			=> trim($userOpenCart["firstname"]),
					"LAST_NAME"		=> trim($userOpenCart["lastname"]),
					"DATE_REGISTER"	=> date('d.m.Y H:i:s',strtotime($userOpenCart["date_added"])),
					"PERSONAL_PHONE"	=> $phone,
					"PHONE_NUMBER"		=> $phone,
					"LOGIN"			=> trim($userOpenCart["email"]),
					"LID"			=> $language,
					"ACTIVE"			=> ($userOpenCart['status'] == 1)?'Y':'N',
					"GROUP_ID"		=> $userGroup,
				);
				$user->Update($arUser["ID"], $fields);
				$strError .= $user->LAST_ERROR;
			}

			$isUserFind = true;
		}
	}

	if (!$isUserFind) {
		$arFields = [
			"NAME"			=> trim($userOpenCart["firstname"]),
			"LAST_NAME"		=> trim($userOpenCart["lastname"]),
			"DATE_REGISTER"	=> date('d.m.Y H:i:s',strtotime($userOpenCart["date_added"])),
			"EMAIL"			=> trim($userOpenCart["email"]),
			"PERSONAL_PHONE"	=> $phone,
			"PHONE_NUMBER"		=> $phone,
			"LOGIN"			=> trim($userOpenCart["email"]),
			"LID"			=> $language,
			"ACTIVE"			=> ($userOpenCart['status'] == 1)?'Y':'N',
			"GROUP_ID"		=> $userGroup,
			"PASSWORD"		=> $password,
			"CONFIRM_PASSWORD"	=> $password
		];

		$ID = $user->Add($arFields);
		if (intval($ID) > 0)
			echo "Пользователь успешно добавлен.".$ID.'<br>';
		else
			echo $user->LAST_ERROR.'<br><br>';
	}
}
?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
