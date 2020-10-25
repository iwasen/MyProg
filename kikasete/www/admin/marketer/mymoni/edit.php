<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙモニター変更処理
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
include("$inc/my_monitor.php");

// モニター実施状態選択肢
function select_moni_status($sel) {
	if ($sel <= 2) {
		echo "<option ", value_selected('0', $sel), ">申請中</option>";
		echo "<option ", value_selected('1', $sel), ">事務局チェック中</option>";
		echo "<option ", value_selected('2', $sel), ">承認済み</option>";
	} else {
		echo "<option ", value_selected('3', $sel), ">実施中</option>";
		echo "<option ", value_selected('4', $sel), ">一時停止</option>";
		echo "<option ", value_selected('5', $sel), ">終了</option>";
	}
	echo "<option ", value_selected('9', $sel), ">削除済み</option>";
}

$mymoni = new my_monitor_class;
$mymoni->read_db($room_id);

set_global('marketer', 'マーケター管理｜Ｍｙモニター管理', 'Ｍｙモニター情報変更', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("このＭｙモニターを更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■設定内容</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ルーム名</td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="room_name" <?=value($mymoni->room_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">開始日</td>
		<td class="n1">
			<input class="number" type="text" name="start_date_y" size=5 <?=value($mymoni->start_date_y)?>>年
			<input class="number" type="text" name="start_date_m" size=3 <?=value($mymoni->start_date_m)?>>月
			<input class="number" type="text" name="start_date_d" size=3 <?=value($mymoni->start_date_d)?>>日
		</td>
	</tr>
	<tr>
		<td class="m1">実施期間</td>
		<td class="n1">
			<input class="number" type="text" name="period" size=4 <?=value($mymoni->period)?>>ヶ月
		</td>
	</tr>
	<tr>
		<td class="m1">メンバー条件</td>
		<td class="n1">
			<textarea class="kanji" name="condition" cols="50" rows="8"><?=htmlspecialchars($mymoni->condition)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">状態</td>
		<td class="n1">
			<select class="kanji" name="status"><? select_moni_status($mymoni->status) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">開始通知メール</td>
		<td class="n1">
			<textarea class="kanji" name="start_mail" cols="80" rows="10"><?=htmlspecialchars($mymoni->start_mail)?></textarea>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="room_id" value="<?=$room_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
