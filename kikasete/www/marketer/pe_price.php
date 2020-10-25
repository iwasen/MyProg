<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション変数削除
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_PROENQ); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_pe/title_1.gif" alt="PRO MENU" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_pe/title_01.gif" alt="Proアンケート" width="123" height="28">                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_pe/title_05.gif" alt="Proアンケート" /></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td class="footer_text"><img src="images/mp_pjt_list/title06.gif" alt="基本料金" width="176" height="15"></td>
              </tr>
			                <tr> 
                <td class="footer_text" align="right">(千円)</td>
              </tr>
			                <tr> 
                <td bgcolor="#dadada"> 
                  <Table Border="0" Width="100%" CellSpacing="1" CellPadding="5">
                    <Tr> 
                      <Td BgColor="#e5eaf0" ColSpan="2" RowSpan="2">&nbsp;</Td>
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" ColSpan="9">質問数</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" BgColor="#e5eaf0">〜10問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜15問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜20問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜25問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜30問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜35問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜40問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜45問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜50問</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" RowSpan="23">サ<br>
                      ン<br>
                      プ<br>
                      ル<br>
                        数<br>                      </Td>
<td BgColor="#FFFFFF" align="center">100</td><td BgColor="#FFFFFF" align="right">50</td><td BgColor="#FFFFFF" align="right">74</td><td BgColor="#FFFFFF" align="right">98</td><td BgColor="#FFFFFF" align="right">122</td><td BgColor="#FFFFFF" align="right">146</td><td BgColor="#FFFFFF" align="right">170</td><td BgColor="#FFFFFF" align="right">194</td><td BgColor="#FFFFFF" align="right">218</td><td BgColor="#FFFFFF" align="right">242</td>
                    </Tr>
