<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// セッション処理
if (!isset($_SESSION['ss_regist']))
	redirect('m_update.php');
$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　プロフィール</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<form>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■</font><font color="white">基本情報の確認</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b>以下の通りに更新します。<br>
									<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">お名前（漢字）</font></td>
											<td width="141"><font size="2">姓：<?=htmlspecialchars($reg->name1)?></font></td>
											<td width="166"><font size="2">名：<?=htmlspecialchars($reg->name2)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">お名前（フリガナ）</font></td>
											<td width="141"><font size="2">セイ：<?=htmlspecialchars($reg->name1_kana)?></font></td>
											<td width="166"><font size="2">メイ：<?=htmlspecialchars($reg->name2_kana)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">生年月日</font></td>
											<td colspan="2"><font size="2"><?=$reg->birthday_y?>年 <?=$reg->birthday_m?>月 <?=$reg->birthday_d?>日</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">未既婚</font></td>
											<td width="141"><font size="2"><?=decode_mikikon($reg->mikikon)?></font></td>
											<td width="166"></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">性別</font></td>
											<td width="141"><font size="2"><?=decode_sex($reg->sex)?></font></td>
											<td width="166"></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">メールアドレス</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($reg->mail_addr)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">パスワード</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($reg->password)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">HTMLメール受信</font></td>
											<td colspan="2"><font size="2"><?=decode_html_mail($reg->html_mail)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">はいめーる受信</font></td>
											<td colspan="2"><font size="2"><?=decode_haimail_flag($reg->haimail_flag)?></font></td>
										</tr>
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">郵便番号（自宅）</font></td>
											<td colspan="2"><font size="2"><?=$reg->jitaku_zip1?>-<?=$reg->jitaku_zip2?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">居住地域（自宅）</font></td>
											<td colspan="2"><font size="2"><?=decode_area($reg->jitaku_area)?></font></td>
										</tr>
										<tr>
											<td colspan="3">
												<hr>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">職業</font></td>
											<td colspan="2"><font size="2"><?=decode_shokugyou($reg->shokugyou_cd)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">業種</font></td>
											<td colspan="2"><font size="2"><?=decode_gyoushu($reg->gyoushu_cd)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">職種</font></td>
											<td colspan="2"><font size="2"><?=decode_shokushu($reg->shokushu_cd)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">郵便番号</font></td>
											<td colspan="2"><font size="2"><?=$reg->kinmu_zip1?>-<?=$reg->kinmu_zip2?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2">通勤・通学先地域</font></td>
											<td colspan="2"><font size="2"><?=decode_area($reg->kinmu_area)?></font></td>
										</tr>
									</table><font size="2"><br>
									</font></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="button" value="生活情報の変更" onclick="location.href='m_update_l.php'">
											<input type="button" value="　戻　る　" onclick="location.href='m_update_b.php'">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</body>
</html>
