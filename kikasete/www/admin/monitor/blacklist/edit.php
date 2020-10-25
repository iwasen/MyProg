<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ブラックリスト管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('monitor', 'モニター管理', 'ブラックリスト管理', BACK_TOP);

$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2,mn_blacklist_flg"
		. " FROM t_monitor"
		. " WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('モニターＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return confirm("このブラックリストフラグを変更します。よろしいですか？");
	case "delete":
		return confirm("このモニターを退会させます。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■ブラックリストフラグ変更</td>
	</tr>
	<tr>
		<td class="m1" width="22%">名前</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->mn_name1)?> <?=htmlspecialchars($fetch->mn_name2)?>
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->mn_mail_addr)?>
		</td>
	</tr>
	<tr>
		<td class="m1">ブラックリストフラグ</td>
		<td class="n1">
			<select name="blacklist_flg"><? select_blacklist('ブラックリスト登録解除', $fetch->mn_blacklist_flg) ?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　変更　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　退会　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
