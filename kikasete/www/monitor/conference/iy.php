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
          <td colspan="3"><a href="cvs.php"><img src="<?=$img?>/tab_cvs_off.gif" alt="コンビニ会議室" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_off.gif" alt="新商品会議室" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_on.gif" alt="イトーヨーカドー会議室" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_off.gif" alt="ジャスコ会議室" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              
          <tr> 
            <td class="fs12_lh130"><br>
              「イトーヨーカドー会議室」では、普段よく利用するイトーヨーカドーの生鮮、加工食品、お惣菜、日用雑貨からその他のサービスまで幅広くワイワイ話しています。 
              今どんな商品が流行っているのか、どんな良い商品があるのかなどが スグに分かります。<br>
              <br>
                <a href="iy_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="会議室を覗いてみる" width="116" height="18" border="0"></a><br>
                <br>
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
                <td width="110" align="center" nowrap><div align="center">ハンドル名</div></td>
                <td width="35" align="center" nowrap>性別</td>
                <td width="35" align="center" nowrap>年齢</td>
                <td width="60" align="center" bgcolor="#AFE8B4">居住地域 </td>
                <td width="415" align="center">よく行くIYの店舗</td>
              </tr>

<tr bgcolor="#EFEFEF">	  <td height="26">	1010	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	上板橋店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	くりこ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	大船店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ジェリーぽん	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	北海道	</td>	  <td valign="top" nowrap>	アリオ札幌店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	だびまる	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	小田原店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ぶんじくん	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	恋ヶ窪店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ameri	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	立場店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	まきぴょん。	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	大阪府	</td>	  <td valign="top" nowrap>	東大阪店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ゆずみかん。	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	北海道	</td>	  <td valign="top" nowrap>	帯広店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	にゃか２４	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	たまプラーザ店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	コモモンママ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	50代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	東村山店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	hanarinのママ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	東久留米店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ロシママ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	千葉県	</td>	  <td valign="top" nowrap>	新浦安店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ハニカミ姫	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	大森店、川崎港町店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	花大好き	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	南大沢店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	テラ介	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	綾瀬店、アリオ亀有店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ペンギン	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	埼玉県	</td>	  <td valign="top" nowrap>	三郷店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	くどまり	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	大阪府	</td>	  <td valign="top" nowrap>	アリオ八尾店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	がららん	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	鶴見店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	にゃんすけ★	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	南大沢店、橋本店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ぽにょ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	湘南台店、立場店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	キャラメルママさん	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	50代	</td>	  <td valign="top" nowrap>	千葉県	</td>	  <td valign="top" nowrap>	蘇我店	</td>	</tr>


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
