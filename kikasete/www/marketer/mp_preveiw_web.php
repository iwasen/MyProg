<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

// Myパートナークラス取得
if ($pjt_id) {
	$myp = new my_partner_class;
	$myp->read_db($pjt_id);
} else
	$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myp);
else
	$description = $enquete->description;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="500" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td><strong>■Webプレビュー</strong></td>
				</tr>
			</table>
			<br>

			<div align="center">
			<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
				<tr>
					<td><table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td bgcolor="#c3c2ff" colspan="2"><span style="color:#2b2b63;"><strong><?=htmlspecialchars($enquete->title)?></strong></span><br>
									</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="white"><br><?=nl2br(htmlspecialchars($description))?><br>
									<br>
									</td>
							</tr>
<?
if (is_array($enquete->question)) {
	foreach (array_keys($enquete->question) as $i) {
		$question = &$enquete->question[$i];
		if ($question->question_text != '') {
?>
							<tr>
								<td bgcolor="#c3c2ff" colspan="2">
									<span style="color:#2b2b63;"><strong>Q<?=$i?>. <?=nl2br(htmlspecialchars($question->question_text))?></strong></span>
<?
			if ($question->url != '') {
?>
									<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
				if (!$monitor_url) {
					$fetch = get_system_info('sy_url_monitor');
					$monitor_url = $fetch->sy_url_monitor;
				}
?>
									<br><img src="<?=$monitor_url?>img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="white" width="7%" valign="top">A<?=$i?>.</td>
								<td bgcolor="white" width="93%">
<?
			switch ($question->question_type) {
			case 1:		// 単一選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
									<nobr><input type="radio" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
						}
					}
					if ($question->fa_flag == 't') {
?>
									<input type="text" name="free_answer<?=$i?>" size=25>
<?
					}
				}
				break;
			case 2:		// 複数選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
									<nobr><input type="checkbox" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
						}
					}
					if ($question->fa_flag == 't') {
?>
									<input type="text" name="free_answer<?=$i?>" size=25>
<?
					}
				}
				break;
			case 3:		// 自由回答
?>
									<textarea name="answer<?=$i?>" cols="50" rows="15"></textarea>
									<br><br>ご自由にご記入ください。
<?
				break;
			case 4:		// マトリクス(SA)
?>
									<table width="100%">
										<tr>
											<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td align="center" bgcolor="#ffddee"><?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
										</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
										<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
											<td><?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td align="center"><input type="radio" name="answer<?=$sno?>"></td>
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
									<table width="100%">
										<tr>
											<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td align="center" bgcolor="#ffddee"><?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
										</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
										<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
											<td><?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td align="center"><input type="checkbox" name="answer<?=$i?>"></td>
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
									<br><br>
									</td>
							</tr>
<?
		}
	}
}
?>
						</table></td>
				</tr>
			</table>
			<br>
			<a href="javascript:history.back();"><img name="image2" src="images/mkk_achange/bt01.gif" alt="戻る" width="108" height="31"></a>

			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
