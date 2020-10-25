<?
$top = './..';
$inc = "$top/../inc";
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
?>
<? monitor_menu('onload="delivery()"') ?>

<?
$sql = "SELECT mn_name1,mn_name2,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_tel,mp_curr_point,mp_enq_history"
		. " FROM t_monitor JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
		. " WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$name1 = htmlspecialchars($fetch->mn_name1);
	$name2 = htmlspecialchars($fetch->mn_name2);
	$zip1 = substr($fetch->mn_jitaku_zip, 0, 3);
	$zip2 = substr($fetch->mn_jitaku_zip, 3, 4);
	$addr = decode_area($fetch->mn_jitaku_area);
	$tel = explode('-', $fetch->mn_jitaku_tel);
	get_chara_condition($fetch->mp_enq_history, $chara_text);

	$curr_point = number_format($fetch->mp_curr_point);
	$ex_point = floor(($fetch->mp_curr_point - 200) / 1000) * 1000;
	if ($ex_point < 1000)
		$ex_point = 0;
}
?>
<script type="text/javascript">
<!--
function delivery() {
	var f = document.form1;
	var d;	
	if (f.shouhin[0].checked) {
		document.getElementById("caution").innerHTML = "ご記入の必要はありません";
		document.getElementById("note").style.display = "none";
	} else if (f.shouhin[1].checked) {
		document.getElementById("caution").innerHTML = "記入漏れがないようにご注意ください";
		document.getElementById("note").style.display = "";
	} else
		document.getElementById("note").style.display = "none";

	<!--jeon_start seq=ad1>
	document.getElementById("delivery_tbl1").style.display=f.shouhin[0].checked ? "" : "none";
	document.getElementById("delivery_tbl").style.display=f.shouhin[1].checked ? "" : "none";
	
	<!--jeon_end seq=ad1>	
	
}
//-->
</script>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　ポイント</SPAN>
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
		<td width="15"><img src="<?=$img?>/spacer.gif" width="15" height="1"></td>
	</tr>
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<form method="post" action="m_pconfirm.php" name="form1">
						<font color="black"><strong>■ポイントを精算する</strong></font><table width="385" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><img src="<?=$img?>/circle_top.gif" width="384" height="10"></td>
							</tr>
							<tr>
								<td><table width="384" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
											<td bgcolor="#e8f8ff" align="center" width="382"><table width="382" border="0" cellspacing="0" cellpadding="3">
													<tr bgcolor="#e8f8ff" valign="top">
														<td width="148"><font size="2">何ポイント精算しますか？ </font></td>
														<td width="234"><font size="2"><input type="text" name="ex_point" size="6" maxlength="5" value=""> ポイント</font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">何と交換しますか？</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2">
															<!-- jeon_start seq=ad1-->
															<!--<input type="radio" name="shouhin" value="3" onclick="delivery()"> WebMoney　　（<a href="http://www.webmoney.jp/" target="_blank">WebMoneyって？</a>）<br>-->
															<input type="radio" name="shouhin" value="4" onclick="delivery()"> 楽天銀行へ振込（<a href="http://www.ebank.co.jp/kojin/benefit/index.html" target="_blank">楽天銀行って？</a>）<br>
<table border="0">
	<tr>
		<td rowspan="3" valign="top">※</td>
		<td>楽天銀行はご本人名義の口座のみです。</td>
	</tr>
	<tr>
		<td>ポイントの精算は200ポイント、500ポイント、以降1000ポイント、2000ポイント、3000ポイント…と1000ポイント単位となります。</td>
	</tr>
	<tr>
		<td>手数料は<?=get_charge_point(4)?>ポイントです。</td>
	</tr>
</table>
															<!--　jeon_end seq-ad1-->
															<input type="radio" name="shouhin" value="1" onclick="delivery()"> VISAギフト券<br>
<table border="0">
	<tr>
		<td rowspan="2" valign="top">※</td>
		<td>ポイントの精算は<?=get_min_exchange_point(1)?>ポイント以上、1000ポイント単位です。</td>
	</tr>
	<tr>
		<td>手数料は<?=get_charge_point(1)?>ポイントです。</td>
	</tr>
