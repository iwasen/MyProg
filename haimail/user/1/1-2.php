<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/reginfo.php');
include('../inc/sel_profile2.php');

//=== メイン処理 ===
session_start();
$reginfo = new CRegInfo;
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>個人情報・よく行くお店の登録</title>
<SCRIPT LANGUAGE=javascript>
<!--
function ZipKensaku(pfc) {
	open("../10/10-1.php?pfc=" + pfc);
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" action="personal_check.php" name="form1">
		<br>
		<table width="641" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="24">
				<td height="24" width="50" valign="top"></td>
				<td height="24"><img height="26" width="58" src="img/step2.gif" alt="step2">
					<hr>
					<br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="left" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td height="36">
					<div align="left">
						<table width="569" border="0" cellspacing="2" cellpadding="2">
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">メールアドレス<?ErrMark($reginfo->err_mail_adr || $reginfo->err_already_regist)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr" size="30" maxlength=50 value="<?=$reginfo->mail_adr?>"> <font size="2">（半角英数）</font></td>
							</tr>
							<tr>
								<td width="140">　</td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr2" size="30" maxlength=50 value="<?=$reginfo->mail_adr2?>"> <font size="2">（確認のため2回入力してください）</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">パスワード<?ErrMark($reginfo->err_password)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password" size="18" maxlength=14 value="<?=$reginfo->password?>"> <font size="2">（半角英数6文字以上14文字以内）</font></td>
							</tr>
							<tr>
								<td width="140">　</td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password2" size="18" maxlength=14 value="<?=$reginfo->password2?>"> <font size="2">（確認のため2回入力してください）</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">性別<?ErrMark($reginfo->err_seibetsu)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="seibetsu" value="0"<?=RadioChecked($reginfo->seibetsu, "0")?>> <font size="2">男性</font>　　 <input type="radio" name="seibetsu" value="1"<?=RadioChecked($reginfo->seibetsu, "1")?>> <font size="2">女性</font> </td>
								<td width="268">　</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">未既婚<?ErrMark($reginfo->err_mikikon)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="mikikon" value="0"<?=RadioChecked($reginfo->mikikon, "0")?>> <font size="2">独身</font>　　 <input type="radio" name="mikikon" value="1"<?=RadioChecked($reginfo->mikikon, "1")?>> <font size="2">既婚</font> </td>
								<td width="268">　</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">生年月日<?ErrMark($reginfo->err_seinengappi)?></font></td>
								<td colspan="2"><font size="2">西暦</font> <select name="sei_nen" size=1>
										<option></option>
<?
WriteYear($reginfo->sei_nen);
?>
									</select> <font size="2">年</font> 　 <select name="sei_getsu" size="1">
<?
WriteMonth($reginfo->sei_getsu);
?>
									</select> <font size="2">月</font> 　 <select name="sei_hi" size="1">
<?
WriteDay($reginfo->sei_hi);
?>
									</select> <font size="2">日</font><br>
									<font size="2">※ はいめーる.net は１０才以上の方が登録できます。</font>

</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">居住地郵便番号<?ErrMark($reginfo->err_zip_kyojuu)?></font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kyojuu1" size="3" maxlength=3 value="<?=$reginfo->zip_kyojuu1?>"> - <input type="text" name="zip_kyojuu2" size="4" maxlength=4 value="<?=$reginfo->zip_kyojuu2?>"> <font size="2">（半角数字）</font> 　 </td>
								<td width="268"><font size="2"><a href="JavaScript:ZipKensaku('1')">郵便番号が分からない方</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font color="#ff0000" size="2">※現在海外にお住まいの方は、「000−0000」と入力してください。</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">勤務地郵便番号</font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kinmu1" size="3" maxlength=3 value="<?=$reginfo->zip_kinmu1?>"> - <input type="text" name="zip_kinmu2" size="4" maxlength=4 value="<?=$reginfo->zip_kinmu2?>"> <font size="2">（半角数字）</font> 　 　 </td>
								<td width="268"><font size="2"><a href="JavaScript:ZipKensaku('2')">郵便番号が分からない方</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font color="#ff0000" size="2">※現在海外で勤務されている方は、「000−0000」と入力してください。</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">職業<?ErrMark($reginfo->err_shokugyou)?></font></td>
								<td colspan="2" align="left" valign="middle"><select name="shokugyou" size="1">
										<option value=""<?=Selected($reginfo->shokugyou, "")?>>お選びください</option>
<?
WriteShokugyou($reginfo->shokugyou);
?>
									</select> </td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">HTMLメール受信設定<?ErrMark($reginfo->err_html_mail)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="radio" name="html_mail" value="1"<?=RadioChecked($reginfo->html_mail, "1")?>> <font size="2">希望する</font> 　　 <input type="radio" name="html_mail" value="0"<?=RadioChecked($reginfo->html_mail, "0")?>> <font size="2">希望しない</font> </td>
							</tr>
						</table><br>
						<br>
						<p>　</div>
				</td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="603" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">よく行くコンビニ</font></td>
									</tr>
								</table></font></td>
							<td width="312" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">よく行くスーパー</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234"><font size="2">あなたのよく行くコンビニを<br>
								いくつでもチェックしてください。</font></td>
							<td width="312"><font size="2">あなたのよく行くスーパーを<br>
								いくつでもチェックしてください</font> </td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise("01", $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="312"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise("02", $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="604" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234">　</td>
							<td width="313">　</td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"></td>
							<td colspan="2" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="465">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">その他のよく行くお店</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234" align="left" valign="top"><font size="2">あなたがよく利用する業態やサービスを<br>
								いくつでもチェックしてください。</font></td>
							<td width="313"><font size="2">あなたがよく利用するお店やサービスを<br>
								いくつでもチェックしてください。</font></td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><table width="225" border="0" cellspacing="0" height="220" cellpadding="0">
<?
WriteYokuikuMise("03", $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="313" valign="top" align="left"><table width="225" border="0" cellspacing="0" height="312">
<?
WriteYokuikuMise("04", $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><br>
					<br>
					<table width="606" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="468">
									<tr>
										<td bgcolor="#b2cfef"><font size="2">あなたはどっち派？<br>
											</font></td>
									</tr>
								</table>あなたはどっち派ですか？一番近いものを１つ選んでください</font><br>
							</td>
						</tr>
						<tr>
							<td width="45"></td>
							<td colspan="2"><table border="0" cellspacing="0">
<?
WriteDocchiHa($reginfo->docchiha);
?>
								</table></td>
						</tr>
					</table><br>
					<br>
					<table width="520" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td rowspan="2" width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><font size="2">「はいめーる・net」への登録は以上です。 <br>
								さて、「はいめーる・net」を運営する株式会社○○○○○○は、 <br>
								「発信する生活者」を応援するお知らせをｅメールでお届けしようと思っています。<br>
								お受け取りになりますか？<?ErrMark($reginfo->err_oshirase_mail)?></font></td>
						</tr>
						<tr height="55">
							<td width="97" height="55"></td>
							<td width="366" height="55"><input type="radio" name="oshirase_mail" value="1"<?=RadioChecked($reginfo->oshirase_mail, "1")?>> <font size="2">はい</font> 　<input type="radio" name="oshirase_mail" value="0"<?=RadioChecked($reginfo->oshirase_mail, "0")?>> <font size="2">いいえ</font> </td>
						</tr>
					</table>
        <br>
        <table width="372" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="45"><img height="16" width="45" src="../img/space.gif"></td>
            <td>
			<!--プレゼントキャンペーン
			<font size="2" color="#cc0033"><b>
              <table border="1" cellpadding="5" cellspacing="0" width="596" bgcolor="#fff6ea">
                <tr>
                  <td>
                    <font size="2" color="#cc0033"><b>スプリングキャンペーン実施中</b><br>
                      </font><font size="2"><br>
                      「はいめーる・net」では、2002年3月18日から2002年5月31日までにメンバー登録された方に<br>
                      スプリングキャンペーンを実施しています。<a href="../present/index.html">⇒詳しくはこちら</a><br>
                      ・「</font><font size="2" color="#ff9b2f">XBOX</font><font size="2">」（１名）<br>
                      ・「</font><font size="2" color="#ff9b2f">XBOX special edition</font><font size="2">」（１名）<br>
                      ・「</font><font size="2" color="#ff9b2f">東京ディズニーシーペアチケット</font><font size="2">」（３組６名）<br>
                      ・「</font><font size="2" color="#ff9b2f">クオカード1000円分</font><font size="2">」（５０名）<br>
                      </font><font size="2">が抽選で当たります。<br>
                    スプリングキャンペーンにご応募される方は、ご希望の商品を選んでください。 </font> <font size="2">
                    <hr width="100%">
                    </font>
                    <table width="500" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <input type="radio" value="1" name="present"<?=RadioChecked($_SESSION['present'], '1')?>>
                        </td>
                        <td><font size="2">「</font><font size="2" color="#ff9b2f">XBOX</font><font size="2">」</font></td>
                        <td>
                          <input type="radio" value="2" name="present"<?=RadioChecked($_SESSION['present'], '2')?>>
                        </td>
                        <td><font size="2">「</font><font size="2" color="#ff9b2f">XBOX
                          special edition</font><font size="2">」</font></td>
                      </tr>
                      <tr>
                        <td>
                          <input type="radio" value="3" name="present"<?=RadioChecked($_SESSION['present'], '3')?>>
                        </td>
                        <td><font size="2">「</font><font size="2" color="#ff9b2f">東京ディズニーシーペアチケット</font><font size="2">」</font></td>
                        <td>
                          <input type="radio" value="4" name="present"<?=RadioChecked($_SESSION['present'], '4')?>>
                        </td>
                        <td><font size="2">「</font><font size="2" color="#ff9b2f">クオカード1000円分</font><font size="2">」</font></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              </b></font> 
			  プレゼントキャンペーン-->
			  </td>
          </tr>
          <tr>
            <td width="45"></td>
            <td></td>
          </tr>
        </table>
        <br>
					<table width="520" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td colspan="2"><font size="2">仮登録は終了です。以上でよろしければ、確認ボタンを押してください。</font><br>
								<br>
<!-- 2000/12/01 T.Iyonaga/xx xxxxx 修正ここから -->
<!--
								<input type="submit" value="　戻　る　" name="btn_back"> 　 <input type="submit" value="　確　認　" name="btn_kakunin"> <br>
-->
								<input type="submit" value="　確　認　" name="btn_kakunin">　<input type="submit" value="　戻　る　" name="btn_back"><br>
<!-- 修正ここまで -->
								<br>
							</td>
						</tr>
					</table></td>
			</tr>
		</table>
		</form>
	</body>

</html>
