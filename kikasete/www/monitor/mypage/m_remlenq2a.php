<?
$top = './..';
$inc = "$top/../inc";
include("$inc/encrypt.php");
include("$inc/mail.php");
include("$inc/str_replace.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// アンケートIDが無ければトップへ戻す 2007/09/20 edit by namik redirect先変更
if ($enquete_id == '')
//	redirect('m_index.php');
	redirect('m_index_main.php');

// 該当のアンケートが無ければトップへ戻す 2007/09/20 edit by namik redirect先変更、enenq_typeを削除
$sql = "SELECT en_mail_subject,en_mail_contents,en_job_id FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status=5";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
//	redirect('m_index.php');
	redirect('m_index_main.php');
$fetch = pg_fetch_object($result, 0);
$subject = $fetch->en_mail_subject;
$body = $fetch->en_mail_contents;
$job_id = $fetch->en_job_id;

// モニター情報取得 2007/09/20 edit by namik redirect先変更
// %CLICK%の差込先を追加 2008/02/04 edit by sudoh

$monitor_id = $_SESSION['ss_monitor_id'];
$sql = "SELECT mn_mail_addr,mn_name1 FROM t_monitor WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
//	redirect('m_index.php');
	redirect('m_index_main.php');
$fetch = pg_fetch_object($result, 0);
$mail_addr = $fetch->mn_mail_addr;

$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
$body = str_replace('%MONITOR_NAME%', $fetch->mn_name1, $body);
$body = str_replace('%CLICK%', $monitor_id, $body);

if ($job_id) {
	$sql = "SELECT jb_search_id,jb_mail_format FROM t_job WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);
		replace_kyoumi_genre($fetch->jb_mail_format, $fetch->jb_search_id, $body);
	}
}

$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail');
$enq_account = strtok($fetch->sy_enq_account, '%');
$enquete_param = encrypt_param($enquete_id, $monitor_id);
$from = "$fetch->sy_enq_mail <$enq_account-$enquete_param@$fetch->sy_enq_domain>";
send_mail($subject, $mail_addr, $from, $body);
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
								<td bgcolor="#525294"><font color="#ffcc00">■</font><font color="white">メールアンケート再送完了</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br></b></font><font color="#2b2b63"><b>【<?=$mail_addr?>】へアンケートメールを再送いたしました。</b></font><font size="2"><br>ご使用のメールソフトをご確認いただき、ぜひアンケートへご回答ください。</font> <br><br></td>
							</tr>
							<tr>
								<td>
									<hr>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
