<?
header("P3P: CP='UNI CUR OUR'");
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/common.php");
include("$inc/mn_tbl.php");
include("$inc/search.php"); // ����ʸ 2005/03/16 BTI
include("$inc/pro_enquete.php"); // ����ʸ 2005/03/16 BTI
include("$inc/mye_temp.php"); // ����ʸ 2005/03/16 BTI
$inc = "$top/inc";
$img = "$top/image";
//include("$inc/mn_mypage.php");
//include("$inc/mn_header2.php");
include("$inc/mn_error_close.php");
include("$inc/mn_enquete.php");
include("$inc/mn_error.php");
include("$inc/mn_request.php");
// ��̳��ȼ�������
function select_kinmu_gyoushu() {
	echo "<option value='' selected>�����Ӥ�������</option>\n";

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

// infoQ¦�λ����ֲ���������׸�QueryString��id���ݻ���mn_request.php��id�����å��Τ�����  2006/03/3 BTI
if ( $_SESSION['ss_req_flg'] == 'g' ) {
	$g_id = 'g' . $monitor_id . 'g' . $enqId;
}


// ���󥱡���ID��̵����Хȥåפ��᤹
if ($enquete_id == '') {
	$msg[] = '���󥱡��Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';
}

// �ƥ��Ȥ����֤����� 2006/03/3 BTI
$isTesting = false;
if (!$msg) {
	$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id or mep_enquete2_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ( $fetch->mep_enquete_id == $fetch->mep_enquete2_id ) { // t_enqeute->en_enq_kind is 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$enquete_id"; //�ե�����ǥ��󥰥��󥱡��Ȥ�
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 6 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // �ե�����ǥ��󥰥��󥱡��Ȥǥǡ�����Ǽ��ǧ��ʤ�
					$isTesting = true;
				}
			}
		} else { // current enquete_id->en_enq_kind is 5 or 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$fetch->mep_enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 5 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // �ե�����ǥ��󥰥��󥱡��Ȥǥǡ�����Ǽ��ǧ��ʤ�
					$isTesting = true;
				}
			}
		}
	}
}

