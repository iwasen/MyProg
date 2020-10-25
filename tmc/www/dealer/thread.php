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
include("$inc/dealer.php");
include("mail_inc.php");

// スレッド表示
function display_thread($comm_id, &$seq_ary, $depth = ' ') {
	global $g_seq_no;

	if (strlen($depth) > MAX_THREAD_DEPTH)
		return;

	$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cmc_subject,cmc_comm_type,ups_name_kanji,stf_name"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " JOIN t_user_personal ON ups_user_id=cml_user_id"
			. " LEFT JOIN t_staff ON stf_staff_id=cml_staff_id"
			. " WHERE cml_comm_id=$comm_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
?>
<tr class="<?=$g_seq_no++ % 2 == 0 ? 'on' : 'off'?>">
	<td align="center"><?=$seq_ary[$fetch->cml_comm_id]?></td>
	<td><?=send_name($fetch->cmc_comm_type, $fetch->cml_send_recv, $fetch->ups_name_kanji, $fetch->stf_name)?></td>
	<td align="center"><?=mail_type($fetch->cmc_comm_type, $fetch->cml_send_recv)?></td>
	<td><a href="mail.php?comm_id=<?=$fetch->cml_comm_id?>"><?=$depth?><?=htmlspecialchars($fetch->cmc_subject)?></a></td>
	<td><?=date('Y/m/d H:i:s', sql_time($fetch->cml_date))?></td>
</tr>
<?
	} else
		return;

	$depth = str_replace('└', '　', $depth);

	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_parent_comm_id=$comm_id ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		display_thread($fetch->cml_comm_id, $seq_ary, $depth . '└ ');
	}
}

// 入力パラメータ
$user_id = $_GET['user_id'];

// 販売店名取得
$dealer_name = get_dealer_name();

// ユーザ名取得
$sql = "SELECT ups_name_kanji FROM t_user_personal WHERE ups_user_id=$user_id";
$result = db_exec($sql);
if (pg_num_rows($result) != 0)
	$name_kanji = pg_fetch_result($result, 0, 0);


// ページ遷移履歴設定
set_page_history(2, $name_kanji, 'メール配信履歴');
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
	<td><?=get_page_history()?></td>
</tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="90%" class="text14" align="center">
<tr>
	<td align="right"><a href="top.php" title="トップページへ戻る">トップページへ戻る</a></td>
</tr>
</table>
<br>


<table border="0" cellspacing="0" cellpadding="1" bgcolor="#ffffff" align="center" width="90%" class="text12">
<tr>
	<td align="right"><a href="time.php?user_id=<?=$user_id?>" title="時系列表示">時系列表示</a></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" align="center" width="90%" class="text12">
<tr>
	<td>

<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%" id="mail" class="text12" bgcolor="#ffffff">

<tr bgcolor="#f7df3e">
	<th width="5%">SEQ</th>
	<th width="20%">送信者</th>
	<th width="5%">メール種別</th>
	<th width="50%">SUBJECT</th>
	<th width="20%">送信日時</th>
</tr>
<?
// シーケンス番号取得
$seq_ary = get_seq_no($user_id);

$sql = "SELECT cml_comm_id"
		. " FROM t_comm_log"
		. " WHERE cml_user_id=$user_id AND cml_parent_comm_id IS NULL"
		. " ORDER BY cml_comm_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	display_thread($fetch->cml_comm_id, $seq_ary);
}
?>
</table>

	</td>
</tr>
</table>


<p class="copyright" align="center">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>

</body>

</html>
