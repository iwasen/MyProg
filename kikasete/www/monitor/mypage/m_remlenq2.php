<?
$top = './..';
$inc = "$top/../inc";
include("$inc/format.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// アンケートIDが無ければトップへ戻す
if ($enquete_id == ''){
	redirect('m_index_main.php');
}
// --- SEQ AD3 START -----------------------------------
// WEBアンケートかメールアンケートか区別する
if($en_type==''){
	$en_enq_type_vari=1;
}else{
	$en_enq_type_vari=2;
}
// --- SEQ AD3 END -----------------------------------
//　WEBアンケートかメールアンケートか区別してSQLを処理
// 該当のアンケートが無ければトップへ戻す
$monitor_id = $_SESSION['ss_monitor_id'];
$sql = "SELECT en_title,en_description,en_end_date,en_point"
		. " FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id AND el_monitor_id=$monitor_id"
		. " WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=".$en_enq_type_vari; 
/* --- SEQ AD3 START -----------------------------------		
 * Original SQL
 * $sql = "SELECT en_title,en_description,en_end_date,en_point"
 * . " FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id AND el_monitor_id=$monitor_id"
 * . " WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=1";
 *
 * --- SEQ AD3 END -----------------------------------
 */
$result = db_exec($sql);
if (pg_numrows($result) == 0){
	redirect('m_index_main.php');
}

$fetch = pg_fetch_object($result, 0);
$title = htmlspecialchars($fetch->en_title);
$end_date = format_date($fetch->en_end_date);
$point = number_format($fetch->en_point);
?>
<? monitor_menu() ?>
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
		<td colspan="2">
			<table border="0" cellpadding="20" cellspacing="0" width="90%" bgcolor="white">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■</font><font color="white">回答期限までにお答えくださいね。</font></td>
							</tr>
							<tr>
								<td>
										<font size="2"><br>いつもアンケートにご協力いただき、ありがとうございます。もしよかったら、以下のアンケートにもご協力くださいね。</font><br>
										<table border="0" cellpadding="3" cellspacing="2" width="100%">
											<tr bgcolor="#c3c2ff">
												<td width="203"><font size="2">テーマ</font></td>
												<td width="90"><font size="2">アンケートの種類</font></td>
												<td width="83"><font size="2">回答期間</font></td>
												<td width="42"><font size="2">ポイント</font></td>
											</tr>
											<tr bgcolor="#dedede">
												<td width="203"><font size="2"><?=$title?></font></td>
												<td width="90"><font size="2">
												
												<? // --- SEQ AD3 START ----------------------
													//画面にWEBアンケートかメールアンケートか区別して表示する
												if($en_type==''){ ?>
													メールアンケート
												<? }else{ ?>
													WEB アンケート
												<? }
													// --- SEQ AD3 END -----------------------
												?>
												</font></td>
												<td width="83"><font size="2"><?=$end_date?>まで</font></td>
												<td width="42"><font size="2"><?=$point?>P</font></td>
											</tr>
										</table>
										<font size="2"><br>※きかせて・net 事務局から届いたアンケートメールをなくしてしまったという方は、下記のボタンを押して、メールの再送を催促してください。<br>ただし、回答は１回のみとなっておりますので、ご注意ください。<br><br><br>
									<a href="m_remlenq2a.php?enquete_id=<?=$enquete_id?>&en_type=<?=$en_type?>">メールを再送する</a><br><br>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>