if (!$msg) {
	// �����Ѥߤʤ��å�����ɽ��
	$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$msg[] = '���ʤ��ϴ��ˤ��Υ��󥱡��Ȥ˲�������Ƥ���褦�Ǥ���';
	} else {

		/* ������
		// �����Υ��󥱡��Ȥ�̵����Хȥåפ��᤹
		$sql = "SELECT en_enquete_id FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status IN (5,10) AND en_enq_kind IN (2,4,5,6) AND en_enq_type=2";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0) {
			$msg[] = '���򤵤줿���󥱡��Ȥ�ͭ���ǤϤ���ޤ���';
		}
		*/
		// ������
		if ( !$isTesting ) {
			// �����Υ��󥱡��Ȥ�̵����Хȥåפ��᤹  2006/03/3 BTI
			$sql = "SELECT en_enquete_id FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status IN (5,10) AND en_enq_kind IN (2,4,5,6) AND en_enq_type=2";
			$result = db_exec($sql);
			if (pg_numrows($result) == 0) {
				$msg[] = '���򤵤줿���󥱡��Ȥ�ͭ���ǤϤ���ޤ���';
			}
		}
		// �����Ѥ�


		// ���󥱡������Ƽ���
		$enquete = new enquete_class;
		$enquete->read_db($enquete_id);

		// ����ʸ��ɽ��
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

		// PRO���󥱡��ȥ���ץ����¥����å�
		$onLoad = '""';
		if ($enquete->enq_kind == 6) {
			if ( !$isTesting ) {
				if (!check_pro_enquete($monitor_id, $enquete_id, $enquete->start_date)) {
//                    $onLoad = '"window.close;"';
					$onLoad = '"self.opener = self;self.close();"';
				}
			}
			// GMO�����ܥ��󥱡������ե�����ǥ��󥰥��󥱡��Ƚ�λ
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
			$msg[] = '���ʤ��Ϥ��Υ��󥱡��Ȥ˻��ä��Ƥ��ޤ���';
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
// ��������.NET¦��GMO¦���ˤ�ä�ɽ�������ѹ�
if ( $_SESSION['ss_req_flg'] == 't' ) {
?>

<!--<? //monitor_menu() ?>-->
<!-- ADD -->
<HTML>
<HEAD>
	<TITLE>�������ơ�net</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<script type="text/javascript">
<!--
function opener_redirect(){
	 window.opener.top.location.href="<?=$ret_url;?>";
}
// -->
</script>

</HEAD>
<? // ������
/*
?>
<BODY onLoad=<?=$onLoad;?>>
<?
*/
?>
<?
// ������
// ��Ǽ�ƥ������opener_redirect()�ʤ�  2006/03/3 BTI
if ( !isset($_SESSION["admin_login_id"]) ) { ?>
<BODY onLoad=<?=$onLoad;?>>
<? }else { ?>
<BODY>
<? }
// �����Ѥ�
?>
<!-- ADD -->
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR bgColor=#c0e2fa>
			<TD><IMG height=29 alt=�̾異�󥱡��� src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
				<SPAN class=mysttl> �̾異�󥱡��� </SPAN>
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
		<td width="15">��</td>
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
											<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2"><b><?=htmlspecialchars($enquete->title)?></b> �ˤĤ��ƤΥ��󥱡���</font><font size="2"><br>
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
		// �������ޤǥ����å�
		if ($qno < $nqno)
			continue;

		$question = &$enquete->question[$qno];

		// ���ڡ�������ޤ���ʬ����郎����в��ڡ���
		if (!$first && ($question->page_break == DBTRUE || isset($enquete->branch_cond[$qno]))) {
			$next_page = true;
			break;
		}

		$first = false;

		if ($question->question_text != '') {
			$last_qno = $qno;

			$question_text = $question->question_text;

			if (isset($enquete->branch_cond[$qno]))
				$question_text = $enquete->branch_cond_text($qno, '����������ˤ�ʹ�����ޤ���') . "\n\n". $question_text;
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
			case 1:     // ñ������
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
			case 2:     // ʣ������
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
			case 3:     // ��ͳ����
?>
												<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
												<br><br>����ͳ�ˤ���������������
<?
				break;
			case 4:     // �ޥȥꥯ��(SA)
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
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			case 5:     // �ޥȥꥯ��(MA)
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
								��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
<?
				}
				break;
			case 6:     // ���̲���
?>
												<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>"> <?=htmlspecialchars($question->post_text)?>
<?
				break;
			case 7:		// �ץ������
?>
											<select name="answer<?=$qno?>">
												<option value="">���򤷤Ƥ�������</option>
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
			case 8:		// �ե꡼�����ʣ��ԡ�
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

// My�ѡ��ȥʡ����ɲü���
if ($enquete->enq_kind == 4) {
?>
										<tr>
											<td bgcolor="#c3c2ff" colspan="2"><font color="#2b2b63" size="2">
												<b>���ܿͤ��ޤξ���</b></font>
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
														<td><font size="2">�˥å��͡���</font></td>
														<td><input type="text" name="nickname"></td>
													</tr>
<?
	}
?>
													<tr>
														<td><font size="2">��̳��ȼ�</font></td>
														<td><select name="gyoushu1"><? select_kinmu_gyoushu() ?></select></td>
													</tr>
													<tr>
														<td valign="top"><font size="2">��²����</font></td>
														<td valign="top">
															<table cellpadding="2" cellspacing="0">
																<tr>
																	<td align="center"><font size="2">³�����㡧�ס�</font></td>
																	<td align="center"><font size="2">ǯ��</font></td>
																	<td align="center"><font size="2">��̳��ȼ�</font></td>
																</tr>
<?
	for ($i = 0; $i < 5; $i++) {
?>
																<tr>
																	<td><font size="2"><input type="text" name="zokugara[<?=$i?>]" size=10></font></td>
																	<td><font size="2"><input type="text" name="age[<?=$i?>]" size=4>��</font></td>
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
						<input type="submit" value="�����ء�">
<?
} else {
?>
						<input type="submit" value="�����������롡">
<?
}
?>
						<input type="reset" value="�����ꥢ��">
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
//                        <input type="submit" value="����롡">
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
			<TITLE><?=htmlspecialchars($enquete->title)?>���ˤĤ��ƤΥ��󥱡���</TITLE>
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
		// ��Ǽ�ƥ������opener_redirect()�ʤ�  2006/03/09 BTI
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
		// ���������ܤ���� $_GET['nqno']��URL�ˤĤ��Ƥ�ΤǤ����Ȥ�
		if ( !(isset($_GET["nqno"]) || isset($_GET["k"]) || !isset($enquete)) ) {
		?>
		<div id="title">
		<TABLE cellSpacing=0 cellPadding=10 align=center border=1>
			<TBODY>
			<TR bgColor=#c5ff59>
				<TD>
					<FONT style="FONT-SIZE: 14px">
						<B>��<?=htmlspecialchars($enquete->title);?>�٤ˤĤ��ƤΥ��󥱡��ȤؤΤ����Ϥ򤪴ꤤ�������ޤ���</B><BR><BR>
									���ͤε��Ťʤ��ո���ʹ��������������<BR><BR>
									�����󥱡��Ȥؤ����Ϥ����������ͤؤΤ��ꤤ
						<HR>
								 �� �����󥱡��Ȥؤβ����ϡ���Ǽ���ξ塢Ǥ�դˤ����ä���������<BR>
									�������ʤ������Ϥ���������������<BR><BR>
									�������<BR>
						<HR>
									���֥饦���Ρ����ץܥ�������Ѥˤʤ�ޤ��ȡ����������Ƥ�<BR>
									�ä��Ƥ��ޤ���ǽ�����������ޤ���<BR>
									��������ˤ��Ф餯(60ʬ����)���⤷�ʤ��ޤޤˤ��Ƥ����ȡ�����³��<BR>
									���������������ʤ��ʤ�ޤ���<BR>
									�����󥱡��ȼ»���Ǥ���к��٥����󤹤뤳�ȤǤ��������������ޤ���<BR><BR>
					</FONT>
					<TABLE borderColor=#009933 height=50 cellSpacing=0 cellPadding=10 align=center bgColor=#ffffff border=1>
					<TBODY>
					<TR>
						<TD>
							<FONT style="FONT-SIZE: 14px">
								<FONT color=#009933>
									<STRONG>
																��˥�������˴�Ť������󥱡�������Τ������������ơ�����ʪ�ˤĤ���<BR>
																�Ǽ��Ĥ�ۡ���ڡ����ؤε��ܤ�ޤߡ�������ž�Ѥ��뤳�Ȥ�ػߤ������ޤ���
									</STRONG><BR>
															���ܤ����ϥޥ��ڡ����⡢��˥�������򤴻��Ȥ���������
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
			<input type="button" value="�����ء�" onclick="showQuestion();">
		</center>
		</div>

		<div id="question" style="display:none;">
		<?
		} // ���������ܤ���� $_GET['nqno']��URL�ˤĤ��Ƥ�ΤǤ����Ȥ�
		else {
		?>
			<div id="question">
		<?  } ?>

		<font size="2"><br>
		<form method="post" action="m_remlenq3b.php">
		<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
		<?
		// infoQ¦�λ����ֲ���������׸�QueryString��id���ݻ���mn_request.php��id�����å��Τ�����
		if(isset($g_id)) {?>
			<input type="hidden" name="id" value="<?=$g_id;?>">
		<? }
 ?>

			<!-- ���� -->
			<table border=0 cellpadding=3 cellspacing=2 width='100%'>
				<tr>
					<td class=font-qa>
						<b><?=htmlspecialchars($enquete->title)?></b>�ˤĤ��ƤΥ��󥱡���<br /><br />
						<hr border="3">
					</td>
				</tr>
			</TABLE>

			<!-- ����� -->
			<table border=0 cellpadding=3 cellspacing=2 width='100%'>
			  <tr>
				<td width=5%>��</td>
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
				// �������ޤǥ����å�
				if ($qno < $nqno)
					continue;

				$question = &$enquete->question[$qno];

				// ���ڡ�������ޤ���ʬ����郎����в��ڡ���
				if (!$first && ($question->page_break == DBTRUE || isset($enquete->branch_cond[$qno]))) {
					$next_page = true;
					break;
				}

				$first = false;

				if ($question->question_text != '') {
					$last_qno = $qno;

					$question_text = $question->question_text;

					if (isset($enquete->branch_cond[$qno]))
						$question_text = $enquete->branch_cond_text($qno, '����������ˤ�ʹ�����ޤ���') . "\n\n". $question_text;
		?>
							<tr>
								<td class=font-qa>
									<b>�ڣ�.<?=$qno?>�� <?=nl2br(htmlspecialchars($question_text))?></b>
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
					case 1:     // ñ������
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
					case 2:     // ʣ������
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
					case 3:     // ��ͳ����
		?>
									<textarea name="answer<?=$qno?>" cols="50" rows="15"></textarea>
									<br><br>����ͳ�ˤ���������������
		<?
						break;
					case 4:     // �ޥȥꥯ��(SA)
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
										��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
		<?
						}
						break;
					case 5:     // �ޥȥꥯ��(MA)
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
										��Ʊ�������ǽ�ʣ�������򤹤뤳�ȤϤǤ��ޤ����
		<?
						}
						break;
					case 6:     // ���̲���
?>
									<?=htmlspecialchars($question->pre_text)?> <input type="text" name="answer<?=$qno?>"> <?=htmlspecialchars($question->post_text)?>
<?
						break;
					case 7:		// �ץ������
?>
											<select name="answer<?=$qno?>">
												<option value="">���򤷤Ƥ�������</option>
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
					case 8:		// �ե꡼�����ʣ��ԡ�
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
			<input type="submit" value="�����ء�">
		<?
		} else {
		?>
			<input type="submit" value="�����������롡">
		<?
		}
		?>
			<input type="reset" value="�����ꥢ��">
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
