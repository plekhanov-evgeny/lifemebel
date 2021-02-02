<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($_POST["element_delete"]) {
	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$idElement = $_POST["element_delete"];
	$idIblock = $_POST["IBLOCK_ID"];

	if (is_numeric($idElement)) {
		echo 'stat';
		if(CIBlock::GetPermission($idIblock)>='W')
		{
			$DB->StartTransaction();
			if(!CIBlockElement::Delete($idIblock)) {
				$DB->Rollback();
			} else {
				$DB->Commit();
			}
		}
	}
}