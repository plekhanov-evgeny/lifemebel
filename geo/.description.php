<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentDescription = array(
	"NAME" => GetMessage("GEO_NAME"),
	"DESCRIPTION" => GetMessage("GEO_DESCRIPTION"),
	"ICON" => "",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "geo",
			"NAME" => GetMessage("GEO_NAME_CHILD"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "news_geo",
			),
		),
	),
);

?>
