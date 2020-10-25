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
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
} else {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
}

$enquete = &$myenq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myenq);
else
	$description = $enquete->description;
?>
<?
if ($popup) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net−Ｍｙアンケート</title>
<link rel="stylesheet" href="css/style.css">
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
} else
	marketer_header('Ｍｙアンケート', PG_NULL)
?>
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
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="500" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td><b>■Webプレビュー</b></td>
				</tr>
			</table>
			<br>		  <div align="center">
			<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
				<tr>
					<td><table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td bgcolor="#c3c2ff" colspan="2"><span style="color:#2b2b63;"><b><?=htmlspecialchars($enquete->title)?></b> についてのアンケート</span><br>
									</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="white"><br><?=nl2br(htmlspecialchars($description))?><br>
									<br>
									</td>
							</tr>
<?
if (is_array($enquete->question)) {
	$i = 0;
	foreach (array_keys($enquete->question) as $qno) {
		$question = &$enquete->question[$qno];
		if ($question->question_text != '' && !$question->delete_flag) {
			$i++;
			if (isset($enquete->branch_cond[$qno])) {
?>
								<tr><td colspan=2 bgcolor="#ffff99" align="center">分岐あり（改ページ）<span style="color:#ff0000;">＜<?=htmlspecialchars($enquete->branch_cond_text($qno, 'を選択した方のみ', false, false))?>＞</span></td></tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
								<tr><td colspan=2 bgcolor="#ffcc99" align="center">改ページ</td></tr>
<?
			}
?>
							<tr>
								<td bgcolor="#c3c2ff" colspan="2">
									<span style="color:#2b2b63;"><b>Q<?=$i?>. <br><?=nl2br(htmlspecialchars($question->question_text))?></b></span>
<?
			if ($question->url != '') {
?>
									<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
?>
									<br><img src="../monitor/img.php?id=<?=$question->image_id?>" alt="">
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
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
									<nobr><input type="radio" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
							if ($question->fa_sno == $sno) {
?>
									<input type="text" name="free_answer<?=$i?>" size=25>
<?
							}
						}
					}
				}
				break;
			case 2:		// 複数選択
				if (is_array($question->sel_text)) {
					$br = '';
					foreach ($question->sel_text as $sno => $sel_text) {
						if ($sel_text != '') {
							echo $br;
							$br = '<br>';
?>
									<nobr><input type="checkbox" name="answer<?=$i?>" value=""> <?=htmlspecialchars($sel_text)?></nobr>
<?
							if ($question->fa_sno == $sno) {
?>
									<input type="text" name="free_answer<?=$i?>" size=25>
<?
							}
						}
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
			case 6:		// 数量回答
?>
									<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$i?>" size="10"> <?=htmlspecialchars($question->post_text)?>
<?
				break;
			case 7:		// プルダウン
?>
								<select name="answer<?=$i?>">
									<option value="">選択してください</option>
<?
				if (is_array($question->sel_text)) {
					foreach ($question->sel_text as $sno => $sel_text) {
?>
									<option <?=value($sno)?>><?=htmlspecialchars($sel_text)?></option>
<?
					}
				}
?>
								</select>
<? 
				break;
			case 8:		// フリー回答（１行）
?>
								<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$i?>"> <?=htmlspecialchars($question->post_text)?>
<?
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
<?
if ($popup) {
?>
			<a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()"

onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="閉じる"

name="Image19" width="108" height="31" border="0"></a>
<?
} else {
?>
			<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
<?
}
?>
			</div>

		</td>
	</tr>
</table>
<?
if ($popup) {
?>
<br>
</body>
</html>

<?
} else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<? marketer_footer();?>
	</td>
  </tr>
</table>
<? }?>

