<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks = [];
$db_iblock = CIBlock::GetList(
	["SORT"=>"ASC"],
	["SITE_ID"=>$_REQUEST["site"]]
);
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("GEO_MODULE_TYPE_IBLOCK"),
			"TYPE" => "LIST",
			"VALUES"=>$arTypesEx,
			"DEFAULT" => "news",
			"MULTIPLE" => "N",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("GEO_MODULE_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"CACHE_TIME" => Array("DEFAULT"=>36000000),
	),
);