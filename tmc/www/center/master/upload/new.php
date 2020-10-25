<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:アップロード担当新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// 担当販売店
function tantou_dealer() {
	// 登録済みの担当販売店取得
	$sql = "SELECT upd_dealer_cd,upd_upload_cd FROM t_upload_dealer";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	$dealer_ary = array();
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$dealer_ary[$fetch->upd_dealer_cd] = $fetch->upd_upload_cd;
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
					echo "<input type='checkbox' name='dealer_cd[]' value='$fetch->dlr_dealer_cd'" . disabled($dealer_ary[$fetch->dlr_dealer_cd] != '') . ">$fetch->dlr_dealer_cd-$fetch->dlr_short_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.upload_cd.value == "") {
		alert("アップロード担当コードを入力してください。");
		f.upload_cd.focus();
		return false;
	}
	if (f.name.value == "") {
		alert("正式名称を入力してください。");
		f.name.focus();
		return false;
	}
	return confirm("アップロード担当を登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.upload_cd.focus()">

<? center_header('マスタメンテナンス｜アップロード担当マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録するアップロード担当情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">アップロード担当コード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="upload_cd" size=10 maxlength=10>
			<span class="note">（半角／全角１０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">正式名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">稼動状況<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="active" value='1' checked>稼動
			<input type="radio" name="active" value='0'>非稼動
		</td>
	</tr>
	<tr>
		<td class="m1">エクスポート画面背景色</td>
		<td class="n1">
			<input class="alpha" type="text" name="bgcolor" size=20 maxlength=20>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">担当販売店</td>
		<td class="n1"><? tantou_dealer() ?></td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
