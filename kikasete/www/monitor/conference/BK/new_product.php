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
              参加しているリサーチパートナーの方には謝礼ポイントとして１日１回の発言で１００ポイントとかなり高額！<br>
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
          <td colspan="3"><a href="cvs.php"><img src="<?=$img?>/tab_cvs_off.gif" alt="コンビニ会議室" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_on.gif" alt="新商品会議室" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_off.gif" alt="イトーヨーカドー会議室" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_off.gif" alt="ジャスコ会議室" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="fs12_lh130"><br>
                  「コンビニ会議室」では、コンビニ大好きなメンバーが、お店で見つけた新商品やお気に入りの商品などについてワイワイ話しています。 今どんな商品が流行っているのか、どんな良い商品があるのかなどが スグに分かります。<br>
                  <br>
                  <a href="new_product_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="会議室を覗いてみる" width="116" height="18" border="0"></a><br>
                  <br></td>
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
                <td align="center" nowrap>ハンドル名</td>
                <td align="center" nowrap>性別</td>
                <td align="center" nowrap>年齢</td>
                <td align="center" bgcolor="#AFE8B4">居住地域 </td>
                <td align="center">よく行く店</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>ねか＠おせわ役</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top" nowrap>西友,東急ストアー,セブンイレブン</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>かめしゃん</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>新潟県</td>
                <td>ファミリーマート,ローソン,デイリーヤマザキ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>SallySally</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代</td>
                <td valign="top" nowrap>大阪府</td>
                <td valign="top">セブンイレブン,ローソン,ファミリーマート,ミニストップ,am.pm.,ヤマザキ,サークルＫ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>トムハシ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>埼玉県</td>
                <td valign="top">セブンイレブン,ファミリーマート,アピタ,ベルク,コープ,無印良品</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>餃子王子</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>埼玉県</td>
                <td valign="top">ローソン,カインズホーム</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>mama421</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">タリーズ,スタンダードデリ,東急ストア,ショップ99</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>ayaayaya</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>50代前半</td>
                <td valign="top" nowrap>島根県</td>
                <td valign="top">ローソン,ファミリーマート,ヤマザキ,その他</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>なぎさ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">スリーエフ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>かおり姫</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">ローソン,コープ,ダイエー</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>かんからさんしん</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>茨城県</td>
                <td valign="top">セブンイレブン,ファミリーマート,イトーヨーカドー,ソニプラ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>シナモン</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">セブンイレブン,GAP,ユニクロ ,サティー</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>あんこ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>40代</td>
                <td valign="top" nowrap>埼玉県</td>
                <td valign="top">西友,ライフ,ローソン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>あんま</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top">イトーヨーカドー,西友,ファミリーマート,ダイエー,セブンイレブン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>あざらし</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>高知県</td>
                <td valign="top">セブンイレブン,サンクス,サークルＫ,スリーエフ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>あんころすずたん</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>埼玉県</td>
                <td valign="top">ヤオコー,TSUTAYA,GAP</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>sandra</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代後半</td>
                <td valign="top" nowrap>福岡県</td>
                <td valign="top">ローソン,ａｍｐｍ,岩田屋（全国的にいえば伊勢丹）</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>みけ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top">AMPM,ローソン,ナチュラルローソン,ＧＡＰ,大丸ピーコック</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>まひろ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代半ば</td>
                <td valign="top" nowrap>埼玉県</td>
                <td valign="top">ミニストップ,マルエツ,イオン,フランフラン,GAP</td>
              </tr>
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
