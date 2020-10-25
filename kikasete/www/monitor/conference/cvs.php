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
          <td colspan="3"><a href="cvs.php"><img src="<?=$img?>/tab_cvs_on.gif" alt="コンビニ会議室" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_off.gif" alt="新商品会議室" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_off.gif" alt="イトーヨーカドー会議室" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_off.gif" alt="ジャスコ会議室" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              
          <tr> 
            <td class="fs12_lh130"><p><br>
                「コンビニ会議室」では、コンビニ大好きなメンバーが、お店で見つけた新商品やお気に入りの商品などについてワイワイ話しています。
                今どんな商品が流行っているのか、どんな良い商品があるのかなどが スグに分かります。<br>
                <br>
                <a href="cvs_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="会議室を覗いてみる" width="116" height="18" border="0"></a><br>
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
                <td width="15%" align="center" nowrap>ハンドル名</td>
                <td width="7%" align="center" nowrap>性別</td>
                <td width="7%" align="center" nowrap>年齢</td>
                <td width="10%" align="center" bgcolor="#AFE8B4">居住地域 </td>
                <td width="61%" align="center">よく行くコンビニ</td>
              </tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	りんごまま	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	兵庫県	</td>	  <td valign="top" nowrap>	セブンイレブン・平岡山之上店、セブンイレブン・古田店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	うっきー	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	北海道	</td>	  <td valign="top" nowrap>	セブンイレブン・富良野店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	smile	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	セブンイレブン・荏原4丁目店、
セブンイレブン・大田区西蒲田8丁目店、
ローソン・武蔵小山駅前店</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ミルク姫	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	千葉県	</td>	  <td valign="top" nowrap>	セブンイレブン・東京医科歯科大病院店、セブンイレブン・千葉蘇我駅前店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	真田まーのしん	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	埼玉県	</td>	  <td valign="top" nowrap>	ミニストップ・幕張イオンタワー店、
ローソン・新川店、
セブンイレブン・西調布店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	かっつん	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	ローソン・八王子みなみ野一丁目店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	メイプル	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	広島県	</td>	  <td valign="top" nowrap>	ファミリーマート・尾長店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ちゅら海	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	ローソン・藤沢○○町店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	sorasora	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	ampm・白糸台１丁目店、セブンイレブン・多磨霊園駅前店、コミュニティーストア・府中なみき店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	なおくみ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	京都府	</td>	  <td valign="top" nowrap>	ミニストップ・欽明台店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	まいか	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	埼玉県	</td>	  <td valign="top" nowrap>	ローソン・下間久里店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ひろぴょん	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	50代	</td>	  <td valign="top" nowrap>	茨城県	</td>	  <td valign="top" nowrap>	セブンイレブン
・東海原研前店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ダンゴ兄弟	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	兵庫県	</td>	  <td valign="top" nowrap>	セブンイレブン・本山駅南店、
ファミリーマート・甲南町店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	うさみ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	50代	</td>	  <td valign="top" nowrap>	神奈川県	</td>	  <td valign="top" nowrap>	サークルＫ・愛川中津店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	かんタン	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	東京都	</td>	  <td valign="top" nowrap>	ポプラ・千駄木２丁目店、
ファミリーマート・池袋サンシャイン内	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	　なみきちくん	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	40代	</td>	  <td valign="top" nowrap>	愛知県	</td>	  <td valign="top" nowrap>	ミニストップ・赤池店、
サークルＫ・大高店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	haru37	</td>	  <td valign="top" nowrap>	男性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	宮城県	</td>	  <td valign="top" nowrap>	ファミリーマート・新田東店、
セブンイレブン・仙台新田２丁目店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	Pii	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	30代	</td>	  <td valign="top" nowrap>	愛知県	</td>	  <td valign="top" nowrap>	セブンイレブン・杜若店、
サークルK・伝治山店	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	みーぶ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	20代	</td>	  <td valign="top" nowrap>	埼玉県	</td>	  <td valign="top" nowrap>	セブンイレブン・戸塚安行店、ミニストップ・川口長蔵店、ファミリーマート・花山下店、ファミリーマート・川口西立野店 
	</td>	</tr>
<tr bgcolor="#EFEFEF">	  <td height="26">	ぴっぴ	</td>	  <td valign="top" nowrap>	女性	</td>	  <td valign="top" nowrap>	50代	</td>	  <td valign="top" nowrap>	新潟県	</td>	  <td valign="top" nowrap>	セーブオン・新潟東笠巻店	</td>	</tr>


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
