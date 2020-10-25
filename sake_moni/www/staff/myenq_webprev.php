<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
} else {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
}

$enquete = &$myenq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myenq);
else
	$description = $enquete->description;

$fetch = get_system_info('sy_url_monitor');
$monitor_url = $fetch->sy_url_monitor;
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<table width="500" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td><b>■Webプレビュー</b></td>
				</tr>
			</table>
			<br>

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
			</div>
			<input type="button" value="　戻る　" onclick="history.back()">

		</td>
	</tr>
</table>

<? staff_footer() ?>
