<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

db_begin_trans();

// アフィリエイト申請
$rec['af_monitor_id'] = sql_number($_SESSION['ss_monitor_id']);
$rec['af_affiliate_mgr_id'] = sql_number($affiliate_mgr_id);
$rec['af_web_url'] = sql_char($web_url);
$rec['af_introduction'] = sql_char($introduction);
db_insert('t_affiliate', $rec);
$affiliate_id = get_currval('t_affiliate_af_affiliate_id_seq');

// アフィリエイトカウント
$rec['afc_affiliate_id'] = sql_number($affiliate_id);
db_insert('t_affiliate_cnt', $rec);

// 応募人数カウントアップ
$sql = "UPDATE t_affiliate_mgr SET afm_appl_count=afm_appl_count+1 WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
db_exec($sql);

db_commit_trans();

db_vacuum('t_affiliate_cnt');
db_vacuum('t_affiliate_mgr');
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD>
					<IMG height=29 alt=アフィリエイト src="<?=$img?>/my_affiliate.gif" width=45 align=absMiddle>
					<SPAN class=mysttl>アフィリエイト </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3">
			<table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■　</font><font color="white">きかせて・net紹介　応募フォーム</font></td>
							</tr>
							<tr>
								<td>
										<tr>
											<td><br><font size="2">ご応募ありがとうございました。<br>
											<br>
											これより簡単な審査が行われます。<BR>
											承認状況は、申請後数日中にきかせて･netマイページ内でお知らせさせていただきます。
											</font></td>
										</tr>
										<tr>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td colspan="3" align="center"><input type="button" value="　戻る　" onclick="location.href='m_index_main.php'"></td>
										</tr>
										
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>