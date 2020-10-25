<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:ログイン画面処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/dealer.php");

// 初期フォローメール送信状況表示
function disp_follow_mail($follow_mail_vol, $vol_no) {
	echo $follow_mail_vol >= $vol_no ? '○' : '&nbsp;';
}

// 継続リレーションメール送信状況表示
function disp_relation_mail($rel_mail_vol, $vol_no) {
	switch ($rel_mail_vol{$vol_no - 1}) {
	case '1':
		echo '○';
		break;
	case '2':
		echo '済';
		break;
	default:
		echo '&nbsp;';
		break;
	}
}

// 入力パラメータ
$car_model_cd = $_GET['car_model_cd'];
$st = $_GET['st'];

// 車種名取得
if ($car_model_cd != '') {
	$sql = "SELECT crm_car_model_name FROM t_car_model WHERE crm_car_model_cd=$car_model_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result) != 0)
		$name = pg_fetch_result($result, 0, 0) . '：';

	and_join($where, "umg_car_model_cd=$car_model_cd");
}

switch ($st) {
case 1:
	$name .= 'フォロー実施中';
	and_join($where, "ust_status<=2");
	break;
case 2:
	$name .= '引継ぎ済み（合計）';
	and_join($where, "ust_status>=3 AND urs_rank IN ('S','A','B','J')");
	break;
case 3:
	$name .= '引継ぎ済み（ランクS）';
	and_join($where, "ust_status>=3 AND urs_rank='S'");
	break;
case 4:
	$name .= '引継ぎ済み（ランクA）';
	and_join($where, "ust_status>=3 AND urs_rank='A'");
	break;
case 5:
	$name .= '引継ぎ済み（ランクB）';
	and_join($where, "ust_status>=3 AND urs_rank='B'");
	break;
case 6:
	$name .= '引継ぎ済み（ランクJ）';
	and_join($where, "ust_status>=3 AND urs_rank='J'");
	break;
case 7:
	$name .= 'サービス停止（合計）';
	and_join($where, "ust_status>=3 AND urs_rank IN ('D','E','M')");
	break;
case 8:
	$name .= 'サービス停止（ランクD）';
	and_join($where, "ust_status>=3 AND urs_rank='D'");
	break;
case 9:
	$name .= 'サービス停止（ランクE）';
	and_join($where, "ust_status>=3 AND urs_rank='E'");
	break;
case 10:
	$name .= 'サービス停止（ランクM）';
	and_join($where, "ust_status>=3 AND urs_rank='M'");
	break;
}

and_join($where, "urg_follow_date<=current_timestamp");

// 販売店名取得
$dealer_name = get_dealer_name();

// ページ遷移履歴設定
set_page_history(1, $name, 'ユーザー別メール配信状況一覧');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title><?=$dealer_name?>様用ページ</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#33a2cc" align="center" width="100%" height="50">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="10" bgcolor="#dbeff7" align="center" width="100%" height="100%">
		<tr>
			<td class="titlefont"><?=$dealer_name?>様</td>
			<td class="text12" align="right" valign="bottom"><a href="logout.php" title="ログアウト">ログアウト</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#f7df3e" width="100%" class="text14">
<tr>
	<td><?=get_page_history(1)?></td>
</tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="100%" class="text14" align="center">
<tr>
	<td width="30%">
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" width="100%" class="text12">
		<tr>
			<td>
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="text12" width="100%" id="list">
		<tr>
			<td colspan="3" bgcolor="#dbdbdb">凡例</td>
		</tr>
		<tr>
			<td width="10%" align="center">○</td>
			<td width="10%">：</td>
			<td width="80%">メール送信済み</td>
		</tr>
		<tr class="off">
			<td align="center">済</td>
			<td>：</td>
			<td>1to1により送信済み</td>
		</tr>
		<tr>
			<td align="center" nowrap>空欄</td>
			<td>：</td>
			<td>未送信</td>
		</tr>
		</table>
			</td>
		</tr>
		</table>
			</td>
	<td width="70%" align="right"><a href="top.php" title="トップページへ戻る">トップページへ戻る</a></td>
</tr>
</table>
<br>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" align="center" width="100%" class="text12">
<tr>
	<td>

<table border="0" cellspacing="2" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12" id="list">
<?
$sub1 = "SELECT COUNT(*)"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cml_user_id=umg_user_id AND (cml_send_recv='R' OR cmc_comm_type=4)";

$sql = "SELECT umg_user_id,ups_name_kanji,ust_follow_mail_vol,ust_rel_mail_vol,ust_rel_stop_flag,urg_follow_date,($sub1) AS count_mail"
		. " FROM t_car_model"
		. " JOIN t_user_manage ON umg_car_model_cd=crm_car_model_cd"
		. " JOIN t_user_status ON ust_user_id=umg_user_id"
		. " JOIN t_user_personal ON ups_user_id=umg_user_id"
		. " JOIN t_user_regist ON urg_user_id=umg_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=umg_user_id"
		. " WHERE umg_dealer_cd=" . sql_char($g_dealer_cd) . " AND $where";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$follow_mail_vol = $fetch->ust_follow_mail_vol;
	$rel_mail_vol = $fetch->ust_rel_mail_vol;

	if ($i % 10 == 0) {
?>
<tr bgcolor="#f7df3e">
	<th rowspan="2" width="100">ユーザー名</th>
	<th colspan="4">初期フォローメール</th>
	<th colspan="22">継続リレーションメール</th>
	<th rowspan="2" width="50">1to1メール</th>
	<th rowspan="2" width="50">1to1メール回数</th>
	<th rowspan="2" width="70">初回メール配信日</th>
</tr>
<tr bgcolor="#f7df3e">
	<th width="25">1</th>
	<th width="25">2</th>
	<th width="25">3</th>
	<th width="25">4</th>
	<th width="25">1</th>
	<th width="25">2</th>
	<th width="25">3</th>
	<th width="25">4</th>
	<th width="25">5</th>
	<th width="25">6</th>
	<th width="25">7</th>
	<th width="25">8</th>
	<th width="25">9</th>
	<th width="25">10</th>
	<th width="25">11</th>
	<th width="25">12</th>
	<th width="25">13</th>
	<th width="25">14</th>
	<th width="25">15</th>
	<th width="25">16</th>
	<th width="25">17</th>
	<th width="25">18</th>
	<th width="25">19</th>
	<th width="25">20</th>
	<th width="25">21</th>
	<th width="25">22</th>
</tr>
<?
	}
?>
<tr align="center" class="<?=$i % 2 == 0 ? 'on' : 'off'?>">
	<td><a href="thread.php?user_id=<?=$fetch->umg_user_id?>" title="メールコミュニケーションを見る"><?=htmlspecialchars($fetch->ups_name_kanji)?></a></td>
<?
	for ($vol_no = 1; $vol_no <= 4; $vol_no++) {
?>
	<td><? disp_follow_mail($follow_mail_vol, $vol_no) ?></td>
<?
	}
?>
<?
	for ($vol_no = 1; $vol_no <= 22; $vol_no++) {
?>
	<td><? disp_relation_mail($rel_mail_vol, $vol_no) ?></td>
<?
	}
?>
	<td><?=$fetch->ust_rel_stop_flag == DBTRUE ? 'ON' : 'OFF'?></td>
	<td><?=number_format($fetch->count_mail)?></td>
	<td><?=format_date($fetch->urg_follow_date)?></td>
</tr>
<?
}
?>
</table>

	</td>
</tr>
</table>

<p class="copyright" align="center">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>

</body>

</html>
