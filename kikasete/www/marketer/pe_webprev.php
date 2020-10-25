<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($pro_enq_no)) {
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else {
$pro_enq = &$_SESSION['ss_pro_enq'];
$pro_enq_no = $pro_enq->pro_enq_no;
}
?>
<?
if ($popup) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net−Ｐｒｏアンケート</title>
<link rel="stylesheet" href="css/style.css">
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
} else
	marketer_header('Ｐｒｏアンケート', PG_NULL)
?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
					<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
				</tr>
			</table>

			<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="778" align="center" valign="top">
						<table border="0" cellspacing="0" cellpadding="0" width="778">
							<tr>
								<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
								<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
							</tr>
						</table>
						<br>
						<table width="500" border="0" cellpadding="2" cellspacing="0">
							<tr>
								<td><b>■Webプレビュー</b></td>
							</tr>
						</table>
						<br>

						<div align="center">
<?
if (!isset($main) && $pro_enq->finding_flag == DBTRUE) {
	disp_preview($pro_enq, 0, 'ファインディングアンケート', $pro_enq->enquete->get_finding_point());
	disp_preview($pro_enq, 1, '本アンケート', $pro_enq->enquete2->get_enquete_point());
//////jeon_start seq=99//////////////////////
//ファインディングアンケートだけ　プレビュー画面場合
}else if($pro_enq->finding_flag == DBTRUE){
	disp_preview($pro_enq, $main, '', $pro_enq->enquete->get_finding_point());
//////jeon_end seq=99////////////////////////
} else
	disp_preview($pro_enq, $main, '', $pro_enq->enquete->get_enquete_point());

function disp_preview(&$pro_enq, $main, $enquete_kind, $point) {
	if ($main)
		$enquete = &$pro_enq->enquete2;
	else
		$enquete = &$pro_enq->enquete;

	if ($enquete->description == '')
		$description = get_enq_description($pro_enq);
	else
		$description = $enquete->description;

	$description = str_replace('%POINT%', $point, $description);
?>
						<table width="470" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
							<tr>
									<td><table border="0" cellpadding="5" cellspacing="0" width="100%">
<?
	if ($enquete_kind != '') {
?>
										<tr>
											<td bgcolor="#ccffff" colspan="2" align="center"><b><?=htmlspecialchars($enquete_kind)?></b><br>
											</td>
										</tr>
<?
	}
?>
										<tr>
											<td bgcolor="#c3c2ff" colspan="2"><b><?=htmlspecialchars($pro_enq->enquete->title)?></b><br>
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
		foreach ($enquete->question as $qno => $question) {
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
												<b>Q<?=$i?>. <br><?=nl2br(htmlspecialchars($question->question_text))?></b>
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
}

if ($popup) {
?>
						<a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="閉じる" name="Image19" width="108" height="31" border="0"></a>
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
		</td>
		<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
	</tr>
</table>
</body>
</html>
<?
} else
	marketer_footer();
?>
