<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if (!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

unset($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$pagerParameters = $GLOBALS[$arParams["PAGER_PARAMS_NAME"]];
if (!is_array($pagerParameters)) {
	$pagerParameters = [];
}

$arNavParams = array(
	"nPageSize" => '10',
	"bDescPageNumbering" => 'Описание',
	"bShowAll" => 'Y',
);
$arNavigation = CDBResult::GetNavParams($arNavParams);
if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
	$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];

$cacheId = $arParams["IBLOCK_ID"] . $USER->GetUserGroupString();

if ($this->StartResultCache(false, [($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation, $cacheId])) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arFilter = [
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => $arParams['CHECK_PERMISSIONS'] ? "Y" : "N",
	];
	$arSelect = [
		"ID",
		"NAME",
		"IBLOCK_ID",
		"ACTIVE",
	];

	$dbItems = CIBlockElement::GetList(["SORT" => "ASC", "ID" => "ASC"], $arFilter, false, $arNavParams, $arSelect);
	while ($arItem = $dbItems->GetNext()) {
		$arResult["ITEMS"][] = $arItem;
	}

	$arResult["NAV_STRING"] = $dbItems->GetPageNavStringEx($navComponentObject, 'Заголовок', '', 'Y');

	// Передаем поля, которые надо закешировать
	$this->SetResultCacheKeys([
		"ID",
		"NAME",
		"IBLOCK_ID",
		"ACTIVE",
	]);
	$this->IncludeComponentTemplate();
}