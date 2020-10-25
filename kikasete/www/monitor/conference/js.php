<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
    <TD VALIGN="TOP" ALIGN="CENTER" WIDTH="10"><IMG SRC="<?=$img?>/spacer.gif" WIDTH="10" HEIGHT="1"></TD>
    <TD VALIGN="TOP" ALIGN="CENTER">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" background="<?=$img?>/csv_ttlbg.gif"><img src="<?=$img?>/csv_ttl.gif" alt="おしゃべり会議室とは？" width="243" height="39"></td>
        </tr>
        <tr>
          <td colspan="2"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#C0E2FA"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="175" rowspan="3" bgcolor="#E8F6FF"><img src="<?=$img?>/csv_photo.gif" width="175" height="188"></td>
          <td bgcolor="#E8F6FF"><img src="<?=$img?>/csv_copy.gif" alt="今や生活に欠かせない「コンビニ」。食卓の台所「イトーヨーカドー」や「ジャスコ」等のスーパー。日々生まれる「新商品」達。" width="397" height="59"></td>
        </tr>
        <tr>
          <td height="1" bgcolor="#FFFFFF"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td bgcolor="#E8F6FF" class="fs12_lh130"><p>そんな日常生活に密着したお店や新商品の発見・驚きを、モニターからパワーアップしたリサーチパートナーの人々がリアルタイムでおしゃべりしている場所が「おしゃべり会議室」です。<br>
              <br>
              参加しているリサーチパートナーの方には謝礼ポイントとして１日１回の発言で６０ポイントとかなり高額！<br>
              <br>
              皆さんもぜひリサーチパートナー募集のお知らせが来たら参加してください！！<br>
            </p>
          </td>
        </tr>
        <tr>
          <td height="3" colspan="2" background="<?=$img?>/csv_ttl_under.gif"><img src="<?=$img?>/spacer.gif" width="1" height="3"></td>
        </tr>
      </table>
      <br>
      <br>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr align="left" valign="bottom">
          <td colspan="3"><a href="cvs.php"><img src="<?=$img?>/tab_cvs_off.gif" alt="コンビニ会議室" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_off.gif" alt="新商品会議室" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_off.gif" alt="イトーヨーカドー会議室" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_on.gif" alt="ジャスコ会議室" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              
          <tr> 
            <td class="fs12_lh130"><p><br>
                「ジャスコ会議室」では、普段よく利用するジャスコ（サティ）の生鮮、加工食品、お惣菜、日用雑貨からその他のサービスまで幅広くワイワイ話しています。 
                今どんな商品が流行っているのか、どんな良い商品があるのかなどが スグに分かります。<br>
                <br>
                <a href="js_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="会議室を覗いてみる" width="116" height="18" border="0"></a><br>
                <br></p>
                </p>
              </td>
              </tr>
              <tr>
                <td height="1" bgcolor="#2E7435"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td><br>
                  おしゃべり会議室に参加するには、<br>
                  リサーチパートナーへのお申し込みが<br>
                  必要です。<br>
                  <br>
                  <a href="rp.php"><img src="<?=$img?>/csv_button_resear.gif" alt="リサーチパートナーとは" width="116" height="18" border="0"></a>
                </td>
              </tr>
            </table>
          </td>
          <td width="1" background="<?=$img?>/csv_bar.gif" bgcolor="#F5FAF6" class="kaigishitsu_center"><img src="<?=$img?>/spacer.gif" width="1" height="1"></td>
          <td align="center" bgcolor="#F5FAF6" class="kaigishitsu_right"><br>
            <table width="98%"  border="0" cellspacing="1" cellpadding="3">
              <tr>
                <td colspan="5" bgcolor="#46834C"><img src="<?=$img?>/csv_member.gif" alt="参加者リスト" width="79" height="15" hspace="3" vspace="0"></td>
              </tr>
              <tr bgcolor="#AFE8B4">
                <td width="110" align="center" nowrap>ハンドル名</td>
                <td width="35" align="center" nowrap>性別</td>
                <td width="35" align="center" nowrap>年齢</td>
                <td width="60" align="center" bgcolor="#AFE8B4">居住地域 </td>
                <td width="415" align="center">よく行くジャスコ店舗</td>
              </tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ちっぴー	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	石川県	</td>	<td valign="top" nowrap>	もりの里店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	Jubilo,ジュビサポ	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	岐阜県	</td>	<td valign="top" nowrap>	桑名サティ（マイカル桑名店）	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ここ☆こころ	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	大阪府	</td>	<td valign="top" nowrap>	新茨木店、
マイカル茨木サティ、
京都五条店、
洛南店、
大日店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	johnson	</td>	<td valign="top" nowrap>	男性	</td>	<td valign="top" nowrap>	40代	</td>	<td valign="top" nowrap>	千葉県	</td>	<td valign="top" nowrap>	市川妙典店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	サラ	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	新潟県	</td>	<td valign="top" nowrap>	新潟南店、
新潟青山店、
新潟サティ	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ぼよよーん	</td>	<td valign="top" nowrap>	男性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	愛知県	</td>	<td valign="top" nowrap>	八事店、三好店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	みゃーにゃんこ	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	40代	</td>	<td valign="top" nowrap>	大阪府	</td>	<td valign="top" nowrap>	イオンりんくう店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	さな基地	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	埼玉県	</td>	<td valign="top" nowrap>	入間店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ジゼル	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	20代	</td>	<td valign="top" nowrap>	京都府	</td>	<td valign="top" nowrap>	洛南店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	トラミー	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	50代	</td>	<td valign="top" nowrap>	大阪府	</td>	<td valign="top" nowrap>	南千里店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	光姫	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	50代	</td>	<td valign="top" nowrap>	京都府	</td>	<td valign="top" nowrap>	高の原店、
登美が丘店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	マックスコーヒー	</td>	<td valign="top" nowrap>	男性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	千葉県	</td>	<td valign="top" nowrap>	マリンピア店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	さったん	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	神奈川県	</td>	<td valign="top" nowrap>	東神奈川店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	きより	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	20代	</td>	<td valign="top" nowrap>	大阪府	</td>	<td valign="top" nowrap>	金剛東店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	とまち	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	40代	</td>	<td valign="top" nowrap>	三重県	</td>	<td valign="top" nowrap>	マックスバリュー太閤店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	猫姐	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	熊本県	</td>	<td valign="top" nowrap>	八代店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	アロエ	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	北海道	</td>	<td valign="top" nowrap>	札幌平岡店、札幌東苗穂店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	もなか	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	50代	</td>	<td valign="top" nowrap>	広島県	</td>	<td valign="top" nowrap>	ビック庄原店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ファイターズシニア	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	30代	</td>	<td valign="top" nowrap>	北海道	</td>	<td valign="top" nowrap>	札幌苗穂店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	ruki	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	40代	</td>	<td valign="top" nowrap>	大阪府	</td>	<td valign="top" nowrap>	イオン大日店、イオン鶴見店、サティ鴻池店	</td>	</tr>
<tr bgcolor="#EFEFEF">	<td height="26">	マカロン	</td>	<td valign="top" nowrap>	女性	</td>	<td valign="top" nowrap>	20代	</td>	<td valign="top" nowrap>	東京都	</td>	<td valign="top" nowrap>	葛西店	</td>	</tr>


            </table>
            <br>
          </td>
        </tr>
      </table>
    </TD>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
