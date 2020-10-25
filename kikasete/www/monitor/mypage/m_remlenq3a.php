<?
header("P3P: CP='UNI CUR OUR'");
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/common.php");
include("$inc/mn_tbl.php");
include("$inc/search.php"); // 説明文 2005/03/16 BTI
include("$inc/pro_enquete.php"); // 説明文 2005/03/16 BTI
include("$inc/mye_temp.php"); // 説明文 2005/03/16 BTI
$inc = "$top/inc";
$img = "$top/image";
//include("$inc/mn_mypage.php");
//include("$inc/mn_header2.php");
include("$inc/mn_error_close.php");
include("$inc/mn_enquete.php");
include("$inc/mn_error.php");
include("$inc/mn_request.php");
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

$monitor_id = $_SESSION['ss_monitor_id'];
$ret_url = $_SESSION['ss_ret_url'];

// infoQ側の時、「回答を送る」後QueryStringのidを保持。mn_request.phpのidチェックのせいで  2006/03/3 BTI
if ( $_SESSION['ss_req_flg'] == 'g' ) {
	$g_id = 'g' . $monitor_id . 'g' . $enqId;
}


// アンケートIDが無ければトップへ戻す
if ($enquete_id == '') {
	$msg[] = 'アンケートが選択されていないようです。';
}

// テストか本番か識別 2006/03/3 BTI
$isTesting = false;
if (!$msg) {
	$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id or mep_enquete2_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ( $fetch->mep_enquete_id == $fetch->mep_enquete2_id ) { // t_enqeute->en_enq_kind is 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$enquete_id"; //ファインディングアンケートが
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 6 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // ファインディングアンケートでデータ格納確認中なら
					$isTesting = true;
				}
			}
		} else { // current enquete_id->en_enq_kind is 5 or 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$fetch->mep_enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 5 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // ファインディングアンケートでデータ格納確認中なら
					$isTesting = true;
				}
			}
		}
	}
}

if (!$msg) {
	// 回答済みならメッセージ表示
	$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$msg[] = 'あなたは既にこのアンケートに回答されているようです。';
	} else {

		/* 修正前
		// 該当のアンケートが無ければトップへ戻す
		$sql = "SELECT en_enquete_id FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status IN (5,10) AND en_enq_kind IN (2,4,5,6) AND en_enq_type=2";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0) {
			$msg[] = '選択されたアンケートは有効ではありません。';
		}
		*/
		// 修正後
		if ( !$isTesting ) {
			// 該当のアンケートが無ければトップへ戻す  2006/03/3 BTI
			$sql = "SELECT en_enquete_id FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status IN (5,10) AND en_enq_kind IN (2,4,5,6) AND en_enq_type=2";
			$result = db_exec($sql);
			if (pg_numrows($result) == 0) {
				$msg[] = '選択されたアンケートは有効ではありません。';
			}
		}
		// 修正済み


		// アンケート内容取得
		$enquete = new enquete_class;
		$enquete->read_db($enquete_id);

		// 説明文の表示
		if ( $isTesting ) {
			if ($enquete->description == '') {
				$sql = "SELECT mep_marketer_id,mep_pro_enq_no FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id or mep_enquete2_id=$enquete_id";
				$result = db_exec($sql);

				if (pg_numrows($result)) {
					$fetch = pg_fetch_object($result, 0);

					$pro_enq = new pro_enquete_class();
					$pro_enq->read_db($fetch->marketer_id,$fetch->pro_enq_no);

					$enquete->description =get_enq_description($pro_enq);
				}
			}
		}

		// PROアンケートサンプル数上限チェック
		$onLoad = '""';
		if ($enquete->enq_kind == 6) {
			if ( !$isTesting ) {
				if (!check_pro_enquete($monitor_id, $enquete_id, $enquete->start_date)) {
//                    $onLoad = '"window.close;"';
					$onLoad = '"self.opener = self;self.close();"';
				}
			}
			// GMOかつ本アンケート前ファインディングアンケート終了
			if ($_SESSION['ss_req_flg'] == 'g' && $_SESSION['ss_prev_kind'] == '5') {
				$ret_url .= "&status=2";
				$onLoad = '"opener_redirect()"';
			}
		}

		if (!isset($nqno)) {
			$_SESSION['ss_answer'] = array();
			$nqno = 1;
		}
	}
}
if( !$msg ) {
	if( $isTesting ) {
		$sql = "SELECT mn_monitor_id FROM t_test_monitor WHERE mn_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0) {
			$msg[] = 'あなたはこのアンケートに参加していません。';
		}
	}
}

