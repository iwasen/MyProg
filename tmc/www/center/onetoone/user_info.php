<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・ユーザ情報表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$user_id = $_GET['user_id'];
$setting = $_GET['setting'];

// 処理
switch ($_GET['action']) {
case 1:		// 配信状況ステータス変更
	$rec['ust_rel_mail_vol'] = "overlay(COALESCE(ust_rel_mail_vol,repeat('0'," . MAX_RELATION_MAIL . ")) placing '{$_GET['status']}' from {$_GET['vol_no']} for 1)";
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
case 2:		// 配信再開
	$rec['ust_rel_stop_flag'] = 'false';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
case 3:		// 配信停止
	$rec['ust_rel_stop_flag'] = 'true';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");
	break;
}

// ユーザIDが無ければ何も表示しない
if ($user_id) {
	$sql = "SELECT ups_name_kanji,umg_car_model_cd,ust_status,ust_follow_mail_vol,ust_rel_mail_vol,ust_rel_stop_flag,urg_handover_date,date_part('day',current_date-urg_follow_date) AS day_num"
			. " FROM t_user_personal"
			. " JOIN t_user_manage ON umg_user_id=ups_user_id"
			. " JOIN t_user_status ON ust_user_id=ups_user_id"
			. " JOIN t_user_regist ON urg_user_id=ups_user_id"
			. " WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$name_kanji = $fetch->ups_name_kanji;
		$car_model_cd = $fetch->umg_car_model_cd;
		$status = $fetch->ust_status;
		$follow_mail_vol = $fetch->ust_follow_mail_vol;
		$rel_mail_vol = $fetch->ust_rel_mail_vol;
		$rel_stop_flag = $fetch->ust_rel_stop_flag;
		$handover_date = $fetch->urg_handover_date;
		$day_num = $fetch->day_num;
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
function onclick_follow(vol_no) {
	window.open("show_mail.php?mail=follow&user_id=<?=$user_id?>&vol_no=" + vol_no, "_blank", "width=600,scrollbars=yes,resizable=yes");
}
function onclick_theme(vol_no) {
	window.open("show_mail.php?mail=relation&user_id=<?=$user_id?>&vol_no=" + vol_no, "_blank", "width=600,scrollbars=yes,resizable=yes");
}
function onclick_change0(vol_no) {
	if (confirm("Vol." + vol_no + "の配信状況を未配信に変更しますか？"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=1&vol_no=" + vol_no + "&status=0" + "&setting=1";
}
function onclick_change2(vol_no) {
	if (confirm("Vol." + vol_no + "の配信状況を配信済み(OneToOne)に変更しますか？"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=1&vol_no=" + vol_no + "&status=2" + "&setting=1";
}
function onclick_restart() {
	if (confirm("メールを配信再開しますか？"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=2" + "&setting=1";
}
function onclick_stop() {
	if (confirm("メールを配信停止しますか？"))
		location.href = "user_info.php?user_id=<?=$user_id?>&action=3" + "&setting=1";
}
function onclick_hot() {
	if (confirm("このユーザの引継ぎ処理を行いますか？"))
		window.open("result_input.php?user_id=<?=$user_id?>");
}
function onload_body() {
<?
if ($_GET['action'] == 2 || $_GET['action'] == 3) {
?>
	parent.mail.location.reload();
<?
}
?>
}
//-->
</script>
</head>
<body bgcolor="aliceblue" class="small" style="margin:5" onload="onload_body()">
<?
if ($user_id) {
?>
<table class="small" width="100%" style="border:outset;font-weight:bold;background-color:lightgrey">
	<tr>
		<td align="center"><?=htmlspecialchars($name_kanji)?>さんの情報</td>
	</tr>
</table>
<br>
<a href="../user/info.php?user_id=<?=$user_id?>" target="_blank">詳細登録情報表示</a><br>
<span class="note">（別ウィンドウで表示されます）</span>
<br>
<?
	if ($day_num && !$handover_date) {
?>
<br>
<font class="small" color="#444444">【初回配信後<?=(int)($day_num / 7)?>週経過】</font><br>
<?
	}
	if ($setting) {
?>
<br>
<font color="darkblue">■引継ぎ</font><br>
<?
		if (!$handover_date) {
?>
<span class="note">この方が「HOT」ステータスになった場合、下の「引継ぎ」ボタンを押してください。</span>
<?
	 } else {
?>
<span class="note">この方はすでに引継ぎを行っています。</span>
<?
		}
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%" class="small">
	<tr>
		<td align="center"><input type="button" name="hot" value="　引継ぎ　" onclick="onclick_hot()" <?=disabled($handover_date)?>></td>
	</tr>
</table>
<?
	}
?>
<br><br>
<font color="darkblue">■初期フォローメール配信状況</font><br>
<span class="note">各Subjectのリンクをクリックすると配信されるメールコンテンツが表示されます。</span>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr bgcolor="lightblue">
		<th>Vol</th>
		<th>Subject</th>
		<th>配信状況</th>
	</tr>
<?
	// 初期フォローメールの件名取得
	$sql = "SELECT fls_vol_no,fls_subject FROM t_follow_subject WHERE fls_car_model_cd=$car_model_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$follow_subject[(int)$fetch->fls_vol_no] = $fetch->fls_subject;
	}

	// 初期フォローメール配信状況表示
	for ($i = 0; $i < 4; $i++) {
		$vol_no = $i + 1;

		// 初期フォローメール配信状況取得
		if ($i < $follow_mail_vol)
			$st = '<font color="blue">済</font>';
		else
			$st = '<font color="red">未</font>';
?>
	<tr>
		<td align="right"><?=$vol_no?></td>
		<td><a href="javascript:onclick_follow(<?=$vol_no?>)"><?=htmlspecialchars($follow_subject[$vol_no])?></a></td>
		<td align="center"><?=$st?></td>
	</tr>
<?
	}
?>
</table>
<br><br>
<font color="darkblue">■継続リレーションメール配信状況</font><br>
<span class="note">各テーマのリンクをクリックすると配信されるメールコンテンツが表示されます。</span>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr bgcolor="lightgreen">
		<th>Vol</th>
		<th>テーマ</th>
		<th>配信状況</th>
	</tr>
<?
	$sql = "SELECT rlt_vol_no,rlt_theme"
			. " FROM t_relation_theme"
			. " WHERE rlt_car_category_cd=(SELECT crm_car_category_cd FROM t_car_model WHERE crm_car_model_cd=$car_model_cd)"
			. " ORDER BY rlt_vol_no";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$vol_no = $fetch->rlt_vol_no;

		// 継続リレーションメール配信状況取得
		switch ($rel_mail_vol{$vol_no - 1}) {
		case 1:
			$st = '<font color="blue">済</font>';
			break;
		case 2:
			$st = '<font color="green"><nobr>済(1to1)</nobr></font>';
			if ($setting)
				$st .= '<br><input type="button" value="変更" style="height:19" onclick="onclick_change0(' . $vol_no . ')">';
			break;
		default:
			$st = '<font color="red">未</font>';
			if ($setting)
				$st .= '<input type="button" value="変更" style="height:19" onclick="onclick_change2(' . $vol_no . ')">';
			break;
		}
?>
	<tr>
		<td align="right"><?=$vol_no?></td>
		<td><a href="javascript:onclick_theme(<?=$vol_no?>)"><?=htmlspecialchars($fetch->rlt_theme)?></a></td>
		<td align="center"><?=$st?></td>
	</tr>
<?
	}
?>
</table>
<br><br>
<?
	switch ($status) {
	case 1:
		if ($rel_stop_flag == DBFALSE)
			$st = '初期フォローメール配信中';
		else
			$st = '初期フォローメール一時停止中';
		break;
	case 2:
		if ($rel_stop_flag == DBFALSE)
			$st = '継続リレーションメール配信中';
		else
			$st = '継続リレーションメール一時停止中';
		break;
	case 3:
		$st = '引継ぎ済み';
		break;
	case 7:
		$st = 'メール不達';
		break;
	case 8:
		$st = 'メール購読解除';
		break;
	case 9:
		$st = '終了済み';
		break;
	default:
		$st = '不明';
		break;
	}
?>
<font color="darkblue">■メール配信ステータス</font>
<table border=0 cellspacing=0 cellpadding=1 width="100%" class="small">
	<tr>
		<td><font color="darkred">【<?=$st?>】</font></td>
	</tr>
	<tr>
<?
	if ($setting && ($status == 1 || $status == 2)) {
		if ($rel_stop_flag == DBTRUE) {
?>
		<td align="center"><input type="button" value="配信再開" onclick="onclick_restart()"></td>
<?
		} else {
?>
		<td align="center"><input type="button" value="配信停止" onclick="onclick_stop()"></td>
<?
		}
	}
?>
	</tr>
</table>
<?
}
?>
</body>
</html>
