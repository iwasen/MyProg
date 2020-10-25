<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:似顔絵情報管理｜似顔絵受付リスト
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// 優先度選択
function select_priority($selected) {
		echo '<option ', value_selected('', $selected), '>通常</option>', "\n";
		echo '<option ', value_selected(1, $selected), '>優先する</option>', "\n";
}

// 写真画像URL取得
$ur_photo = get_system_info('url_photo');

// 似顔絵URL取得
$ur_nigaoe = get_system_info('url_nigaoe');

// メイン処理
set_global('nigaoe', '似顔絵情報管理', '似顔絵受付リスト', BACK_TOP);
//to_char(nd_nouki_date - now(),'dd日 HH24時間MI分')

$sql = "SELECT * , to_char(nd_appl_date-nd_accept_date, 'dd日 HH24：MI') AS appl, to_char(nd_photo_date-nd_appl_date, 'dd日 HH24：MI') AS photo"
	.", to_char(nd_assign_date-nd_photo_date, 'dd日 HH24：MI') AS assign, to_char(nd_creation_date-nd_assign_date, 'dd日 HH24：MI') AS creation"
	. ", to_char(nd_completion_date-nd_creation_date, 'dd日 HH24：MI') AS completion, to_char(nd_dl_mail_date-nd_completion_date, 'dd日 HH24：MI') AS dl_mail"
	. ", to_char(nd_download_date-nd_dl_mail_date, 'dd日 HH24：MI') AS download"
	. " FROM t_nigaoe_data WHERE nd_nigaoe_id = $nd_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("所属名IDが不正", __FILE__);
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
function open_photo() {
	window.open("<?=$ur_photo?><?=$fetch->nd_photo_file?>", "_blank", "width=300,height=300,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
}

function onSubmit_form1(f) {
	if (f.nd_random_id.value == "") {
		alert("申込みIDを入力してください。");
		f.nd_random_id.focus();
		return false;
	}
	return confirm("似顔絵情報を更新します。よろしいですか？");
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
		<td class="m0" width=30%>■更新する似顔絵情報を入力してください。</td>
		<td align="right" width=70%><input type="button" value="　戻る　" onclick="history.back()"></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m1" width=30%>課金サーバID<?=MUST_ITEM?></td>
		<td class="n1" width=70%>
			<?=$fetch->nd_server_id?>
		</td>
	</tr>
	<tr>
		<td class="m1">キャンペーンコード<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="nd_campaign_cd"><?=select_campaign('', $fetch->nd_campaign_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">商品コード<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="nd_product_cd"><?=select_product('', $fetch->nd_product_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">優先度</td>
		<td class="n1">
			<select name="nd_priority"><?=select_priority($fetch->nd_priority)?></select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m2" width=30%>申込みID<?=MUST_ITEM?></td>
		<td class="n1" width=70%>
			<input class="alpha" type="text" name="nd_random_id" size=50 <?=value($fetch->nd_random_id)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">メールアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="nd_mail_addr" size=50 <?=value($fetch->nd_mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">受信メール本文</td>
		<td class="n1">
			<textarea rows=3 cols=50 disabled><?=htmlspecialchars($fetch->nd_mail_body)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m2">性別</td>
		<td class="n1">
			<select name="nd_sex_code"><?=select_sex('', $fetch->nd_sex_code)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">年齢</td>
		<td class="n1">
			<input type="text" name="nd_age" size=6 maxlength=3 <?=value($fetch->nd_age)?>>才
		</td>
	</tr>
	<tr>
		<td class="m2">画風</td>
		<td class="n1">
			<select name="nd_brushwork"><?=select_brushwork('未設定', $fetch->nd_brushwork)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">ニックネーム</td>
		<td class="n1">
			<input type="text" size=60 maxlength=40 name="nd_name_text" <?=value($fetch->nd_name_text)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">写真ファイル名</td><td class="n1"><?=$fetch->nd_photo_file != '' ? $fetch->nd_photo_file : '未受付'?></td>
	</tr>
	<tr>
		<td class="m2">依頼写真</td>
		<td class="n1">
<?
if ($fetch->nd_photo_file) {
?>
			<a href="javascript:open_photo()"><img src="<?=$ur_photo?><?=$fetch->nd_photo_file?>" alt="<?=$random_id?>" border=0 width=160></a>
<?
} else {
?>
			未受付
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m2">似顔絵ファイル名</td><td class="n1"><?=$fetch->nd_nigaoe_file != '' ? $fetch->nd_nigaoe_file : '未作成'?></td>
	</tr>
	<tr>
		<td class="m2">似顔絵画像</td>
		<td class="n1">
<?
if ($fetch->nd_nigaoe_file) {
?>
			<img src="<?=$ur_nigaoe?><?=$fetch->nd_nigaoe_file?>" alt="<?=$random_id?>" width=160>
<?
} else {
?>
			未作成
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m2">担当作画スタッフ</td>
		<td class"n1">
			<select name="nd_staff_id"><?=select_staff('未設定', $fetch->nd_staff_id)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">ステータス</td>
		<td class"n1">
			<select name="nd_status"><?=select_status('', $fetch->nd_status)?></select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m3" width=30%>納期</td>
		<td class="n1" width=70%>
			<select name="nd_nouki_date_y"><? select_year('2003', '', get_datepart('Y', $fetch->nd_nouki_date)) ?></select>年
			<select name="nd_nouki_date_m"><? select_month('', get_datepart('M', $fetch->nd_nouki_date)) ?></select>月
			<select name="nd_nouki_date_d"><? select_day('', get_datepart('D', $fetch->nd_nouki_date)) ?></select>日&nbsp;
			<select name="nd_nouki_date_h"><? select_hour('', get_datepart('h', $fetch->nd_nouki_date)) ?></select>時
			<select name="nd_nouki_date_mi"><? select_minute('', get_datepart('m', $fetch->nd_nouki_date)) ?></select>分
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m3" width=30%>決済サーバからの受付日時</td>
		<td class="n1" width=70%><?=format_datetime($fetch->nd_accept_date)?>
		</td>
	</tr>
	<tr>
		<td class="m3">ユーザからの申込み日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_appl_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->appl != '' ? "[経過時間：$fetch->appl]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">ユーザからの写真受信日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_photo_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->photo != '' ? "[経過時間：$fetch->photo]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">作画スタッフ割当日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_assign_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->assign != '' ? "[経過時間：$fetch->assign]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">似顔絵作成開始日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_creation_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->creation != '' ? "[経過時間：$fetch->creation]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">似顔絵完成日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_completion_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->completion != '' ? "[経過時間：$fetch->completion]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">ユーザ通知日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_dl_mail_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->dl_mail != '' ? "[経過時間：$fetch->dl_mail]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">似顔絵ダウンロード日時</td>
		<td class="n1"><?=format_datetime($fetch->nd_download_date,$def='未定')?>&nbsp;&nbsp;<?=$fetch->download != '' ? "[経過時間：$fetch->download]":''?>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="nd_id" <?=value($nd_id)?>>
<?
if ($fetch->nd_status != 10) {
	print "<input type='submit' value='　更新　'>\n";
}
?>
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