// 2006/04/14
if( $msg ) {
	if( $_SESSION['ss_req_flg'] == 't' && !$isTesting ) {
		$onLoad = '"opener_redirect()"';
	}
}

?>


<?
// きかせて.NET側かGMO側かによって表示形式変更
if ( $_SESSION['ss_req_flg'] == 't' ) {
?>

<!--<? //monitor_menu() ?>-->
<!-- ADD -->
<HTML>
<HEAD>
	<TITLE>きかせて・net</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<script type="text/javascript">
<!--
function opener_redirect(){
	 window.opener.top.location.href="<?=$ret_url;?>";
}
// -->
</script>

</HEAD>
<? // 修正前
/*
?>
<BODY onLoad=<?=$onLoad;?>>
<?
*/
?>
<?
// 修正後
// 格納テスト中はopener_redirect()なし  2006/03/3 BTI
if ( !isset($_SESSION["admin_login_id"]) ) { ?>
<BODY onLoad=<?=$onLoad;?>>
<? }else { ?>
<BODY>
<? }
// 修正済み
?>
<!-- ADD -->
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR bgColor=#c0e2fa>
			<TD><IMG height=29 alt=通常アンケート src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
				<SPAN class=mysttl> 通常アンケート </SPAN>
			</TD>
		</TR>
		<TR>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
		<TR>
			<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
	</TBODY>
</TABLE>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td>
			<table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="white">
				<tr>
					<td>
<?

if (!$msg) {
?>
						<font size="2"><br>
						<form method="post" action="m_remlenq3b.php">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="hidden" name="monitor_id" value="<?=$monitor_id?>">
						<table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="#c8c8c8">
							<tr>
								<td><font size="2"><table border="0" cellpadding="5" cellspacing="0" width="100%">
										<tr>
											<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b><?=htmlspecialchars($enquete->title)?></b> についてのアンケート</font><font size="2"><br>
												</font></td>
										</tr>
<?
if ($nqno == 1) {
?>
										<tr>
											<td colspan="2" bgcolor="white"><font size="2"><br><?=nl2br(htmlspecialchars(str_replace('%POINT%', $enquete->point, $enquete->description)))?><br>
												<br>
												</font></td>
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
											<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2">
												<b>Q<?=$qno?>. <br><?=nl2br(htmlspecialchars($question_text))?></b></font>
<?
			if ($question->url != '') {
?>
												<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
<?
			}
			if ($question->image_id) {
?>
												<br><img src="<?=$top?>/img.php?id=<?=$question->image_id?>" alt="">
<?
			}
?>
											</td>
										</tr>
										<tr>
											<td bgcolor="white" width="7%" valign="top"><font size="2">A<?=$qno?>.</font></td>
											<td bgcolor="white" width="93%"><font size="2">
<?
			switch ($question->question_type) {
			case 1:     // 単一選択
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
			case 2:     // 複数選択
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
			case 3:     // 自由回答
?>
												<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
												<br><br>ご自由にご記入ください。
<?
				break;
			case 4:     // マトリクス(SA)
?>
												<table width="100%">
													<tr>
														<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
														<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
													</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
													<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
														<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
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
			case 5:     // マトリクス(MA)
?>
												<table width="100%">
													<tr>
														<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
														<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
<?
				}
?>
													</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
													<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
														<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
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
												<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>"> <?=htmlspecialchars($question->post_text)?>
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
											</font></td>
										</tr>
<?
		}
	}
}

