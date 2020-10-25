<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($marketer_id, $pro_enq_no);
}

$enquete = &$pro_enq->enquete;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function disp_res_and_or() {
	var f = document.form1;
	var cn = 0;
	for (var i = 1; i <= <?=count($enquete->question)?>; i++) {
		var sn = f["select_no[" + i + "][]"];
		if (sn) {
			if (sn.length) {
				for (var j = 0; j < sn.length; j++) {
					if (sn[j].checked) {
						cn++;
						break;
					}
				}
			} else {
				if (sn.checked)
					cn++;
			}
		}
	}
	document.getElementById("id_res_and_or").style.display = cn >= 2 ? "" : "none";
}
window.onload = function() {
  disp_res_and_or();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update_res.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">■本アンケート対象条件</td>
					<td align="right" id="id_res_and_or" style="display:none">設問間結合条件　<input type="radio" name="res_and_or" <?=value_checked('A', $pro_enq->res_and_or)?>>AND　<input type="radio" name="res_and_or" <?=value_checked('O', $pro_enq->res_and_or)?>>OR</td>
				</tr>
			</table>
		</td>
	</tr>
<?
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($question->question_type == 3 || $question->question_type == 6 || $question->question_type == 8)
				continue;

			$res_cond = &$pro_enq->res_cond[$qno];

			// 初期値
			if (!isset($res_cond)) {
				$res_cond->and_or = 'O';
				$res_cond->not_cond = DBFALSE;
			}

			$sa_flag = false;
			if ($question->question_type == 1 || $question->question_type == 7) {
				$res_cond->and_or = 'O';
				$sa_flag = true;
			}
?>
	<tr>
		<td class="m1" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
		<td class="n1"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td class="n1">
			<table border="1" cellspacing="0" cellpadding="0" class="n1">
				<tr>
					<td>
						<input type="radio" name="not_cond[<?=$qno?>]" <?=value_checked(DBFALSE, $res_cond->not_cond)?>>選択されている
						<input type="radio" name="not_cond[<?=$qno?>]" <?=value_checked(DBTRUE, $res_cond->not_cond)?>>選択されていない
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('A', $res_cond->and_or)?> <?=$sa_flag ? 'disabled' : ''?>>AND条件
						<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('O', $res_cond->and_or)?>>OR条件
						<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('F', $res_cond->and_or)?> <?=$sa_flag ? 'disabled' : ''?>>完全一致
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td class="n1">
<?
				foreach ($question->sel_text as $sno => $sel_text) {
?>
			<input type="checkbox" name="select_no[<?=$qno?>][]" <?=value_checked_multi($sno, $res_cond->select_no)?> onclick="disp_res_and_or()"><?=htmlspecialchars($sel_text)?><br>
<?
				}
?>
		</td>
	</tr>
<?
			} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td class="n1">
			<table border="0" cellspacing="2" cellpadding="2" width="100%">
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td bgcolor="#ffffff"><?=mb_convert_kana($tno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
				<tr bgcolor="<?=$sno % 2 ? '#e8fbfd' : '#ffffff'?>">
					<td><?=mb_convert_kana($sno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td align="center"><input type="checkbox" name="select_no[<?=$qno?>][]" <?=value_checked_multi("$sno:$tno", $res_cond->select_no)?> onclick="disp_res_and_or()"></td>
<?
					}
?>
				</tr>
<?
				}
?>
			</table>
		</td>
	</tr>
<?
			}
		}
	}
?>
	<tr>
		<td class="m1">想定出現率</td>
		<td class="n1"><input type="text" name="appearance_ratio" size=5 <?=value($pro_enq->appearance_ratio)?>> %</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
