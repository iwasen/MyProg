<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Proアンケート料金表マスタ変更画面
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

// メイン処理
set_global('proenquete', 'Ｐｒｏアンケート管理｜マスタメンテナンス', '料金表管理', BACK_TOP);

if (!isset($add_row) && !isset($add_col)) {
	$question_num = array();
	$sql = "SELECT DISTINCT pep_question_num FROM m_proenq_price ORDER BY pep_question_num";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$question_num[$i] = $fetch->pep_question_num;
		$col_ary[$fetch->pep_question_num] = $i;
	}

	$sample_num = array();
	$sql = "SELECT DISTINCT pep_sample_num FROM m_proenq_price ORDER BY pep_sample_num";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$sample_num[$i] = $fetch->pep_sample_num;
		$row_ary[$fetch->pep_sample_num] = $i;
	}

	$price = array();
	$sql = "SELECT pep_question_num,pep_sample_num,pep_price FROM m_proenq_price";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$price[$row_ary[$fetch->pep_sample_num]][$col_ary[$fetch->pep_question_num]] = $fetch->pep_price;
	}
}

$col_num = count($question_num) + $add_col;
$row_num = count($sample_num) + $add_row;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (!check_data(f))
			return false;
		return confirm("本アンケート料金表を更新します。よろしいですか？");
	}
	return true;
}
function check_data(f) {
	for (var i = 0; i < <?=$col_num?>; i++) {
		var question_num = f["question_num[" + i + "]"];
		if (!num_chk(question_num.value)) {
			alert("質問数は半角数字で入力してください");
			question_num.focus();
			return false;
		}
		for (var j = 0; j < i; j++) {
			if (question_num.value == f["question_num[" + j + "]"].value) {
				alert("同じ質問数が存在します。");
				question_num.focus();
				return false;
			}
		}
	}
	for (var i = 0; i < <?=$row_num?>; i++) {
		var sample_num = f["sample_num[" + i + "]"];
		if (!num_chk(sample_num.value)) {
			alert("回答数は半角数字で入力してください");
			sample_num.focus();
			return false;
		}
		for (var j = 0; j < i; j++) {
			if (sample_num.value == f["sample_num[" + j + "]"].value) {
				alert("同じ回答数が存在します。");
				sample_num.focus();
				return false;
			}
		}
	}
	for (var i = 0; i < <?=$row_num?>; i++) {
		var sample_num = f["sample_num[" + i + "]"];
		if (sample_num.value != "") {
			for (var j = 0; j < <?=$col_num?>; j++) {
				var question_num = f["question_num[" + j + "]"];
				if (question_num.value != "") {
					var price = f["price[" + i + "][" + j + "]"];
					if (price.value == "") {
						alert("料金を入力してください");
						price.focus();
						return false;
					}
					if (!num_chk(price.value)) {
						alert("料金は半角数字で入力してください");
						price.focus();
						return false;
					}
				}
			}
		}
	}
	return true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■本アンケート料金表編集</td>
	</tr>
</table>

<form method="post" name="form1" action="update2.php" onsubmit="return onsubmit_form1(this)">
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<td colspan=2 rowspan=2></td>
		<td align="center" colspan="<?=$col_num?>"><b>質問数</b></td>
	</tr>
	<tr class="tch">
<?
for ($col = 0; $col < $col_num; $col++) {
?>
		<td align="center"><input type="text" name="question_num[<?=$col?>]" <?=value($question_num[$col])?> size=5></td>
<?
}
?>
	</tr>
<?
for ($row = 0; $row < $row_num; $row++) {
?>
	<tr>
<?
	if ($row == 0) {
?>
		<td class="tch" align="center" rowspan="<?=$row_num?>"><b>回<br>答<br>数</b></td>
<?
	}
?>
		<td class="tch" align="center">〜<input type="text" name="sample_num[<?=$row?>]" <?=value($sample_num[$row])?> size=5></td>
<?
	for ($col = 0; $col < $col_num; $col++) {
?>
		<td class="tc<?=$row % 2?>" align="center"><input type="text" name="price[<?=$row?>][<?=$col?>]" <?=value($price[$row][$col])?> size=5></td>
<?
	}
?>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="回答数追加" onclick="document.form1.next_action.value='add_row';action='edit2.php?add_row=1'">
<input type="submit" value="質問数追加" onclick="document.form1.next_action.value='add_col';action='edit2.php?add_col=1'">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update';action='update2.php'">
<input type="button" value="　戻る　" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
</form>
</div>

<? page_footer() ?>
</body>
</html>
