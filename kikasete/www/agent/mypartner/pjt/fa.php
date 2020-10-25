<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リアルタイム集計
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'リアルタイム集計　定性回答', BACK_CLOSE);

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$enquete_id = $myp->enquete_id;

$question = $enquete->question[$question_no];
$qno = mb_convert_kana($question_no, 'N');
$sno = mb_convert_kana($sel_no, 'N');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($myp->room_name)?>（<?=$myp->pjt_id?>）</td>
	</tr>
</table>
</form>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" colspan=2>■集計結果</td>
	</tr>
	<tr>
		<td class="n1">
			<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td align="center" colspan=2>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2">Ｑ<?=$qno?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
								</td>
							</tr>
<?
if ($sel_no != 0) {
?>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2">(選択肢)　：　</font>
								</td>
								<td width="550">
								<font size="2"><?=$sno?>．<?=htmlspecialchars($question->sel_text[$sel_no])?></font>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<th width="6%"><font size="2">回答受<br>付連番</font></th>
					<th><font size="2"><nobr>回答</nobr></font></th>
				</tr>
<?
$sql = "SELECT as_free_answer"
		. " FROM t_enquete_list"
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
		. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY an_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->as_free_answer != '') {
?>
				<tr>
					<td align="center"><font size="2"><?=$i + 1?></font></td>
					<td><font size="2"><?=nl2br(htmlspecialchars(trim($fetch->as_free_answer)))?></font></td>
				</tr>
<?
	}
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