<tr><td BgColor="#FFFFFF" align="center">200</td><td BgColor="#FFFFFF" align="right">70</td><td BgColor="#FFFFFF" align="right">100</td><td BgColor="#FFFFFF" align="right">130</td><td BgColor="#FFFFFF" align="right">160</td><td BgColor="#FFFFFF" align="right">190</td><td BgColor="#FFFFFF" align="right">220</td><td BgColor="#FFFFFF" align="right">250</td><td BgColor="#FFFFFF" align="right">280</td><td BgColor="#FFFFFF" align="right">310</td></tr>
<tr><td BgColor="#FFFFFF" align="center">300</td><td BgColor="#FFFFFF" align="right">90</td><td BgColor="#FFFFFF" align="right">126</td><td BgColor="#FFFFFF" align="right">162</td><td BgColor="#FFFFFF" align="right">198</td><td BgColor="#FFFFFF" align="right">234</td><td BgColor="#FFFFFF" align="right">270</td><td BgColor="#FFFFFF" align="right">306</td><td BgColor="#FFFFFF" align="right">342</td><td BgColor="#FFFFFF" align="right">378</td></tr>
<tr><td BgColor="#FFFFFF" align="center">400</td><td BgColor="#FFFFFF" align="right">110</td><td BgColor="#FFFFFF" align="right">152</td><td BgColor="#FFFFFF" align="right">194</td><td BgColor="#FFFFFF" align="right">236</td><td BgColor="#FFFFFF" align="right">278</td><td BgColor="#FFFFFF" align="right">320</td><td BgColor="#FFFFFF" align="right">362</td><td BgColor="#FFFFFF" align="right">404</td><td BgColor="#FFFFFF" align="right">446</td></tr>
<tr><td BgColor="#FFFFFF" align="center">500</td><td BgColor="#FFFFFF" align="right">130</td><td BgColor="#FFFFFF" align="right">178</td><td BgColor="#FFFFFF" align="right">226</td><td BgColor="#FFFFFF" align="right">274</td><td BgColor="#FFFFFF" align="right">322</td><td BgColor="#FFFFFF" align="right">370</td><td BgColor="#FFFFFF" align="right">418</td><td BgColor="#FFFFFF" align="right">466</td><td BgColor="#FFFFFF" align="right">514</td></tr>
<tr><td BgColor="#FFFFFF" align="center">600</td><td BgColor="#FFFFFF" align="right">150</td><td BgColor="#FFFFFF" align="right">204</td><td BgColor="#FFFFFF" align="right">258</td><td BgColor="#FFFFFF" align="right">312</td><td BgColor="#FFFFFF" align="right">366</td><td BgColor="#FFFFFF" align="right">420</td><td BgColor="#FFFFFF" align="right">474</td><td BgColor="#FFFFFF" align="right">528</td><td BgColor="#FFFFFF" align="right">582</td></tr>
<tr><td BgColor="#FFFFFF" align="center">700</td><td BgColor="#FFFFFF" align="right">170</td><td BgColor="#FFFFFF" align="right">230</td><td BgColor="#FFFFFF" align="right">290</td><td BgColor="#FFFFFF" align="right">350</td><td BgColor="#FFFFFF" align="right">410</td><td BgColor="#FFFFFF" align="right">470</td><td BgColor="#FFFFFF" align="right">530</td><td BgColor="#FFFFFF" align="right">590</td><td BgColor="#FFFFFF" align="right">650</td></tr>
<tr><td BgColor="#FFFFFF" align="center">800</td><td BgColor="#FFFFFF" align="right">190</td><td BgColor="#FFFFFF" align="right">256</td><td BgColor="#FFFFFF" align="right">322</td><td BgColor="#FFFFFF" align="right">388</td><td BgColor="#FFFFFF" align="right">454</td><td BgColor="#FFFFFF" align="right">520</td><td BgColor="#FFFFFF" align="right">586</td><td BgColor="#FFFFFF" align="right">652</td><td BgColor="#FFFFFF" align="right">718</td></tr>
<tr><td BgColor="#FFFFFF" align="center">900</td><td BgColor="#FFFFFF" align="right">210</td><td BgColor="#FFFFFF" align="right">282</td><td BgColor="#FFFFFF" align="right">354</td><td BgColor="#FFFFFF" align="right">426</td><td BgColor="#FFFFFF" align="right">498</td><td BgColor="#FFFFFF" align="right">570</td><td BgColor="#FFFFFF" align="right">642</td><td BgColor="#FFFFFF" align="right">714</td><td BgColor="#FFFFFF" align="right">786</td></tr>
<tr><td BgColor="#FFFFFF" align="center">1000</td><td BgColor="#FFFFFF" align="right">230</td><td BgColor="#FFFFFF" align="right">308</td><td BgColor="#FFFFFF" align="right">386</td><td BgColor="#FFFFFF" align="right">464</td><td BgColor="#FFFFFF" align="right">542</td><td BgColor="#FFFFFF" align="right">620</td><td BgColor="#FFFFFF" align="right">698</td><td BgColor="#FFFFFF" align="right">776</td><td BgColor="#FFFFFF" align="right">854</td></tr>
<tr><td BgColor="#FFFFFF" align="center">1250</td><td BgColor="#FFFFFF" align="right">270</td><td BgColor="#FFFFFF" align="right">360</td><td BgColor="#FFFFFF" align="right">450</td><td BgColor="#FFFFFF" align="right">540</td><td BgColor="#FFFFFF" align="right">630</td><td BgColor="#FFFFFF" align="right">720</td><td BgColor="#FFFFFF" align="right">810</td><td BgColor="#FFFFFF" align="right">900</td><td BgColor="#FFFFFF" align="right">990</td></tr>
<tr><td BgColor="#FFFFFF" align="center">1500</td><td BgColor="#FFFFFF" align="right">310</td><td BgColor="#FFFFFF" align="right">412</td><td BgColor="#FFFFFF" align="right">514</td><td BgColor="#FFFFFF" align="right">616</td><td BgColor="#FFFFFF" align="right">718</td><td BgColor="#FFFFFF" align="right">820</td><td BgColor="#FFFFFF" align="right">922</td><td BgColor="#FFFFFF" align="right">1024</td><td BgColor="#FFFFFF" align="right">1126</td></tr>
<tr><td BgColor="#FFFFFF" align="center">1750</td><td BgColor="#FFFFFF" align="right">350</td><td BgColor="#FFFFFF" align="right">464</td><td BgColor="#FFFFFF" align="right">578</td><td BgColor="#FFFFFF" align="right">692</td><td BgColor="#FFFFFF" align="right">806</td><td BgColor="#FFFFFF" align="right">920</td><td BgColor="#FFFFFF" align="right">1034</td><td BgColor="#FFFFFF" align="right">1148</td><td BgColor="#FFFFFF" align="right">1262</td></tr>
<tr><td BgColor="#FFFFFF" align="center">2000</td><td BgColor="#FFFFFF" align="right">390</td><td BgColor="#FFFFFF" align="right">516</td><td BgColor="#FFFFFF" align="right">642</td><td BgColor="#FFFFFF" align="right">768</td><td BgColor="#FFFFFF" align="right">894</td><td BgColor="#FFFFFF" align="right">1020</td><td BgColor="#FFFFFF" align="right">1146</td><td BgColor="#FFFFFF" align="right">1272</td><td BgColor="#FFFFFF" align="right">1398</td></tr>
<tr><td BgColor="#FFFFFF" align="center">2250</td><td BgColor="#FFFFFF" align="right">430</td><td BgColor="#FFFFFF" align="right">568</td><td BgColor="#FFFFFF" align="right">706</td><td BgColor="#FFFFFF" align="right">844</td><td BgColor="#FFFFFF" align="right">982</td><td BgColor="#FFFFFF" align="right">1120</td><td BgColor="#FFFFFF" align="right">1258</td><td BgColor="#FFFFFF" align="right">1396</td><td BgColor="#FFFFFF" align="right">1534</td></tr>
<tr><td BgColor="#FFFFFF" align="center">2500</td><td BgColor="#FFFFFF" align="right">470</td><td BgColor="#FFFFFF" align="right">620</td><td BgColor="#FFFFFF" align="right">770</td><td BgColor="#FFFFFF" align="right">920</td><td BgColor="#FFFFFF" align="right">1070</td><td BgColor="#FFFFFF" align="right">1220</td><td BgColor="#FFFFFF" align="right">1370</td><td BgColor="#FFFFFF" align="right">1520</td><td BgColor="#FFFFFF" align="right">1670</td></tr>
<tr><td BgColor="#FFFFFF" align="center">2750</td><td BgColor="#FFFFFF" align="right">510</td><td BgColor="#FFFFFF" align="right">672</td><td BgColor="#FFFFFF" align="right">834</td><td BgColor="#FFFFFF" align="right">996</td><td BgColor="#FFFFFF" align="right">1158</td><td BgColor="#FFFFFF" align="right">1320</td><td BgColor="#FFFFFF" align="right">1482</td><td BgColor="#FFFFFF" align="right">1644</td><td BgColor="#FFFFFF" align="right">1806</td></tr>
<tr><td BgColor="#FFFFFF" align="center">3000</td><td BgColor="#FFFFFF" align="right">550</td><td BgColor="#FFFFFF" align="right">724</td><td BgColor="#FFFFFF" align="right">898</td><td BgColor="#FFFFFF" align="right">1072</td><td BgColor="#FFFFFF" align="right">1246</td><td BgColor="#FFFFFF" align="right">1420</td><td BgColor="#FFFFFF" align="right">1594</td><td BgColor="#FFFFFF" align="right">1768</td><td BgColor="#FFFFFF" align="right">1942</td></tr>
<tr><td BgColor="#FFFFFF" align="center">3500</td><td BgColor="#FFFFFF" align="right">610</td><td BgColor="#FFFFFF" align="right">802</td><td BgColor="#FFFFFF" align="right">994</td><td BgColor="#FFFFFF" align="right">1186</td><td BgColor="#FFFFFF" align="right">1378</td><td BgColor="#FFFFFF" align="right">1570</td><td BgColor="#FFFFFF" align="right">1762</td><td BgColor="#FFFFFF" align="right">1954</td><td BgColor="#FFFFFF" align="right">2146</td></tr>
<tr><td BgColor="#FFFFFF" align="center">4000</td><td BgColor="#FFFFFF" align="right">670</td><td BgColor="#FFFFFF" align="right">880</td><td BgColor="#FFFFFF" align="right">1090</td><td BgColor="#FFFFFF" align="right">1300</td><td BgColor="#FFFFFF" align="right">1510</td><td BgColor="#FFFFFF" align="right">1720</td><td BgColor="#FFFFFF" align="right">1930</td><td BgColor="#FFFFFF" align="right">2140</td><td BgColor="#FFFFFF" align="right">2350</td></tr>
<tr><td BgColor="#FFFFFF" align="center">4500</td><td BgColor="#FFFFFF" align="right">730</td><td BgColor="#FFFFFF" align="right">958</td><td BgColor="#FFFFFF" align="right">1186</td><td BgColor="#FFFFFF" align="right">1414</td><td BgColor="#FFFFFF" align="right">1642</td><td BgColor="#FFFFFF" align="right">1870</td><td BgColor="#FFFFFF" align="right">2098</td><td BgColor="#FFFFFF" align="right">2326</td><td BgColor="#FFFFFF" align="right">2554</td></tr>
<tr><td BgColor="#FFFFFF" align="center">5000</td><td BgColor="#FFFFFF" align="right">790</td><td BgColor="#FFFFFF" align="right">1036</td><td BgColor="#FFFFFF" align="right">1282</td><td BgColor="#FFFFFF" align="right">1528</td><td BgColor="#FFFFFF" align="right">1774</td><td BgColor="#FFFFFF" align="right">2020</td><td BgColor="#FFFFFF" align="right">2266</td><td BgColor="#FFFFFF" align="right">2512</td><td BgColor="#FFFFFF" align="right">2758</td></tr>
<tr><td BgColor="#FFFFFF" align="center">5001〜</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td></tr>
                  </Table>                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr>
                <td><img src="images/mkk_pe/title_07.gif" alt="ファインディング調査　基本料金" width="200" height="15" /></td>
              </tr>
                            <tr>
                              <td class="footer_text" align="right">(千円)</td>
                            </tr>
              <tr>
                <td bgcolor="#dadada"><Table Border="0" Width="100%" CellSpacing="1" CellPadding="5">
                    <Tr> 
                      <Td BgColor="#e5eaf0" ColSpan="2" RowSpan="2">&nbsp;</Td>
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" ColSpan="6">質問数</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" BgColor="#e5eaf0">〜3問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜5問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜7問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜8問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜9問</Td>
                      <Td Align="center" BgColor="#e5eaf0">〜10問</Td>
                      </Tr>
                    <Tr> 
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" RowSpan="12">サ<br>
                      ン<br>
                      プ<br>
                      ル<br>
                        数<br>                      </Td>
