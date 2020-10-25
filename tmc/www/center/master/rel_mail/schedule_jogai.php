<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール配信除外販売店選択
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 除外販売店
function jogai_dealer($date) {
	// 登録済みの除外販売店取得
	$sql = "SELECT rlj_dealer_cd FROM t_relation_jogai WHERE rlj_send_date=" . sql_date($date);
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	$dealer_ary = array();
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$dealer_ary[$fetch->rlj_dealer_cd] = true;
	}

	$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name,dlr_dealer_cd,dlr_short_name"
			. " FROM t_dealer"
			. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
			. " ORDER BY sch_sales_channel_cd,dlr_dealer_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo "<table border=0 cellspacing=0 cellpadding=1 width='100%' class='small'>\n";

	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		if ($sales_channel_cd != $fetch->sch_sales_channel_cd) {
			echo "<tr class='subhead'>\n";
			echo "<td colspan=5>$fetch->sch_sales_channel_name</td>\n";
			echo "</tr>\n";
		}

		$sales_channel_cd = $fetch->sch_sales_channel_cd;

		echo "<tr>\n";

		for ($j = 0; $j < 5; $j++) {
			echo "<td width='20%'>";
			if ($i < $nrow) {
				$fetch = pg_fetch_object($result, $i);
				if ($fetch->sch_sales_channel_cd == $sales_channel_cd) {
					echo "<input type='checkbox' name='dealer_cd[]' value='$fetch->dlr_dealer_cd'" . checked($dealer_ary[$fetch->dlr_dealer_cd]) . ">$fetch->dlr_dealer_cd-$fetch->dlr_short_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}

// 入力パラメータ
$date = $_GET['date'];
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function all_set(f) {
	var f = document.form1;
	for (var i = 0; i < f["dealer_cd[]"].length; i++)
		f["dealer_cd[]"][i].checked = true;
}
function all_reset(f) {
	var f = document.form1;
	for (var i = 0; i < f["dealer_cd[]"].length; i++)
		f["dealer_cd[]"][i].checked = false;
}
//-->
</script>
</head>
<body>

<? center_header('マスタメンテナンス｜継続リレーションメール｜配信日設定｜配信除外販売店選択') ?>

<div align="center">
<form method="post" name="form1" action="schedule_jogai_update.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">■<?=$date?>に継続リレーションメールを配信しない販売店を選択してください。</td>
		<td align="right">
			<input type="button" value="全て設定" onclick="all_set()">
			<input type="button" value="全て解除" onclick="all_reset()">
		</td>
	</tr>
	<tr>
		<td class="n1" colspan=2><? jogai_dealer($date) ?></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="date" <?=value($date)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
