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
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);

if ($main)
	$enquete = &$pro_enq->enquete2;
else
	$enquete = &$pro_enq->enquete;
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
<form>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■分岐・改ページの設定</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<?
if (is_array($enquete->question)) {
	$branch_button_flag = false;
	$count = count($enquete->question);
	foreach ($enquete->question as $qno => $question) {
		if ($qno > 1) {
			if (isset($enquete->branch_cond[$qno])) {
?>
				<tr>
					<td bgcolor="#ffff99" align="center" colspan=2>分岐あり（改ページ）<span style="color:#ff0000;">＜<?=htmlspecialchars($enquete->branch_cond_text($qno, 'を選択した方のみ', false, false))?>＞</span></td>
				</tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
				<tr>
					<td bgcolor="#66ccff" align="center" colspan=2>改ページ</td>
				</tr>
<?
			}
		}
?>
				<tr>
					<td class="m1">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td>Ｑ<?=mb_convert_kana($qno, 'N')?>．<?=nl2br(htmlspecialchars($question->question_text))?></td>
								<td align="right" valign="top">
<?
		if ($qno > 1) {
			if ($question->page_break == DBTRUE) {
?>
									<input type="button" value="改ページの解除" onclick="if (confirm('改ページを解除します。よろしいですか？')) location.href='update_pb.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>&qno=<?=$qno?>&pb=0'"<?=isset($enquete->branch_cond[$qno]) ? ' disabled' : ''?>>
<?
			} else {
?>
									<input type="button" value="改ページの設定" onclick="location.href='update_pb.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>&qno=<?=$qno?>&pb=1'"<?=isset($enquete->branch_cond[$qno]) ? ' disabled' : ''?>>
<?
			}
?>
									<input type="button" value="分岐条件の設定" onclick="location.href='edit_branch2.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>&qno=<?=$qno?>'"<?=$branch_button_flag ? '' : ' disabled'?>>
<?
		}
?>
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
						<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
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
						<table border="1" cellspacing="0" cellpadding="2" width="100%" frame="box">
							<tr>
								<td bgcolor="#ffffff">&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
							</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
							<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
								<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($question->hyoutou as $hno => $hyoutou) {
?>
								<td></td>
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

		if ($question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8)
			$branch_button_flag = true;
	}
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="プレビュー" onclick="location.href='web_prev.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
