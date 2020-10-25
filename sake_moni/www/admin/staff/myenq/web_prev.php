<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ＭｙアンケートWebプレビュー
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('staff', 'スタッフ管理｜Ｍｙアンケート管理', 'Ｗｅｂプレビュー', BACK_TOP);

$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;

// 説明文が設定されていない場合はテンプレートを表示
$description = ($enquete->description != '') ? $enquete->description : get_enq_description($myenq);

$fetch = get_system_info('sy_url_monitor');
$monitor_url = $fetch->sy_url_monitor;
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

<div align="center">
<TABLE WIDTH=673 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD rowspan=2 valign="top"><IMG SRC="<?=$monitor_url?>images/monitor02_01.gif" WIDTH=88 HEIGHT=135></TD>
		<TD><IMG SRC="<?=$monitor_url?>images/monitor02_02.gif" WIDTH=585 HEIGHT=87 ALT="アンケートに答える"></TD>
	</TR>
	<TR>
		<TD valign="top">

<!--▼表組み▼-->
<form>
<TABLE WIDTH=585 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD bgcolor="#EEEEEE" height="28"><SPAN class="t13">&nbsp;&nbsp;&nbsp;<B><?=htmlspecialchars($enquete->title)?></B></span></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD align="center">

<TABLE WIDTH=100% BORDER=1 CELLPADDING=10 CELLSPACING=1 bordercolor="#DDDDDD">
  <TR>
    <TD bgColor=white colSpan=2><FONT size=2><?=nl2br(htmlspecialchars($description))?><BR></FONT></TD></TR>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_text != '') {
?>
  <TR>
    <TD bgColor=#EEEEEE colSpan=2>
			<FONT color=#003366 size=2><B>Q<?=$qno?>. <?=nl2br(htmlspecialchars($question->question_text))?></B></FONT>
<?
			if ($question->url != '') {
?>
			<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
?>
			<br><img src="<?=$monitor_url?>img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
		</TD>
	</TR>
  <TR>
    <TD vAlign=top width="9%" bgColor=white><FONT size=2>A<?=$qno?>.</FONT></TD>
    <TD width="91%" bgColor=white><FONT size=2>
<?
			switch ($question->question_type) {
			case 1:		// 単一選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
?>
			<?=$br?><nobr><input type="radio" name="answer<?=$qno?>" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							$br = '<br>';
						}
					}
					if ($question->fa_flag == 't') {
?>
			<input type="text" name="free_answer<?=$qno?>" size=25>
<?
					}
				}
				break;
			case 2:		// 複数選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sel_text) {
						if ($sel_text != '') {
?>
			<?=$br?><nobr><input type="checkbox" name="answer<?=$qno?>[]" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							$br = '<br>';
						}
					}
					if ($question->fa_flag == 't') {
?>
			<input type="text" name="free_answer<?=$qno?>" size=25>
<?
					}
				}
				break;
			case 3:		// 自由回答
?>
			<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
			<br><br>ご自由にご記入ください。
<?
				break;
			case 4:		// マトリクス(SA)
?>
			<table>
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td bgcolor="#ffddee"><font size="2"><?=$tno?>．<?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
				<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
					<td><font size="2"><?=$sno?>．<?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td align="center"><input type="radio" name="answer<?=$qno?>[<?=$sno?>]" value="<?="$sno:$tno"?>"></td>
<?
					}
?>
				</tr>
<?
				}
?>
		</table>
<?
				if ($question->dup_flag == 't') {
?>
								（同じ列の中で重複して選択することはできません）
<?
				}
				break;
			case 5:		// マトリクス(MA)
?>
			<table>
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td bgcolor="#ffddee"><font size="2"><?=$tno?>．<?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
				<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
					<td><font size="2"><?=$sno?>．<?=htmlspecialchars($hyousoku)?></font></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
					<td align="center"><input type="checkbox" name="answer<?=$qno?>[]" value="<?="$sno:$tno"?>"></td>
<?
					}
?>
				</tr>
<?
				}
?>
			</table>
<?
				if ($question->dup_flag == 't') {
?>
								（同じ列の中で重複して選択することはできません）
<?
				}
				break;
			}
?>
			</FONT>
		</TD>
	</TR>
<?
		}
	}
}
?>
	<TR>
		<TD colspan="2"><INPUT type=button value="　回答を送る　"> <INPUT type=reset value="　クリア　"></TD>
	</TR>
</TABLE>

		</TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="<?=$monitor_url?>images/spacer.gif" WIDTH=585 HEIGHT=30></TD>
	</TR>
</TABLE>
<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
</form>
<!--▲表組み▲-->

		</TD>
	</TR>
</TABLE>
<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
