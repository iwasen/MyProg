                    <br>
                  </td>
                </tr>
              </table>
              <table border="0" cellpadding="5" cellspacing="0" height="62" width="657">
                <tr align="left" valign="top" bgcolor="#e2f0c9" height="25">
                  <td bgcolor="#91c741" height="26">
				    <img src="../search/img/yellow.gif" width="11" height="11">
				  </td>
                  <td width="450" height="26" align="left" valign="middle">
					<font class="ub" color="#336600"><b><?php echo $kind; ?></b></font>
					<font size="2" color="black"></font></td>
                  <td width="240" height="25" align="right" valign="middle"><font size="2" color="black">
                    </font>
					<?php echo $chg_page2; ?>
                  </td>
                </tr>
                <tr height="17">
                  <td height="17" colspan="3" bgcolor="#91c741" align="right">
					<table border="0" cellpadding="5" cellspacing="0">
						<tr>
<?php
	if ($all_num != 0) {
?>
							<td>
<?php
		if ($mail_addr == "") {
			print "<font size=2>メールアドレス入力欄<br>\n";
			print "<input type=text name=email size=30></font>\n";
		} else {
			print "<input type=hidden name=reader_id value=\"".$reader_id."\">\n";
			print "<input type=hidden name=reader_pswd value=\"".$reader_pswd."\">\n";
			print "<input type=hidden name=email value=\"".trim($mail_addr)."\">\n";
			print trim($mail_addr)."\n";
		}
?>
							</td>
							<td id="agreement">
<font size=2>
<nobr><input type="checkbox" name="agreement1" onclick="check_agreement()">規約に同意します。（規約は<a href="http://www.melonpan.net/rule_reader.html">こちら</a>）</nobr><br>
<nobr><input type="checkbox" name="agreement2" onclick="check_agreement()">広告・宣伝受取に同意します。</nobr>
</font>
							</td>
<?php
	}
?>
							<td>
<?php
	if ($all_num == 0) {
		print "<form><input type=\"button\" value=\"戻る\" onClick=\"history.back()\"></form>\n";
	} else {
		print "<input type=\"submit\" value=\"決定（購読する／解除）\" name=\"action_btn\">\n";
	}
?>
							</td>
						</tr>
                    </table>
                  </td>
                </tr>
              </table>
<?php
if ($mode == "rank" ){
	if 	   ($rank_id == "101"):
		echo "<br><br><br>";
	elseif ($rank_id == "102"):
		echo "<br><br><br>";
	elseif ($rank_id == "103"):
		$kijyunbi = substr($base_date,0,4)."年".substr($base_date,4,2)."月".substr($base_date,6,2)."日";
		echo "<table border=0 cellpadding=5 cellspacing=0 height=62 width=657>
		      <tr><td><font size=1 color=\"black\">
			  *このランキングは".$kijyunbi."以降に投稿された推薦文を対象に作成しました。
			  </font></td></tr>
		      </table>";
	elseif ($rank_id == "104"):
		echo "<table border=0 cellpadding=5 cellspacing=0 height=62 width=657>
		      <tr><td><font size=1 color=\"black\">
			  *このランキングは現在の読者が50名以上のメールマガジンを対象に作成しました。
			  </font></td></tr>
		      </table>";
	else:
		echo "<table border=0 cellpadding=5 cellspacing=0 height=62 width=657>
		      <tr><td><font size=1 color=\"black\">
			  *このランキングはマイページ登録をされた読者が50名以上購読しているメールマガジンを対象に作成しました。
			  </font></td></tr>
		      </table>";
	endif;
}else{
	echo "<br><br><br>";
}
?>
              <br>
            </td>
            <td width="25" bgcolor="#ffffd3">　</td>
          </tr>
          <tr>
            <td width="24"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
            <td width="24" bgcolor="#90c84b">　</td>
            <td width="657" bgcolor="#90c84b">　</td>
            <td width="25"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
          </tr>

