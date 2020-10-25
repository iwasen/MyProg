<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:最新情報表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");

// 連絡事項
function renraku_jikou($privilege) {
	global $g_staff_id;

	$sql = "SELECT msg_message_id,msg_regist_date,msg_title,msg_message,msr_message_id" .
				" FROM t_message LEFT JOIN t_message_read ON msr_message_id=msg_message_id AND msr_staff_id=$g_staff_id" .
				" WHERE msg_privilege='$privilege' AND (msg_to_staff_id=$g_staff_id OR msg_to_staff_id is null) AND (msg_start_date is null OR msg_start_date<=CURRENT_TIMESTAMP) AND (msg_end_date is null OR msg_end_date>CURRENT_TIMESTAMP)" .
				" ORDER BY msg_message_id desc";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow == 0)
		echo "<span class='disabled'>連絡事項はありません。</span>\n";
	else {
		echo "<table  border=0 cellspacing=0 cellpadding=1 width='100%'>\n";
		for ($i = 0; $i < 10 && $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo "<tr bgcolor='#ffcccc'>\n";
			echo "<td>■", htmlspecialchars($fetch->msg_title), "</td>\n";
			echo "<td align='right'><nobr>", format_datetime($fetch->msg_regist_date), "</nobr></td>\n";
			echo "<td align='right' width=45>\n";
			if (!$fetch->msr_message_id)
				echo "<input type='button' value='確認' onclick='location.href=\"msg_read.php?message_id=$fetch->msg_message_id\"' title='連絡事項を確認したらクリックしてください'>\n";
			echo "</td>\n";
			echo "</tr><tr>\n";
			echo "<td colspan=2><pre>$fetch->msg_message</pre></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

// 未処理問い合わせ数
function toiawase1($thread_type, $link) {
	$sql = "SELECT count(*) AS total,count(case when cml_date+interval '1 day'<current_timestamp then 1 end) AS over24h"
			. " FROM t_comm_log"
			. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
			. " WHERE cml_answer_flag=true AND cmt_thread_type=$thread_type AND cmt_status=1 AND cmt_staff_id is null";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$total = 0;
		$over24h = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$total = $fetch->total;
		$over24h = $fetch->over24h;
	}

	echo '総件数：', show_num($total, $link), '　24時間以上経過の件数：', show_num($over24h);
}

// 対応中問い合わせ数
function toiawase2($thread_type, $link) {
	global $g_staff_id;

	$sql = "SELECT count(*) AS total,count(case when cmt_staff_id=$g_staff_id then 1 end) AS tantou"
			. " FROM t_comm_log"
			. " JOIN t_comm_thread ON cmt_comm_id=cml_thread_comm_id"
			. " WHERE cml_answer_flag=true AND cmt_thread_type=$thread_type AND cmt_status=1 AND cmt_staff_id is not null";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$total = 0;
		$tantou = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$total = $fetch->total;
		$tantou = $fetch->tantou;
	}

	echo '総件数：', show_num($total, $link), '　担当分件数：', show_num($tantou);

	echo '　<input type="button" value="オペレータ別件数"', $total ? '' : ' disabled', ' onclick="onClick_response(' . $thread_type . ')">';
}

// 1週間以上反応のないユーザ数
function toiawase3($mail_type, $link) {
	$sql = "SELECT COUNT(*) FROM t_mail_reply WHERE mrp_date<current_timestamp";
	$num = db_fetch1($sql);

	echo 'ユーザ数：', show_num($num, $link);
}

// 数値表示（０以外は赤で表示）
function show_num($num, $link = '') {
	if ($num == 0)
		return number_format($num);
	else {
		$s = '<font color="red"><b>' . number_format($num) . '</b></font>';
		if ($link != '')
			$s = "<a href='$link'>$s</a>";
		return $s;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onClick_response(thread_type) {
	window.open("response.php?thread_type=" + thread_type, "response", "width=500,height=500,resizable=yes,scrollbars=yes");
}
//-->
</script>
</head>
<body>

<? center_header('最新情報表示') ?>

<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">■連絡事項</td>
		<td class="m0" align="right"><input type="button" value="過去のメッセージ" onclick="location.href='msg_list.php'"></td>
	</tr>
	<tr>
		<td class="m1" width="22%">DHスタッフからの連絡事項</td>
		<td class="n1"><? renraku_jikou(PRIV_DHSTAFF) ?></td>
	</tr>
	<tr>
		<td class="m1">オペレータからの連絡事項</td>
		<td class="n1"><? renraku_jikou(PRIV_OPERATOR) ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■OneToOneメール対応状況</td>
	</tr>
	<tr>
		<td class="m1">未対応メール数</td>
		<td class="n1"><? toiawase1(1, "$top/onetoone/frame1.php?list=1") ?></td>
	</tr>
	<tr>
		<td class="m1">対応中メール数</td>
		<td class="n1"><? toiawase2(1, "$top/onetoone/frame1.php?list=2") ?></td>
	</tr>
	<tr>
		<td class="m1">1週間以上反応のないユーザ数</td>
		<td class="n1"><? toiawase3(1, "$top/onetoone/frame1.php?list=4") ?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■問い合わせ対応状況</td>
	</tr>
	<tr>
		<td class="m1">未対応問い合わせ数</td>
		<td class="n1"><? toiawase1(2, "$top/inquiry/list1.php") ?></td>
	</tr>
	<tr>
		<td class="m1">対応中問い合わせ数</td>
		<td class="n1"><? toiawase2(2, "$top/inquiry/list2.php") ?></td>
	</tr>
</table>

<br>
<div align="center">
<input type="button" value="最新表示" onclick="location.reload()">
</div>

<? center_footer() ?>

</body>
</html>
