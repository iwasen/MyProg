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

<? marketer_menu(M_MYENQ); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title02.gif" alt="【Myアンケート】簡単に安く低価格なネットリサーチ！アンケート調査結果はマーケティングのプレゼン資料で効果的に" width="114" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title08.gif" alt="Ｍｙアンケート料金表" width="553" height="24"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td>きかせて･netモニターを対象としたリサーチサービスです。<br>
				基本プランは「３問、３００通発信、３万円」です。オプションで質問数と発信数の追加ができます。<br>
                  回答率は平均で40%です。回収できる回答数の目安は<a href="mk/service/mk_myenq_price_sample.php" target="_blank">こちら</a>をご覧ください。</td>
              </tr>			  
               <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
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
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" ColSpan="10">質問数</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" BgColor="#e5eaf0">3</Td>
                      <Td Align="center" BgColor="#e5eaf0">4</Td>
                      <Td Align="center" BgColor="#e5eaf0">5</Td>
                      <Td Align="center" BgColor="#e5eaf0">6</Td>
                      <Td Align="center" BgColor="#e5eaf0">7</Td>
                      <Td Align="center" BgColor="#e5eaf0">8</Td>
                      <Td Align="center" BgColor="#e5eaf0">9</Td>
                      <Td Align="center" BgColor="#e5eaf0">10</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" RowSpan="10">発<br>
                        信<br>
                        数<br>                      </Td>
                      <Td BgColor="#FFFFFF" Align="center">300</Td>
                      <Td BgColor="#FFFFFF" align="right">30</Td>
                      <Td BgColor="#FFFFFF" align="right">34</Td>
                      <Td BgColor="#FFFFFF" align="right">38</Td>
                      <Td BgColor="#FFFFFF" align="right">42</Td>
                      <Td BgColor="#FFFFFF" align="right">46</Td>
                      <Td BgColor="#FFFFFF" align="right">51</Td>
                      <Td BgColor="#FFFFFF" align="right">55</Td>
                      <Td BgColor="#FFFFFF" align="right">59</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">600</Td>
                      <Td BgColor="#FFFFFF" align="right">54</Td>
                      <Td BgColor="#FFFFFF" align="right">61</Td>
                      <Td BgColor="#FFFFFF" align="right">68</Td>
                      <Td BgColor="#FFFFFF" align="right">71</Td>
                      <Td BgColor="#FFFFFF" align="right">74</Td>
                      <Td BgColor="#FFFFFF" align="right">77</Td>
                      <Td BgColor="#FFFFFF" align="right">80</Td>
                      <Td BgColor="#FFFFFF" align="right">83</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">900</Td>
                      <Td BgColor="#FFFFFF" align="right">59</Td>
                      <Td BgColor="#FFFFFF" align="right">66</Td>
                      <Td BgColor="#FFFFFF" align="right">73</Td>
                      <Td BgColor="#FFFFFF" align="right">80</Td>
                      <Td BgColor="#FFFFFF" align="right">87</Td>
                      <Td BgColor="#FFFFFF" align="right">94</Td>
                      <Td BgColor="#FFFFFF" align="right">101</Td>
                      <Td BgColor="#FFFFFF" align="right">106</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1200</Td>
                      <Td BgColor="#FFFFFF" align="right">79</Td>
                      <Td BgColor="#FFFFFF" align="right">88</Td>
                      <Td BgColor="#FFFFFF" align="right">97</Td>
                      <Td BgColor="#FFFFFF" align="right">106</Td>
                      <Td BgColor="#FFFFFF" align="right">115</Td>
                      <Td BgColor="#FFFFFF" align="right">124</Td>
                      <Td BgColor="#FFFFFF" align="right">133</Td>
                      <Td BgColor="#FFFFFF" align="right">141</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1500</Td>
                      <Td BgColor="#FFFFFF" align="right">94</Td>
                      <Td BgColor="#FFFFFF" align="right">104</Td>
                      <Td BgColor="#FFFFFF" align="right">114</Td>
                      <Td BgColor="#FFFFFF" align="right">124</Td>
                      <Td BgColor="#FFFFFF" align="right">134</Td>
                      <Td BgColor="#FFFFFF" align="right">144</Td>
                      <Td BgColor="#FFFFFF" align="right">154</Td>
                      <Td BgColor="#FFFFFF" align="right">166</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1800</Td>
                      <Td BgColor="#FFFFFF" align="right">114</Td>
                      <Td BgColor="#FFFFFF" align="right">127</Td>
                      <Td BgColor="#FFFFFF" align="right">140</Td>
                      <Td BgColor="#FFFFFF" align="right">153</Td>
                      <Td BgColor="#FFFFFF" align="right">166</Td>
                      <Td BgColor="#FFFFFF" align="right">179</Td>
                      <Td BgColor="#FFFFFF" align="right">192</Td>
                      <Td BgColor="#FFFFFF" align="right">204</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2100</Td>
                      <Td BgColor="#FFFFFF" align="right">125</Td>
                      <Td BgColor="#FFFFFF" align="right">139</Td>
                      <Td BgColor="#FFFFFF" align="right">153</Td>
                      <Td BgColor="#FFFFFF" align="right">167</Td>
                      <Td BgColor="#FFFFFF" align="right">181</Td>
                      <Td BgColor="#FFFFFF" align="right">195</Td>
                      <Td BgColor="#FFFFFF" align="right">209</Td>
                      <Td BgColor="#FFFFFF" align="right">223</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2400</Td>
                      <Td BgColor="#FFFFFF" align="right">145</Td>
                      <Td BgColor="#FFFFFF" align="right">161</Td>
                      <Td BgColor="#FFFFFF" align="right">177</Td>
                      <Td BgColor="#FFFFFF" align="right">193</Td>
                      <Td BgColor="#FFFFFF" align="right">209</Td>
                      <Td BgColor="#FFFFFF" align="right">225</Td>
                      <Td BgColor="#FFFFFF" align="right">241</Td>
                      <Td BgColor="#FFFFFF" align="right">258</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2700</Td>
                      <Td BgColor="#FFFFFF" align="right">163</Td>
                      <Td BgColor="#FFFFFF" align="right">181</Td>
                      <Td BgColor="#FFFFFF" align="right">199</Td>
                      <Td BgColor="#FFFFFF" align="right">217</Td>
                      <Td BgColor="#FFFFFF" align="right">235</Td>
                      <Td BgColor="#FFFFFF" align="right">253</Td>
                      <Td BgColor="#FFFFFF" align="right">271</Td>
                      <Td BgColor="#FFFFFF" align="right">290</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">3000</Td>
                      <Td BgColor="#FFFFFF" align="right">168</Td>
                      <Td BgColor="#FFFFFF" align="right">187</Td>
                      <Td BgColor="#FFFFFF" align="right">206</Td>
                      <Td BgColor="#FFFFFF" align="right">225</Td>
                      <Td BgColor="#FFFFFF" align="right">244</Td>
                      <Td BgColor="#FFFFFF" align="right">263</Td>
                      <Td BgColor="#FFFFFF" align="right">282</Td>
                      <Td BgColor="#FFFFFF" align="right">301</Td>
                    </Tr>
                  </Table>
                </td>
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
                      <td bgcolor="#FFFFFF" width="20%" align="center">エージェントパック</td>
                      <td bgcolor="#FFFFFF" width="60%">Myアンケートの基本プラン（3問、300通配信、3万円）のパッケージサービスです。基本プランを5回分まとめてお求めいただきますと、1回分お得になります。定期的なリサーチをご計画されている方にはぴったりです。（通常150,000円→120,000円） 
                        ※オプション（質問数と発信数の追加）をご利用の場合には、上記料金表との差額分のみをご請求させていただきます。※有効期間は、パッケージご購入月を含めて6ヶ月間とさせていただきます。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">120,000円</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">発信数割付<br>
                        （セル指定）</td>
                      <td bgcolor="#FFFFFF" width="60%">性年代別の発信数割付機能をご利用いただいた場合の料金です。 <br />
                      この機能をお使いの場合にのみ、オプション機能として「未既婚」 もしくは「居住地域」の発信割合設定を無料でご利用いただけます。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">5,000円/1割付</td>
                    </tr>                    
                    <tr>
                      <td bgcolor="#FFFFFF" align="center">属性追加</td>
                      <td bgcolor="#FFFFFF">対象条件設定で「子供の有無」「同居家族」を対象者絞込み条件として ご利用いただいた場合のそれぞれの料金です。</td>
                      <td bgcolor="#FFFFFF" align="center">5,000円/1属性</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">代理設定</td>
                      <td bgcolor="#FFFFFF" width="60%">事務局で代理に設問を設定する場合の費用です。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">20,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center" height="51">スピードチャージ</td>
                      <td bgcolor="#FFFFFF" width="60%" height="51">AM11時を過ぎての申請で、当日発信をしたい場合はスピードチャージをいただきます。ただし16時以降でのお申し付けは翌営業日のAM発信となります。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%" height="51">10,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">画像加工料</td>
                      <td bgcolor="#FFFFFF" width="60%">画像を見せてアンケートをしたい時、事務局で画像を加工する場合にかかる費用です。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">3,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">サーバ利用料</td>
                      <td bgcolor="#FFFFFF" width="60%">アンケートで画像を見せる場合のサーバー使用料です。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">5,000円</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">基礎集計料</td>
                      <td bgcolor="#FFFFFF" width="60%">2属性間までのクロス集計を代行いたします。ご納品形態は、集計票とグラフです。Excel形式にてご納品いたします。</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">30,000円</td>
                    </tr>
                  </table>
                </td>
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