</table>
<?/*															<input type="radio" name="shouhin" value="5" onclick="delivery();document.form1.ex_point.value='200'"> 「東北地方太平洋沖地震義援金」<br>　　　として、寄付する<br>
<table border="0" width="100%">
	<tr>
		<td rowspan="2" valign="top">※</td>
		<td>お1人様1回限り、「200ポイント」にて受付いたします</td>
	</tr>
	<tr>
		<td>手数料は一切かかりません</td>
	</tr>
</table>
*/?>
<?/*															<input type="radio" name="shouhin" value="2" onclick="delivery()"> 図書カード*/?>
</font></td>													</tr>
												</table>
												<table width="382" border="0" cellspacing="0" cellpadding="3" id="delivery_tbl" style="display:none">
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">▽ 送付先情報</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2">（<span id="caution">記入漏れがないようにご注意ください</span>）</font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　氏名</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2">姓 <input type="text" name="name1" size="10" maxlength="20" value="<?=$name1?>"> 　名 <input type="text" name="name2" size="10" maxlength="20" value="<?=$name2?>"> </font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　郵便番号</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="zip1" size="3" maxlength="3" value="<?=$zip1?>">-<input type="text" name="zip2" size="4" maxlength="4" value="<?=$zip2?>"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　住所１（都道府県）</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="addr1" size="30" maxlength="50" value="<?=$addr?>"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　住所２（市区町村名）</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="addr2" size="30" maxlength="50"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　住所３（町名・番地）</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="addr3" size="30" maxlength="50"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　住所４（ビル名）</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="addr4" size="30" maxlength="50"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2"></font></td>
														<td bgcolor="#e8f8ff" width="234"><font color="ff0000"><font size="2">↑都道府県以下をご記入ください</font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　電話番号</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="tel1" size="4" maxlength="5" value="<?=$tel[0]?>">-<input type="text" name="tel2" size="4" maxlength="5" value="<?=$tel[1]?>">-<input type="text" name="tel3" size="4" maxlength="4" value="<?=$tel[2]?>"></font></td>
													</tr>
												</table>
												<!-- jeon_start seq=ad1 -->
												<table width="382" border="0" cellspacing="0" cellpadding="3" id="delivery_tbl1" style="display:none">
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">▽ 送付先情報</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2">（<span id="caution">記入漏れがないようにご注意ください</span>）</font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　支店名</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="siten_name" size="30" maxlength="50">
<br>※楽天銀行の支店名がわからない方は<a href="http://www.ebank.co.jp/code/index.html" target="_blank">こちら</a>でご確認の上、お申し込みください。
</font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　支店番号</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="siten_no" size="30" maxlength="50"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　口座番号</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="kouza_no" size="30" maxlength="50"></font></td>
													</tr>
													<tr valign="top">
														<td bgcolor="#e8f8ff" width="148"><font size="2">　口座名義(全角カタカナ)</font></td>
														<td bgcolor="#e8f8ff" width="234"><font size="2"><input type="text" name="kouza_name" size="30" maxlength="50"></font></td>
													</tr>													
												</table>
												<!-- jeon_end seq=ad1 -->
										  </td>
											<td bgcolor="#8584d2" width="1"><img src="<?=$img?>/spacer.gif" width="1" height="8"></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td><img src="<?=$img?>/table_nomal.gif" width="384" height="10"></td>
							</tr>
						</table><br>
						<hr size="1" width="380" align="left">
						<p><br>
					      <font size="2" id="note">※【精算】ボタンを押す時には、<font color="ff0000">都道府県以下の詳細な住所</font>をご記入されていることをご確認くださいね。<BR><BR>※ポイントの精算は<font color="ff0000">【２０日締め・月末支払い】</font>となります。２１日以降に申請いただいたものは、翌月のお支払いとなりますのでご注意ください。</font>					      </p><br>
						<DIV align="center">
						<input type="submit" value="　精　算　">&nbsp;
						<input type="reset" value="　クリア　">
						</DIV>
						</form>
					</td>
				</tr>
			</table></td>
	</tr>
</table>

<!-- コンテンツ本体のソースはこの間に表記 -->
		</TR>
	</TD>
	</TBODY>
</TABLE>
</BODY>
</HTML>