<td BgColor="#FFFFFF" align="center">5000</td><td BgColor="#FFFFFF" align="right">40</td>	<td BgColor="#FFFFFF" align="right">80</td>	<td BgColor="#FFFFFF" align="right">120</td>	<td BgColor="#FFFFFF" align="right">144</td>	<td BgColor="#FFFFFF" align="right">190</td>	<td BgColor="#FFFFFF" align="right">240</td>
                      </Tr>
<tr><td BgColor="#FFFFFF" align="center">10000</td><td BgColor="#FFFFFF" align="right">60</td><td BgColor="#FFFFFF" align="right">100</td><td BgColor="#FFFFFF" align="right">140</td><td BgColor="#FFFFFF" align="right">192</td><td BgColor="#FFFFFF" align="right">252</td><td BgColor="#FFFFFF" align="right">320</td></tr>
<tr><td BgColor="#FFFFFF" align="center">20000</td><td BgColor="#FFFFFF" align="right">120</td><td BgColor="#FFFFFF" align="right">200</td><td BgColor="#FFFFFF" align="right">280</td><td BgColor="#FFFFFF" align="right">384</td><td BgColor="#FFFFFF" align="right">504</td><td BgColor="#FFFFFF" align="right">640</td></tr>
<tr><td BgColor="#FFFFFF" align="center">30000</td><td BgColor="#FFFFFF" align="right">180</td><td BgColor="#FFFFFF" align="right">300</td><td BgColor="#FFFFFF" align="right">420</td><td BgColor="#FFFFFF" align="right">576</td><td BgColor="#FFFFFF" align="right">756</td><td BgColor="#FFFFFF" align="right">960</td></tr>
<tr><td BgColor="#FFFFFF" align="center">40000</td><td BgColor="#FFFFFF" align="right">240</td><td BgColor="#FFFFFF" align="right">400</td><td BgColor="#FFFFFF" align="right">560</td><td BgColor="#FFFFFF" align="right">768</td><td BgColor="#FFFFFF" align="right">1008</td><td BgColor="#FFFFFF" align="right">1280</td></tr>
<tr><td BgColor="#FFFFFF" align="center">50000</td><td BgColor="#FFFFFF" align="right">300</td><td BgColor="#FFFFFF" align="right">500</td><td BgColor="#FFFFFF" align="right">700</td><td BgColor="#FFFFFF" align="right">960</td><td BgColor="#FFFFFF" align="right">1260</td><td BgColor="#FFFFFF" align="right">1600</td></tr>
<tr><td BgColor="#FFFFFF" align="center">60000</td><td BgColor="#FFFFFF" align="right">360</td><td BgColor="#FFFFFF" align="right">600</td><td BgColor="#FFFFFF" align="right">840</td><td BgColor="#FFFFFF" align="right">1152</td><td BgColor="#FFFFFF" align="right">1512</td><td BgColor="#FFFFFF" align="right">1920</td></tr>
<tr><td BgColor="#FFFFFF" align="center">70000</td><td BgColor="#FFFFFF" align="right">420</td><td BgColor="#FFFFFF" align="right">700</td><td BgColor="#FFFFFF" align="right">980</td><td BgColor="#FFFFFF" align="right">1344</td><td BgColor="#FFFFFF" align="right">1764</td><td BgColor="#FFFFFF" align="right">2240</td></tr>
<tr><td BgColor="#FFFFFF" align="center">80000</td><td BgColor="#FFFFFF" align="right">480</td><td BgColor="#FFFFFF" align="right">800</td><td BgColor="#FFFFFF" align="right">1120</td><td BgColor="#FFFFFF" align="right">1536</td><td BgColor="#FFFFFF" align="right">2016</td><td BgColor="#FFFFFF" align="right">2560</td></tr>
<tr><td BgColor="#FFFFFF" align="center">90000</td><td BgColor="#FFFFFF" align="right">540</td><td BgColor="#FFFFFF" align="right">900</td><td BgColor="#FFFFFF" align="right">1260</td><td BgColor="#FFFFFF" align="right">1728</td><td BgColor="#FFFFFF" align="right">2268</td><td BgColor="#FFFFFF" align="right">2880</td></tr>
<tr><td BgColor="#FFFFFF" align="center">100000</td><td BgColor="#FFFFFF" align="right">600</td><td BgColor="#FFFFFF" align="right">1000</td><td BgColor="#FFFFFF" align="right">1400</td><td BgColor="#FFFFFF" align="right">1920</td><td BgColor="#FFFFFF" align="right">2520</td><td BgColor="#FFFFFF" align="right">3200</td></tr>
<tr><td BgColor="#FFFFFF" align="center">100001〜</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td><td BgColor="#FFFFFF" align="right">要相談</td></tr>
                  </Table></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title09.gif" alt="オプション" width="125" height="15"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0"> 
                      <td width="20%" align="center">サービス</td>
                      <td width="60%" align="center">概要</td>
                      <td width="20%" align="center">料金</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">サンプル数割付</td>
                      <td bgcolor="#FFFFFF" width="60%">性年代別のサンプル数割付機能をご利用いただいた場合の料金です。 <br />