// Myパートナーの追加質問
if ($enquete->enq_kind == 4) {
?>
										<tr>
											<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2">
												<b>ご本人さまの情報</b></font>
											</td>
										</tr>
										<tr>
											<td bgcolor="white" colspan="2" align="center"><font size="2">
												<table cellpadding="2">
<?
	$sql = "SELECT ppj_policy FROM t_mp_pjt WHERE ppj_enquete_id=$enquete_id";
	if (db_fetch1($sql) == 2) {
?>
													<tr>
														<td><font size="2">ニックネーム</font></td>
														<td><input type="text" name="nickname"></td>
													</tr>
<?
	}
?>
													<tr>
														<td><font size="2">勤務先業種</font></td>
														<td><select name="gyoushu1"><? select_kinmu_gyoushu() ?></select></td>
													</tr>
													<tr>
														<td valign="top"><font size="2">家族構成</font></td>
														<td valign="top">
															<table cellpadding="2" cellspacing="0">
																<tr>
																	<td align="center"><font size="2">続柄（例：夫）</font></td>
																	<td align="center"><font size="2">年齢</font></td>
																	<td align="center"><font size="2">勤務先業種</font></td>
																</tr>
<?
	for ($i = 0; $i < 5; $i++) {
?>
																<tr>
																	<td><font size="2"><input type="text" name="zokugara[<?=$i?>]" size=10></font></td>
																	<td><font size="2"><input type="text" name="age[<?=$i?>]" size=4>才</font></td>
																	<td><font size="2"><select name="gyoushu2[<?=$i?>]"><? select_kinmu_gyoushu() ?></select></font></td>
																</tr>
<?
	}
?>
															</table>
														</td>
													</tr>
												</table>
											</font></td>
										</tr>
<?
}
?>
									</table></font></td>
							</tr>
						</table>
						<br>
						</font>
						<hr width="100%">
						<font size="2"><br>
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
						<br>
						<br>
						<br>
						</font>
						</form>
<?
//} else {
//?>
<?//                        <br><?=//$msg[0]?><br>
<?//                        <hr width="100%">
//                        <form method="post" action="m_index.php" target="_top">
//                        <input type="submit" value="　戻る　">
//                        </form>
?>
<?
//}
?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- ADD -->
</BODY>
</HTML>
<?
} else
	error_msg_and_close($msg);
?>



