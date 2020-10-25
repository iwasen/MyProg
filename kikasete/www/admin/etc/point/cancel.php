<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント精算却下取消確認処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// 交換商品デコード
function decode_shouhin($code) {
	switch ($code) {
	case 1:
		return 'VISAギフト券';
	case 2:
		return '図書券';
	case 3:
		return 'WEBMONEY';
	case 4:
		return '楽天銀行';
	}
}

// メイン処理
set_global('etc', 'その他管理', 'ポイント精算', BACK_TOP);

$sql = "SELECT si_member_id,si_point,si_req_date,si_item,mn_name1,mn_name2"
	. " FROM t_seisan"
	. " JOIN t_monitor ON mn_monitor_id=si_member_id"
	. " WHERE si_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$monitor_id = $fetch->si_member_id;
	$point = number_format($fetch->si_point);
	$shouhin = decode_shouhin($fetch->si_item);
	$date = substr($fetch->si_req_date, 0, 16);
	$name = "$fetch->mn_name1 $fetch->mn_name2";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<p class="msg">
<table border="0" cellspacing="0" cellpadding="0" style="color: darkblue">
	<tr>
		<td>モニターID</td>
		<td>：<?=$monitor_id?></td>
	</tr>
	<tr>
		<td>申請者名</td>
		<td>：<?=htmlspecialchars($name)?></td>
	</tr>
	<tr>
		<td>申請ポイント</td>
		<td>：<?=$point?></td>
	</tr>
	<tr>
		<td>申請日</td>
		<td>：<?=$date?></td>
	</tr>
	<tr>
		<td>交換商品</td>
		<td>：<?=htmlspecialchars($shouhin)?></td>
	</tr>
</table>
の却下を取消しますが、宜しいでしょうか？<br>
<br>
※却下を取り消すと、状態が「未精算」に戻ります。<br>
また、モニターＭｙページの精算履歴が復帰し<br>
申請分のポイントが再度減算されます。<br>
</p>
<p>
	<input type="button" value="　はい　" onclick="location.href='cancel2.php?seq_no=<?=$seq_no?>'">
	<input type="button" value="いいえ" onclick="location.href='list.php'">
</p>
</div>

<? page_footer() ?>
</body>
</html>
