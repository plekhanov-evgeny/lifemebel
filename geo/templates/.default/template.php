<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<?php foreach ($arResult["ITEMS"] as $arItem) { ?>
	<table>
		<tr>
			<td><?=$arItem["ID"]?></td>
			<td><?=$arItem["NAME"]?></td>
			<td>
				<span onclick="handler(<?=$arItem["ID"]?>, <?=$arItem["IBLOCK_ID"]?>)">
					<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<line x1="5" y1="5" x2="20" y2="20" stroke="red" stroke-width="2" />
						<line x1="20" y1="5" x2="5" y2="20" stroke="red" stroke-width="2" />
					</svg>
				</span>
			</td>
		</tr>
	</table>
<?php } ?>

<?='<p>'.$arResult["NAV_STRING"].'</p>';?>

<script>
	function handler(id, iblock_id) {
		var request = new XMLHttpRequest();
		request.open('GET',"<?=$templateFolder?>/ajax.php?element_delete="+id+"&IBLOCK_ID="+iblock_id,true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.addEventListener('readystatechange', function() {
			if ((request.readyState == 4) && (request.status == 200)) {
				alert('OK');
			}
		});
		request.send();
	}
</script>
