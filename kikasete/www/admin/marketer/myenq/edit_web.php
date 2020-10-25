<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート情報変更', BACK_TOP);

// セッション処理
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($marketer_id, $my_enq_no);
}

$enquete = &$myenq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myenq);
else
	$description = $enquete->description;
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
<form method="post" name="form1" action="update_web.php">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">■アンケートＷＥＢ画面のカスタマイズ</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" class="m1">説明文</td>
					<td class="n1">
						<textarea rows="20" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		switch ($question->question_type) {
		case 1:
		case 2:
		case 7:
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
				<tr>
					<td class="n1">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' （フリー回答）' : ''?><?=$question->ex_sno == $sno ? ' （排他）' : ''?><br>
<?
			}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
			break;
		case 3:
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
			break;
		case 4:
		case 5:
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
				<tr>
					<td class="n1">
						<table border="0" cellspacing="2" cellpadding="2" width="100%">
							<tr>
								<td>&nbsp;</td>
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
			</table>
		</td>
		<!-- テーブル大枠 -->
	</tr>
<?
			break;
		case 6:
		case 8:
?>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td width="20%" rowspan="3" class="m1">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
					<td class="n1"><?=htmlspecialchars($question->question_text)?></td>
				</tr>
				<tr>
					<td class="n1"><?=decode_question_type($question->question_type)?></td>
				</tr>
				<tr>
					<td class="n1">
						<div>回答欄の前に表示する文字列：<?=htmlspecialchars($question->pre_text)?></div>
						<div>回答欄の後に表示する文字列：<?=htmlspecialchars($question->post_text)?></div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?
			break;
		}
	}
}
?>
</table>

<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