この機能をお使いの場合にのみ、オプション機能として「未既婚」 もしくは「居住地域」の発信割合設定を無料でご利用いただけます。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">10,000円／1割付</td>
                    </tr>
                    <? /*
					 <tr>
                      <td bgcolor="#FFFFFF" align="center">属性追加</td>
                      <td bgcolor="#FFFFFF">対象条件設定で「子供の有無」「同居家族」を対象者絞込み条件としてご利用いただいた場合のそれぞれの料金です。</td>
                      <td bgcolor="#FFFFFF" align="center">5,000円/1属性</td>
                    </tr>
					 */
					 ?>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">スピードチャージ</td>
                      <td bgcolor="#FFFFFF" width="60%">調査開始日の1営業日前を過ぎた場合に適用されます。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">20,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">画像加工料</td>
                      <td bgcolor="#FFFFFF" width="60%">画像を見せてアンケートをしたい時、事務局で画像を加工する場合にかかる費用です。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">3,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">サーバ利用料</td>
                      <td bgcolor="#FFFFFF" width="60%">アンケートで画像を見せる場合のサーバー使用料です。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">5,000円</td>
                    </tr>                                                            
                  </table>                </td>
              </tr>
			   <tr>
			     <td><img src="images/common/spacer.gif" width="10" height="10"></td>
		      </tr>
			   <tr>
			     <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0">
                      <td width="20%" rowspan="2" align="center">サービス</td>
                      <td colspan="6" align="center">質問数</td>
                    </tr>
                    <tr bgcolor="#e5eaf0">
                      <td align="center">5問まで</td> 
                      <td align="center">10問まで</td>
                      <td align="center">15問まで</td>
                      <td align="center">20問まで</td>
                      <td align="center">25問まで</td>
                      <td align="center">30問まで</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">調査票設計費</td>
                      <td colspan="4" bgcolor="#FFFFFF" align="center">80,000円</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">160,000円</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">代理設定</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">20,000円</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">30,000円</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">40,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">基礎集計費</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">30,000円</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">80,000円</td>
                      <td colspan="2" bgcolor="#FFFFFF" align="center">160,000円</td>
                      </tr>                                                                                
                  </table>                </td>
		      </tr>
<tr>
  <td class="red">※ 上記の基礎集計費は本アンケートのみの料金です。<br />
　　ファインディング調査基礎集計費は別途30,000円となります。</td>
</tr>
			   <tr> 
                <td class="footer_text" align="right">※上記は全て税別です。</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="30"></td>
              </tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->


<? marketer_footer (BLUE) ?>
