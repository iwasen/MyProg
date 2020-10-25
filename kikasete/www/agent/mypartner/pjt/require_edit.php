<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・該当条件設定
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　該当条件設定', BACK_TOP);

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;

$sql = "SELECT psl_question_no,psl_sel_no,psl_and_or FROM t_mp_select WHERE psl_pjt_id=$pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$qno = $fetch->psl_question_no;
	$and_or[$qno] = $fetch->psl_and_or;
	$sel_no[$qno] = explode(',', $fetch->psl_sel_no);
}

if ($myp->and_or == '')
	$myp->and_or = 'A';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onclick_search() {
	window.open("", "search", "width=300,height=160");
	document.form1.target = "search";
	document.form1.next_action.value = "search";
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="require_update.php">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($myp->room_name)?></td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
	<tr>
		<td class="lt">&nbsp;</td>
		<td class="lb">&nbsp;</td>
	</tr>
</table>

<span class="lb">
<div align="right">質問間結合条件
	<input name="and_or1" type="radio" <?=value_checked('A', $myp->and_or)?>>AND
	<input name="and_or1" type="radio" <?=value_checked('O', $myp->and_or)?>>OR
</div>
</span>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
<?
$n = 0;
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type != 3) {
			$and_or_flag = $and_or[$qno] != '' ? $and_or[$qno] : 'O';
?>
				<tr class="tc<?=$n % 2?>">
					<td align="center">Q<?=$qno?></td>
					<td align="center">
						<input type="radio" name="and_or2[<?=$qno?>]" <?=value_checked('O', $and_or_flag)?>>OR&nbsp;&nbsp;
						<input type="radio" name="and_or2[<?=$qno?>]" <?=value_checked('A', $and_or_flag)?>>AND
					</td>
					<td>
<?
			if ($question->question_type == 1 || $question->question_type == 2) {
				if (is_array($question->sel_text)) {
					foreach ($question->sel_text as $sno => $sel_text) {
?>
						<nobr><input type="checkbox" name="sel_no[<?=$qno?>][]" value="<?=$sno?>" <?=checked(is_array($sel_no[$qno]) && in_array($sno, $sel_no[$qno]))?>><?=$sno?>:<?=htmlspecialchars($sel_text)?>&nbsp;&nbsp;</nobr>
<?
					}
				}
			} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
						<table width="100%" border="1" cellspacing="1" cellpadding="2" class="small">
							<tr>
								<td></td>
<?
				foreach ($question->hyoutou as $hyoutou) {
?>
								<td align="center"><?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr>
								<td><?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="checkbox" name="sel_no[<?=$qno?>][]" value="<?="$sno:$tno"?>" <?=checked(is_array($sel_no[$qno]) && in_array("$sno:$tno", $sel_no[$qno]))?>></td>
<?
					}
?>
							</tr>
<?
				}
?>
						</table>
<?
			}
?>
					</td>
				</tr>
<?
			$n++;
		}
	}
}
?>
</table>
<br>
<div align="center">
	<input type="submit" value="該当者人数検索" onclick="onclick_search()">
	<input type="submit" value="　更新　" onclick="document.form1.target='';document.form1.next_action.value='update'">
	<input type="reset" value="元に戻す">
	<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
	<input type="hidden" name="next_action">
</div>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
