<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:マーケター登録情報表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/decode.php");

//window_size 500:510

//種別DECODE
function decode_type($code) {
	switch ($code) {
	case 1:
		return 'メールニュース登録者';
		break;
	case 2:
		return '無料マーケター登録者';
		break;
	case 3:
		return '有料マーケター登録者';
		break;
	case 4:
		return 'アシスタント';
		break;
	default:
		return '未設定';
		break;
	}
}

//状態DECODE
function decode_status($code) {
	switch ($code) {
	case 0:
		return '有効';
		break;
	case 2:
		return '不達状態';
		break;
	case 9:
		return '退会';
		break;
	default:
		return '不明';
		break;
	}
}

// マーケターDECODE
function decode_marketer($code) {
	if ($code != '') {
		$sql = "SELECT mr_name1 || ' ' || mr_name2 FROM t_marketer WHERE mr_marketer_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// アシスタント取得
function get_assistant($marketer_id) {
	$assistant_ary = array();

	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4 ORDER BY mr_marketer_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$assistant_ary[] = $fetch->mr_marketer_id;
	}

	return $assistant_ary;
}

//メイン処理

set_global('マーケター登録情報','');

$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$mr_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='450'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
				</td>
			</tr>
			<tr>
				<td>■マーケター登録情報</td>
			</tr>
		</table>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="2">基本情報</td>
			</tr>
			<tr>
				<td width="35%" class="n3">マーケターID</td>
				<td><?=$fetch->mr_marketer_id?></td>
			</tr>
			<tr>
				<td class="n3">氏名</td>
				<td><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
			</tr>
			<tr>
				<td class="n3">氏名(カナ)</td>
				<td><?=htmlspecialchars("$fetch->mr_name1_kana $fetch->mr_name2_kana")?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>生年月日</nobr></td>
				<td><?=format_date($fetch->mr_birthday)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>性別</nobr></td>
				<td><?=decode_sex($fetch->mr_sex)?></td>
			</tr>
			<tr>
				<td class="n3">メールアドレス</td>
				<td><?=htmlspecialchars("$fetch->mr_mail_addr")?></td>
			</tr>
<!--			<tr>
				<td class="n3"><nobr>パスワード</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_password)?></td>
			</tr>	-->
			<tr>
				<td class="n3">職業</td>
				<td><?=decode_shokugyou($fetch->mr_shokugyou_cd)?></td>
			</tr>
			<tr>
				<td class="n3">業種</td>
				<td><?=decode_gyoushu($fetch->mr_gyoushu_cd)?></td>
			</tr>
			<tr>
				<td class="n3">職種</td>
				<td><?=decode_shokushu($fetch->mr_shokushu_cd)?></td>
			</tr>
		</table><br>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="3">勤務先情報</td>
			</tr>
			<tr>
				<td width=35% class="n3"><nobr>勤務先郵便番号</nobr></td>
				<td><?=format_zip($fetch->mr_kinmu_zip)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>勤務先地域</nobr></td>
				<td><?=decode_area($fetch->mr_kinmu_area)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>勤務先住所</nobr></td>
				<td><?=htmlspecialchars("$fetch->mr_kinmu_addr1 $fetch->mr_kinmu_addr2 $fetch->mr_kinmu_addr3")?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>勤務先電話番号</nobr></td>
				<td><?=$fetch->mr_kinmu_tel?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>勤務先FAX番号</nobr></td>
				<td><?=$fetch->mr_kimnu_fax?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>企業名</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>部署名</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_unit)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>役職</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_post)?></td>
			</tr>
		</table><br>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="3">その他情報</td>
			</tr>
			<tr>
				<td width=35% class="n3"><nobr>エージェント</nobr></td>
				<td><?=decode_agent($fetch->mr_agent_id)?></td>
			</tr>
<?
$assistant_ary = get_assistant($mr_id);
?>
			<tr>
				<td class="n3"><nobr>アシスタント１</nobr></td>
				<td><?=decode_marketer($assistant_ary[0])?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>アシスタント２</nobr></td>
				<td><?=decode_marketer($assistant_ary[1])?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>種別</nobr></td>
				<td><?=decode_type($fetch->mr_type)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>種別</nobr></td>
				<td><?=decode_status($fetch->mr_status)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>登録日時</nobr></td>
				<td><?=format_date($fetch->mr_regist_date)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>更新日時</nobr></td>
				<td><?=format_date($fetch->mr_update_date, '')?></td>
			</tr>
		</table>
	<input type="button" value="　閉じる　" onclick="window.close()">
	</center>
</form>
</body>
</html>