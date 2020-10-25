<?
$top = './..';
$inc = "$top/../inc";
include("$inc/select.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// セッション処理
if (!isset($_SESSION['ss_regist'])) {
	$_SESSION['ss_regist'] = new monitor_regist;
	$reg = &$_SESSION['ss_regist'];
	$reg->read_db_b($_SESSION['ss_monitor_id']);
	$reg->read_db_l($_SESSION['ss_monitor_id']);
} else
	$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>

<form name="form1" method="post" action="m_update_b_check.php">
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
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■　</font><font color="white">基本情報の更新</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									「</font><font size="2" color="#ff0009">※</font><font size="2">」 のある項目は必須項目となりますので、必ずご記入ください。<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> お名前（漢字）</font></td>
											<td width="141"><font size="2">姓：<input type="text" name="name1" size="13" maxlength="25" <?=value($reg->name1)?>></font></td>
											<td><font size="2">名：</font><input type="text" name="name2" size="13" maxlength="25" <?=value($reg->name2)?>></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> お名前（フリガナ）</font></td>
											<td width="141"><font size="2">セイ：<input type="text" name="name1_kana" size="13" maxlength="25" <?=value($reg->name1_kana)?>></font></td>
											<td><font size="2">メイ：<input type="text" name="name2_kana" size="13" maxlength="25" <?=value($reg->name2_kana)?>></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 生年月日</font></td>
											<td colspan="2">
												<font size="2">
													<input type="text" name="birthday_y" size="4" maxlength="4" <?=value($reg->birthday_y)?>>年　
													<input type="text" name="birthday_m" size="2" maxlength="2" <?=value($reg->birthday_m)?>>月　
													<input type="text" name="birthday_d" size="2" maxlength="2" <?=value($reg->birthday_d)?>>日（半角数字）
													<br>例） 1974年 01月 06日
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 未既婚</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="mikikon" <?=value_checked('1', $reg->mikikon)?>>未婚　
													<input type="radio" name="mikikon" <?=value_checked('2', $reg->mikikon)?>>既婚　
													<input type="radio" name="mikikon" <?=value_checked('3', $reg->mikikon)?>>その他
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 性別</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>男性　
													<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>女性
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> メールアドレス</font></td>
											<td colspan="2"><font size="2"><input type="text" name="mail_addr" size="39" maxlength="100" <?=value($reg->mail_addr)?>><br>携帯メールアドレスでのご登録はご遠慮ください。<br>アンケートにお答えいただくことができません。</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> メールアドレス確認</font></td>
											<td colspan="2"><font size="2"><input type="text" name="mail_addr2" size="39" maxlength="100" <?=value($reg->mail_addr2)?>><br>（コピーはしないで下さい）</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> パスワード</font></td>
											<td colspan="2"><font size="2"><input type="password" name="password" size="14" <?=value($reg->password)?>>（半角英数字6〜20文字まで）</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> パスワード確認</font></td>
											<td colspan="2"><font size="2"><input type="password" name="password2" size="14" <?=value($reg->password2)?>>（半角英数字6〜20文字まで）</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> HTMLメール受信</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $reg->html_mail)?>>受信する　
													<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $reg->html_mail)?>>受信しない
												</font>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> はいめーる受信</font></td>
											<td colspan="2">
												<font size="2">
													<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $reg->haimail_flag)?>>受信する　
													<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $reg->haimail_flag)?>>受信しない
													<br>はいめーるの説明は<a href="../haimail/haimail.php" target="_blank">こちら</a>
												</font>
											</td>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 郵便番号（自宅）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_zip1" size="3" maxlength="3" <?=value($reg->jitaku_zip1)?>>-<input type="text" name="jitaku_zip2" size="4" maxlength="4" <?=value($reg->jitaku_zip2)?>>（半角数字）</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 居住地域（自宅）</font></td>
											<td colspan="2">
												<select name="jitaku_area" size="1"><? select_area('お選び下さい　　　　　', $reg->jitaku_area) ?></select>
											</td>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 職業</font></td>
											<td colspan="2">
												<select name="shokugyou_cd" size="1"><? select_shokugyou('お選び下さい　　　　　', $reg->shokugyou_cd) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 業種</font></td>
											<td colspan="2">
												<select name="gyoushu_cd" size="1"><? select_gyoushu('お選び下さい　　　　　', $reg->gyoushu_cd, 1) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">※</font><font size="2"> 職種</font></td>
											<td colspan="2">
												<select name="shokushu_cd" size="1"><? select_shokushu('お選び下さい　　　　　', $reg->shokushu_cd) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2"> 郵便番号</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_zip1" size="3" maxlength="3" <?=value($reg->kinmu_zip1)?>>-<input type="text" name="kinmu_zip2" size="4" maxlength="4" <?=value($reg->kinmu_zip2)?>>（半角数字）</font></td>
										</tr>
										<tr>
											<td width="119"><font size="2">（通勤・通学先）</font></td>
											<td colspan="2"><font size="2">（通勤・通学先のある方はご記入ください）</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2"> 通勤・通学先地域</font></td>
											<td colspan="2">
												<select name="kinmu_area" size="1"><? select_area('お選び下さい　　　　　', $reg->kinmu_area) ?></select>
											</td>
										</tr>
										<tr>
											<td width="119"></td>
											<td colspan="2"><font size="2">（通勤・通学先のある方はご記入ください）</font></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="　確　認　">
											<input type="reset" value="　元に戻す　">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</form>
</body>
</html>
