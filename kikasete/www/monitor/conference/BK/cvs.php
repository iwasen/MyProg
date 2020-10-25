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
          <td colspan="3"><a href="cvs.php"><img src="<?=$img?>/tab_cvs_on.gif" alt="コンビニ会議室" width="160" height="31" hspace="1" border="0"><a href="new_product.php"><img src="<?=$img?>/tab_newproduct_off.gif" alt="新商品会議室" width="160" height="31" hspace="1" border="0"></a><a href="iy.php"><img src="<?=$img?>/tab_iy_off.gif" alt="イトーヨーカドー会議室" width="160" height="31" hspace="1" border="0"></a><a href="js.php"><img src="<?=$img?>/tab_js_off.gif" alt="ジャスコ会議室" width="160" height="30" hspace="1" border="0"></a></td>
        </tr>
        <tr>
          <td width="230" align="center" valign="top" bgcolor="#F5FAF6" class="kaigishitsu_left">
            <table width="225"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="fs12_lh130"><br>
                  「コンビニ会議室」では、コンビニ大好きなメンバーが、お店で見つけた新商品やお気に入りの商品などについてワイワイ話しています。 今どんな商品が流行っているのか、どんな良い商品があるのかなどが スグに分かります。<br>
                  <br>
                  <a href="cvs_lg.php" target="_blanck"><img src="<?=$img?>/csv_button_conf.gif" alt="会議室を覗いてみる" width="116" height="18" border="0"></a><br>
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
                <td align="center">よく行くコンビニ</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>ねか＠おせわ役</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top">会社のすぐそばにあるローソン,家の近所のセブンイレブン</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td valign="top" nowrap>みかりん</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代後半</td>
                <td valign="top" nowrap>大阪府</td>
                <td valign="top">最寄り駅から家までの間にあるセブンイレブン</td>
              </tr>
              <tr valign="top" bgcolor="#EFEFEF">
                <td bgcolor="#EFEFEF">miocho</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代前半</td>
                <td valign="top" nowrap>宮城県</td>
                <td valign="top">自宅より徒歩5分のセブンイレブン,会社近くの,デイリーストア,友達宅の近くにあるファミリーマートとデイリーストア</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>ちい</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>30代半ば</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">家から50mほどのサークルK,自宅と駅に間にあるミニストップ,会社近くのファミリーマート,セブンイレブン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>みゅう</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代</td>
                <td valign="top" nowrap>大阪府</td>
                <td valign="top">ローソン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>ゴンタレス</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>20代前半</td>
                <td valign="top" nowrap>神奈川県</td>
                <td valign="top">ファミマ,ローソン,セブン,AMPM</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>わだちん</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>50代</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top">1番近くのローソン,2番目のセブンイレブン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>誠ブラザーズ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代</td>
                <td valign="top" nowrap>岐阜県</td>
                <td valign="top">自宅から5分ほどのところにあるサークルK,バイトや買い物で良く通る国道沿いにあるファミリーマート</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>みかクー</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代</td>
                <td valign="top" nowrap>大阪府</td>
                <td valign="top">自宅最寄り駅近くのファミリーマート,会社の近くのヤマザキデイリーストア、サンクス</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>kanaako</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>40代前半</td>
                <td valign="top" nowrap>高知県</td>
                <td valign="top">ローソン,サークルK</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>ムニ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>30代</td>
                <td valign="top" nowrap>愛知県</td>
                <td valign="top">自宅から徒歩2〜3分のファミリーマート,職場の昼休みなどに行くサークルＫ</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>GaoQiao</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>20代</td>
                <td valign="top" nowrap>千葉県</td>
                <td valign="top">会社の近くのファミマ,家の近くのサンクス,am/pm</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>らいあん</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>30代後半</td>
                <td valign="top" nowrap>福岡県</td>
                <td valign="top">通りがかりのあちこち。まったく決まっていません。</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>りお</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>東京都</td>
                <td valign="top">会社のそばのローソン,am/pm,ポプラ,家のそばのセブンイレブン,ミニストップ,ファミリーマート</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>きゅーぴー</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>20代前半</td>
                <td valign="top" nowrap>滋賀県</td>
                <td valign="top">自宅激近くのセブンイレブン,自宅近くのローソン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>コウメイ</td>
                <td valign="top" nowrap>男性</td>
                <td valign="top" nowrap>20代後半</td>
                <td valign="top" nowrap>大阪府</td>
                <td valign="top">ローソン</td>
              </tr>
              <tr bgcolor="#EFEFEF">
                <td valign="top" nowrap>パオ</td>
                <td valign="top" nowrap>女性</td>
                <td valign="top" nowrap>40代</td>
                <td valign="top" nowrap>熊本県</td>
                <td valign="top">いつも行っているレンタルビデオショップの隣にあるローソン,会社への送り迎えの途中にあるセブンイレブン</td>
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
