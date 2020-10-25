<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:マスタメンテナンスメニュー表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

$event = 'onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)" onMouseDown="onMouseDown(this)"';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onMouseOver(c) {
	c.style.color = "yellow";
}
function onMouseOut(c) {
	c.style.color = "white";
	c.style.border = "outset";
}
function onMouseDown(c) {
	c.style.border = "inset";
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス') ?>

<div align="center">
<table border=0 cellspacing=4 cellpadding=3 width="100%" class="small">
	<tr class="submenu">
		<td colspan=4>■マスタテーブル</td>
	</tr>
	<tr>
		<a href="system/list.php"><td width="25%" class="menuitem" <?=$event?>>システム設定情報マスタ</td></a>
		<a href="staff/list.php"><td width="25%" class="menuitem" <?=$event?>>事務局スタッフマスタ</td></a>
		<a href="user/list.php"><td width="25%" class="menuitem" <?=$event?>>ユーザ情報</td></a>
		<a href="cancel_result/list.php"><td width="25%" class="menuitem" <?=$event?>>メール購読解除理由マスタ</td></a>
	</tr>
	<tr>
		<a href="channel/list.php"><td width="25%" class="menuitem" <?=$event?>>販売チャネルマスタ</td></a>
		<a href="dealer/list.php"><td width="25%" class="menuitem" <?=$event?>>販売店マスタ</td></a>
		<a href="upload/list.php"><td width="25%" class="menuitem" <?=$event?>>アップロード担当マスタ</td></a>
		<td></td>
	</tr>
	<tr>
		<a href="car_category/list.php"><td width="25%" class="menuitem" <?=$event?>>車種カテゴリマスタ</td></a>
		<a href="car_model/list.php"><td width="25%" class="menuitem" <?=$event?>>車種マスタ</td></a>
		<a href="tantousha/list.php"><td width="25%" class="menuitem" <?=$event?>>担当者マスタ</td></a>
		<td></td>
	</tr>
	<tr>
		<a href="greeting/list.php"><td width="25%" class="menuitem" <?=$event?>>時節の挨拶文マスタ</td></a>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan=4><br></td>
	</tr>
	<tr class="submenu">
		<td colspan=4>■メールテンプレート</td>
	</tr>
	<tr>
		<a href="mail_template/edit.php?template_id=onetoone"><td width="25%" class="menuitem" <?=$event?>>OneToOneメールテンプレート</td></a>
		<a href="mail_template/edit.php?template_id=inquiry"><td width="25%" class="menuitem" <?=$event?>>問い合わせ回答テンプレート</td></a>
		<a href="dealermail/edit.php"><td width="25%" class="menuitem" <?=$event?>>販売店メールテンプレート</td></a>
		<td></td>
	</tr>
		<td colspan=4><br></td>
	</tr>
	<tr class="submenu">
		<td colspan=4>■初期フォローメール／継続リレーションメール</td>
	</tr>
	<tr>
		<a href="follow_mail/list.php"><td width="25%" class="menuitem" <?=$event?>>初期フォローメール登録</td></a>
		<a href="rel_mail/list.php"><td width="25%" class="menuitem" <?=$event?>>継続リレーションメール登録</td></a>
		<td></td>
		<td></td>
	</tr>
	</tr>
		<td colspan=4><br></td>
	<tr class="submenu">
		<td colspan=4>■集計出力</td>
	</tr>
	<tr>
		<a href="follow_click/list.php"><td width="25%" class="menuitem" <?=$event?>>初期フォロー車種別クリック一覧</td></a>
		<a href="week_send/list.php"><td width="25%" class="menuitem" <?=$event?>>週別配信数集計</td></a>
		<td></td>
		<td></td>
	</tr>
</table>
</div>

<? center_footer() ?>

</body>
</html>