<?
} // if ( $_SESSION['ss_req_flg'] == 't' )
else { // GMO(infoq)
	if( !$msg ) {
?>
		<HTML>
		<HEAD>
			<TITLE><?=htmlspecialchars($enquete->title)?>　についてのアンケート</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=EUC-JP">

		<STYLE type=text/css>
		div { margin:0; }
		.font-qa {FONT-SIZE: 12pt; COLOR: black}
		</STYLE>
		<script type="text/javascript">
		<!--
		function opener_redirect(){
			window.parent.opener.top.location.href="<?=$ret_url;?>";
		}
		function showQuestion(){
			document.getElementById("title").style.display = "none";
			document.getElementById("question").style.display = "block";
		}
		// -->
		</script>
		</HEAD>

		<?
/*
		// 格納テスト中はopener_redirect()なし  2006/03/09 BTI
		if ( !$isTesting  || ($_SESSION['ss_req_flg'] == 'g'&& $_SESSION['ss_prev_kind'] == '6') ) {
		?>
		<BODY onLoad=<?=$onLoad;?>>
		<? }else { ?>
		<BODY>
		<? }
*/
		?>
		<BODY>

		<?
		// 質問二番目からは $_GET['nqno']がURLについてるのでこれを使う
		if ( !(isset($_GET["nqno"]) || isset($_GET["k"]) || !isset($enquete)) ) {
		?>
		<div id="title">
		<TABLE cellSpacing=0 cellPadding=10 align=center border=1>
			<TBODY>
			<TR bgColor=#c5ff59>
				<TD>
					<FONT style="FONT-SIZE: 14px">
						<B>『<?=htmlspecialchars($enquete->title);?>』についてのアンケートへのご協力をお願いいたします。</B><BR><BR>
									皆様の貴重なご意見をお聞かせください。<BR><BR>
									◆アンケートへご協力いただく皆様へのお願い
						<HR>
								 　 ・アンケートへの回答は、ご納得の上、任意にご参加ください。<BR>
									・不正なご回答はお控えください。<BR><BR>
									◆ご注意<BR>
						<HR>
									・ブラウザの「戻る」ボタンをご利用になりますと、ご回答内容が<BR>
									消えてしまう可能性がございます。<BR>
									・回答中にしばらく(60分程度)何もしないままにしておくと、引き続き<BR>
									ご回答いただけなくなります。<BR>
									※アンケート実施中であれば再度ログインすることでご回答いただけます。<BR><BR>
					</FONT>
					<TABLE borderColor=#009933 height=50 cellSpacing=0 cellPadding=10 align=center bgColor=#ffffff border=1>
					<TBODY>
					<TR>
						<TD>
							<FONT style="FONT-SIZE: 14px">
								<FONT color=#009933>
									<STRONG>
																モニター規約に基づき、アンケート内で知り得た情報・内容・著作物について<BR>
																掲示板やホームページへの記載を含み、口外・転用することを禁止いたします。
									</STRONG><BR>
															※詳しくはマイページ内、モニター規約をご参照ください。
								</FONT>
							</FONT>
						</TD>
					</TR>
					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TBODY>
		</TABLE>
		<br />
		<center>
			<input type="button" value="　次へ　" onclick="showQuestion();">
		</center>
		</div>

		<div id="question" style="display:none;">
		<?
		} // 質問二番目からは $_GET['nqno']がURLについてるのでこれを使う
		else {
		?>
			<div id="question">
		<?  } ?>

		<font size="2"><br>
		<form method="post" action="m_remlenq3b.php">
		<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
		<?
		// infoQ側の時、「回答を送る」後QueryStringのidを保持。mn_request.phpのidチェックのせいで
		if(isset($g_id)) {?>
			<input type="hidden" name="id" value="<?=$g_id;?>">
		<? }
 ?>

			<!-- 設問 -->
			<table border=0 cellpadding=3 cellspacing=2 width='100%'>
				<tr>
					<td class=font-qa>
						<b><?=htmlspecialchars($enquete->title)?></b>についてのアンケート<br /><br />
						<hr border="3">
					</td>
				</tr>
			</TABLE>

			<!-- 選択肢 -->
			<table border=0 cellpadding=3 cellspacing=2 width='100%'>
			  <tr>
				<td width=5%>　</td>
				<td class=font-qa align=left>
				<table border=0 cellpadding=3 cellspacing=2 width=90%>
					<tr>
						<td valign=top>
							<table border=0 cellpadding=3 cellspacing=2 width=90%>
		<?

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
								<td class=font-qa>
									<b>【Ｑ.<?=$qno?>】 <?=nl2br(htmlspecialchars($question_text))?></b>
					<?
					if ($question->url != '') {
					?>
									<br><a href="<?=$question->url?>" target="_blank"><?=htmlspecialchars($question->url)?></a>
					<?
					}
					if ($question->image_id) {
					?>
									<br><img src="<?=$top?>/img.php?id=<?=$question->image_id?>" alt="">
					<?
					}
					?>
								</td>
							</tr>
							<tr>
								<td class="font-qa">
		<?
					switch ($question->question_type) {
					case 1:     // 単一選択
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
					case 2:     // 複数選択
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
					case 3:     // 自由回答
		?>
									<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
									<br><br>ご自由にご記入ください。
		<?
						break;
					case 4:     // マトリクス(SA)
		?>
									<table width="100%">
										<tr>
											<td>&nbsp;</td>
		<?
						foreach ($question->hyoutou as $tno => $hyoutou) {
		?>
											<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
		<?
						}
		?>
										</tr>
		<?
						foreach ($question->hyousoku as $sno => $hyousoku) {
		?>
										<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
											<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
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
					case 5:     // マトリクス(MA)
		?>
									<table width="100%">
										<tr>
											<td>&nbsp;</td>
		<?
						foreach ($question->hyoutou as $tno => $hyoutou) {
		?>
											<td align="center" bgcolor="#ffddee"><font size="2"><?=htmlspecialchars($hyoutou)?></font></td>
		<?
						}
		?>
										</tr>
		<?
						foreach ($question->hyousoku as $sno => $hyousoku) {
		?>
										<tr bgcolor="<?=$sno % 2 ? '#ddeeff' : '#ddffee'?>">
											<td><font size="2"><?=htmlspecialchars($hyousoku)?></font></td>
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
									<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>"> <?=htmlspecialchars($question->post_text)?>
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
				} // if ($question->question_text != '')
			}
		}
		?>
							</table>
						</td>
					</tr>
				</table>
				</td>
			  </tr>
			</table>

			<br>
			<center>
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
			</center>
			<input type="hidden" name="first_qno" <?=value($nqno)?>>
			<input type="hidden" name="last_qno" <?=value($last_qno)?>>

			</form>
		</BODY>
		</HTML>
	<?
	} else {
		error_msg_and_close($msg);
	}

} // else { // GMO(infoq)
?>
