<?
$top = './..';
$inc = "$top/../inc";
include("$inc/select.php");
$img = "$top/image";
$inc = "$top/inc";
include("$inc/mn_header.php");
include("$inc/mn_regist.php");


// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	$_SESSION['ss_regist'] = new monitor_regist;
$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>

<!-- コンテンツ本体のソースはこの間に表記 -->
<form name="form1" method="post" action="regist2_check.php">
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="../image/reg2_m.gif" width=600 height=36 border=0 alt="基本情報の登録"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left">
			<SPAN class=fs12_lh120><font color="#666666"><B>まずは、基本情報（お名前など）を登録してください。</B><BR><BR>アンケート依頼のメールや、商品券のお届など、今回登録していただく情報をもとにお届けいたします。<BR>お間違いのないようにお気をつけください。<BR>「<font color="#CC0000">※</font>」 のある項目は必須項目となりますので、必ずご記入ください。<BR><BR></font></span>
		</TD>
	</TR>
	<TR>
		<TD align="left">
			<table border="0" cellpadding="0" cellspacing="0" width="600">
				<tr><td bgcolor="#999999">
					<table border="0" cellpadding="4" cellspacing="1" width="100%">
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120>お名前（漢字）</span></td>
								<td width="141" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>姓：<input type="text" name="name1" size="13" maxlength="25" <?=value($reg->name1)?>></SPAN></td>
								<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>名：</SPAN><input type="text" name="name2" size="13" maxlength="25" <?=value($reg->name2)?>></td>
							</tr>
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> お名前（フリガナ）</SPAN></td>
								<td width="141" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>セイ：<input type="text" name="name1_kana" size="13" maxlength="25" <?=value($reg->name1_kana)?>></SPAN></td>
								<td bgcolor="#FFFFFF"><SPAN class=fs12_lh120>メイ：<input type="text" name="name2_kana" size="13" maxlength="25" <?=value($reg->name2_kana)?>></SPAN></td>
							</tr>
							<tr>
								<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 生年月日</SPAN></td>
								<td colspan="2" bgcolor="#FFFFFF">
								<SPAN class=fs12_lh120>
								<input type="text" name="birthday_y" size="4" maxlength="4" <?=value($reg->birthday_y)?>>年　
													<input type="text" name="birthday_m" size="2" maxlength="2" <?=value($reg->birthday_m)?>>月　
													<input type="text" name="birthday_d" size="2" maxlength="2" <?=value($reg->birthday_d)?>>日（半角数字）
													<br>例） 1974年 01月 06日
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 未既婚</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="mikikon" <?=value_checked('1', $reg->mikikon)?>>未婚　
													<input type="radio" name="mikikon" <?=value_checked('2', $reg->mikikon)?>>既婚　
													<input type="radio" name="mikikon" <?=value_checked('3', $reg->mikikon)?>>その他
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 性別</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>男性　
													<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>女性
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> メールアドレス</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2"><SPAN class=fs12_lh120><input type="text" name="mail_addr" size="39" maxlength="100" <?=value($reg->mail_addr)?>><br>携帯メールアドレスでのご登録はご遠慮ください。<br>アンケートにお答えいただ>くことができません。</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> メールアドレス<BR>&nbsp;&nbsp;&nbsp;&nbsp;確認</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="mail_addr2" size="39" maxlength="100" <?=value($reg->mail_addr2)?>><br>（コピーはしないで下さい）</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> パスワード</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="password" name="password" size="14" <?=value($reg->password)?>>（半角英数字6〜20文字まで）</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> パスワード確認</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="password" name="password2" size="14" <?=value($reg->password2)?>>（半角英数字6〜20文字まで）</SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> HTMLメール受信</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $reg->html_mail)?>>受信する　
													<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $reg->html_mail)?>>受信しない
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> はいめーる受信</SPAN></td>
											<td bgcolor="#FFFFFF" colspan="2">
												<SPAN class=fs12_lh120>
													<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $reg->haimail_flag)?>>受信する　
													<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $reg->haimail_flag)?>>受信しない
													<br>はいめーるの説明は<a href="../haimail/haimail.php" target="_blank">こちら</a>
												</SPAN>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 郵便番号（自宅）</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="jitaku_zip1" size="3" maxlength="3" <?=value($reg->jitaku_zip1)?>>-<input type="text" name="jitaku_zip2" size="4" maxlength="4" <?=value($reg->jitaku_zip2)?>>（半角数字）　<a href="<?=$top?>/../common/zip_search.php?pfc=1" target="zip_search">郵便番号が分からない方</a></SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 居住地域（自宅）</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="jitaku_area" size="1"><?select_area('お選び下さい　　　　　', $reg->jitaku_area)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 職業</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="shokugyou_cd" size="1"><?select_shokugyou('お選び下さい　　　　　', $reg->shokugyou_cd)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 業種</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="gyoushu_cd" size="1"><?select_gyoushu('お選び下さい　　　　　', $reg->gyoushu_cd, 1)?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120><font color="#ff0009">※</font></SPAN><SPAN class=fs12_lh120> 職種</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="shokushu_cd" size="1"><?select_shokushu('お選び下さい　　　　　', $reg->shokushu_cd)?></select>
											</td>
										</tr>
<tr>                                                                                        <td colspan="3"><SPAN class=fs12_lh120><font color="#FFFFFF">▼通勤・
通学先&nbsp;&nbsp;&nbsp;※通勤・通学先のある方はご記入ください）</SPAN></SPAN></td>
                                                                                </tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120> 郵便番号</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>>-<input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>（半角数字）　<a href="<?=$top?>/../common/zip_search.php?pfc=2" target="zip_search">郵便番号が分からない方</a></SPAN></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><SPAN class=fs12_lh120> 通勤・通学先地域</SPAN></td>
											<td colspan="2" bgcolor="#FFFFFF">
												<select name="kinmu_area" size="1"><?select_area('お選び下さい　　　　　', $reg->kinmu_area)?></select><BR>
<SPAN class=fs12_lh120>（通勤・通学先のある方はご記入ください）</SPAN>
											</td>
										</tr>
										<tr>
											<td colspan="3" align="center" bgcolor="#FFFFFF">
										<input type="submit" value="　次　へ　">
										<input type="reset" value="　クリア　">
											</td>
										</tr>
									</table>

										</td>
										</tr>
									</table>

	</TD>
	</TR>
</SPAN></SPAN>
	</TBODY>
	</TABLE>
<DIV align="left"><img height="75" width="600" src="../image/reg_flow02.gif" border="0" alt="基本情報の入力" vspace="15"></DIV>
</TD>
</TR>
</TBODY>
</TABLE>
</form>

<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
