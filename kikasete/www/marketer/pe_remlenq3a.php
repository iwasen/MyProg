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
//include("$inc/mkk_mypage.php");
session_start();
include("$inc/mkk_header.php");

// 勤務先業種の選択肢
function select_kinmu_gyoushu() {
	echo "<option value='' selected>お選びください</option>\n";

	$sql = "SELECT kg_gyoushu_name FROM m_kinmu_gyoushu ORDER BY kg_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option>', htmlspecialchars($fetch->kg_gyoushu_name), '</option>', "\n";
	}
}


// アンケートIDが無ければトップへ戻す
if ($enquete_id == '')
	redirect('pe_index.php');

// アンケート内容取得
$enquete = new enquete_class;
$enquete->read_db($enquete_id);

if (!isset($nqno)) {
	unset($_SESSION['ss_answer']);
	$nqno = 1;
}

if ($enquete->description == '') {
	if ($_SESSION['ss_owner_id'])
		$sql = "SELECT mep_ans_cond FROM t_pro_enquete WHERE mep_marketer_id={$_SESSION['ss_owner_id']} AND mep_pro_enq_no=$pro_enq_no";
	else
		$sql = "SELECT mep_ans_cond FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id";
	$ans_cond = db_fetch1($sql);

	$description = get_enq_description2($enquete, $ans_cond);
} else
	$description = $enquete->description;

if ($enquete->enq_kind == 5)
	$point = $enquete->get_finding_point();
else
	$point = $enquete->get_enquete_point();
$description = str_replace('%POINT%', $point, $description);
?>
<?
if ($_SESSION['ss_marketer_id'])
	marketer_header('Ｐｒｏアンケートwebプレビュー', PG_NULL);
else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
<meta http-equiv="Pragma" content="no-cache">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="js/w_open.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>
</head>
<body>
<?
}
?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="white">
	<tr>
		<td width="15">　</td>
		<td>
			<table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="white">
				<tr>
					<td>
						<br>
						■ＰＲＯアンケート動作確認
						<form method="post" action="pe_remlenq3b.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
							<tr>
								<td><table border="0" cellpadding="5" cellspacing="0" width="100%">
										<tr>
											<td bgcolor="#c3c2ff" colspan="2"><b><?=htmlspecialchars($enquete->title)?></b>についてのアンケート<br>
												</td>
										</tr>
<?
if ($nqno == 1) {
?>
										<tr>
											<td colspan="2" bgcolor="white"><br><?=nl2br(htmlspecialchars($description))?><br>
												<br>
												</td>
										</tr>
<?
}

$next_page = false;
$first = true;

if (is_array($enquete->question)) {
	foreach (array_keys($enquete->question) as $qno) {
		// 指定質問までスキップ
		if ($qno < $nqno)
			continue;

		$question = &$enquete->question[$qno];

		// 改ページ指定または分岐条件があれば改ページ
		if (!$first && ($question->page_break == DBTRUE || isset($enquete->branch_cond[$qno]))) {
			$next_page = true;
			break;
		}

		$first = false;

		if ($question->question_text != '') {
			$last_qno = $qno;

			$question_text = $question->question_text;

			if (isset($enquete->branch_cond[$qno]))
				$question_text = $enquete->branch_cond_text($qno, 'を選んだ方にお聞きします。') . "\n\n". $question_text;
?>
										<tr>
											<td bgcolor="#c3c2ff" colspan="2">
												<b>Q<?=$qno?>. <br><?=nl2br(htmlspecialchars($question_text))?></b>
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
											<td bgcolor="white" width="7%" valign="top">A<?=$qno?>.</td>
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
												<nobr><input type="radio" name="answer<?=$qno?>" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							if ($question->fa_sno == $sno) {
?>
												<input type="text" name="free_answer<?=$qno?>" size=25>
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
												<nobr><input type="checkbox" name="answer<?=$qno?>[]" value="<?=$sno?>"> <?=htmlspecialchars($sel_text)?> </nobr>
<?
							if ($question->fa_sno == $sno) {
?>
												<input type="text" name="free_answer<?=$qno?>" size=25>
<?
							}
						}
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
			case 6:     // 数量回答
?>
											<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>" size="10"> <?=htmlspecialchars($question->post_text)?>
<?
				break;
			case 7:		// プルダウン
?>
											<select name="answer<?=$qno?>">
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
											<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>"> <?=htmlspecialchars($question->post_text)?>
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

						<hr width="100%">
						<br>
<?
if ($next_page) {
?>
						<input type="submit" value="　次へ　">
<?
} else {
?>
						<input type="submit" value="　回答を送る　">
<?
}
?>
						<input type="reset" value="　クリア　">
						<input type="hidden" name="first_qno" <?=value($nqno)?>>
						<input type="hidden" name="last_qno" <?=value($last_qno)?>>
						<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
						<br>
						<br>
						<br>

						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
