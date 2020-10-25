<?php
include("../../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
//$gConn = db_conxxxt();

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
?>

<html>
<head>
<title>めろんぱん　ワールドカップ特別企画</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<script language="JavaScript">
<!--
	function prediction(wURL){
			window.open(wURL,'Prediction','width=370,height=500,scrollbars=yes,resizable=no');
			}
//-->
</script>
<style type="text/css">
<!--
.maincontents {  background: #B0D8FF}
.question { font-size: 12px; line-height: 135%; font-weight: bold; color: #FFFFFF; background: #0033CC}
.name {  font-size: 12px; line-height: 135%; color: #000000; background: #FFFFFF}
.answer {  font-size: 12px; line-height: 135%; font-weight: bold; color: #000000; background: #FFFFFF}
h1 {  font-size: 12px; line-height: 135%; font-weight: bold; color: #FFFFFF}
.contents {  font-size: 12px; line-height: 135%; color: #333333}
.copyright {  font-size: 10px; line-height: 125%; color: #666666}
.text12 {  font-size: 12px; line-height: 135%; color: #000000}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("../..");	?>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">　</td>
</tr>
<tr> 
<td width="50">　</td>
<td width="700"> 
<div align="center"><img src="img/head.gif" width="398" height="65" alt="メルマガ発行者が注目するワールドカップサッカー2002"></div>
</td>
</tr>
<tr> 
<td width="50">　</td>
<td width="700">　</td>
</tr>
<tr>
<td width="50">　</td>
<td width="700">
      <table width="556" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td class="text12"> 
            <p align="center"><b>今年のワールドカップサッカー。日本で開催されることもあって盛り上がっていますね！<br>
              そこでめろんぱんでは「サッカー系メルマガ」を発行しているメルマガの発行者さんたちに<br>
              今大会の見所、じっくり聞いてみました。<br>
              注目の結果は・・・？？？</b></p>
            </td>
</tr>
</table>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">　</td>
</tr>
<tr> 
<td width="50">　</td>
<td width="700"> 
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="340" class="maincontents"> <img src="img/spacer.gif" width="1" height="20"><br>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr>
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>注目の日本人選手は？</b></font></td>
<td width="44"><a href="javascript:prediction('player_domestic.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="理由を知りたい！" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">　fpさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">小野伸二</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　akinoriさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">市川大祐</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　エリックさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">小野伸二</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　お気楽蹴球団団長さん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">全員</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_1.gif" width="84" height="63"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>注目の外国人選手は？</b></font></td>
<td width="44"><a href="javascript:prediction('player.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="理由を知りたい！" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">　fpさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">ジネディーヌ・ジダン</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　akinoriさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">ルイス・フィーゴ</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　エリックさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">オリバー・カーン</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　お気楽蹴球団団長さん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">ロベルト・バッジォ</div>
</td>
</tr>
</table>
<p>　</p><table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>注目チームは？</b></font></td>
<td width="44"><a href="javascript:prediction('team.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="理由を知りたい！" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">　fpさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">ポルトガル</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　akinoriさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">アルゼンチン</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　エリックさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">ポルトガル</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　お気楽蹴球団団長さん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">アルゼンチン</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_2.gif" width="144" height="105"></p>
</td>
          <td width="21">　</td>
          <td width="339" class="maincontents"> 
            <p align="center"><img src="img/worldcup_3.gif" width="220" height="125"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>決勝に残るチームは？優勝は？</b></font></td>
<td width="44"><a href="javascript:prediction('finals.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="理由を知りたい！" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
<td width="130" class="name">　</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">【優勝】</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">【準優勝】</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">　fpさん<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">ブラジル</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">イタリア</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">　akinoriさん<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">アルゼンチン</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">ポルトガル</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">　エリックさん<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">イタリア</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">アルゼンチン</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">　お気楽蹴球団団長さん<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">イタリア</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">アルゼンチン</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_4.gif" width="221" height="125"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>日本は決勝トーナメントに進める？</b></font></td>
<td width="44"><a href="javascript:prediction('japan.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="理由を知りたい！" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">　fpさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">進める</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　akinoriさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">進める</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　エリックさん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">進める</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">　お気楽蹴球団団長さん<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">YES！</div>
</td>
</tr>
</table>
<p>　</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">　</td>
</tr>
<tr> 
    <td width="50" height="103">　</td>
    <td width="700" height="103" bordercolor="#0033CC"> 
      <table width="500" border="1" cellspacing="0" cellpadding="3" align="center" bordercolor="#0033CC">
        <tr> 
          <td colspan="2" class="question"><img src="img/ball.gif" width="22" height="21" vspace="2" hspace="2" align="absmiddle"><font color="#FFFFFF"><b>今回予想をしたメルマガ発行者さんのメルマガはこちら！</b></font></td>
</tr>
<tr> 
          <td width="250" class="name" height="23">●　fpさん<br>
            <a href="http://www.melonpan.net/mag.php?000130" target="_blank">≫≫FOOTBALL 
            PARK MAGAZINE</a><br>
</td>
          <td width="250" class="name" height="23">●　akinoriさん<br>
            <a href="http://www.melonpan.net/mag.php?000143" target="_blank">≫≫こちらサッカー情報局</a></td>
</tr>
<tr> 
<td width="250" class="name">●　エリックさん<br>
            <a href="http://www.melonpan.net/mag.php?000526" target="_blank">≫≫ワールドサッカー新聞</a><br>
            <a href="http://www.melonpan.net/mag.php?000683" target="_blank">≫≫サッカー日本代表・明日に向かって</a><br>
</td>
<td width="250" class="name">●　お気楽蹴球団団長さん<br>
            <a href="http://www.melonpan.net/mag.php?002563" target="_blank">≫≫お気楽蹴球団〜totoをもっと楽しむバモ！</a></td>
</tr>
</table>
    </td>
</tr>
<tr>
    <td width="50" height="33">　</td>
    <td width="700" height="33"> 
      <hr size="2" noshade>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
    <td width="50" height="14"><img src="img/spacer.gif" width="50" height="25"></td>
    <td width="700" height="14">　</td>
</tr>
<tr> 
<td width="50">　</td>
    <td width="700">
      <table width="586" border="2" cellspacing="1" cellpadding="0" bordercolor="#0033FF" align="center" class="contents">
        <tr>
          <td height="134"> 
            <table width="594" border="0">
              <tr> 
                <td width="65">　 </td>
                <td width="530"> 
                  <div align="right"><a href="http://www.hitobito.net/" target="_blank"><img src="img/h_88-31.gif" width="88" height="31" border="0" alt="ひとびと・net"></a></div>
                </td>
              </tr>
              <tr> 
                <td colspan="2" height="22"> 
                  <table width="600" border="0" height="24" bgcolor="#99CCFF">
                    <tr> 
                      <td> 
                        <div align="center"><font size="+1"><b>ワールドカップについてワイワイお話したいかたは・・・<a href="http://www.hitobito.net/nw/worldcup.html" target="_blank">こちら</a></b></font></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td height="53" width="64"> 
                  <div align="right"><a href="http://www.hitobito.net/nw/worldcup.html" target="_blank"><img src="img/navi.gif" width="60" height="60" border="0" hspace="2"></a></div>
                </td>
                <td height="53" width="530"><b><font size="-1">【ワールドカップを話そう！】</font></b><font size="-1"><br>
                  ＴＶ中継で楽しむ人はもちろん、実際スタジアムで観戦したり、地元がキャンプ地になった人達も一緒にワールドカップについて話しませんか？<br>
                  サッカーフリークだけでなく、今回のワールドカップで興味を持ったという方も、みなさん気軽に参加してくださいね！ <br>
                  </font> </td>
              </tr>
            </table>
          </td>
</tr>
</table>
</td>
</tr>
</table>
<table width="749" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="699">　</td>
</tr>
<tr> 
<td width="50">　</td>
    <td width="699"> 
      <table width="594" border="2" cellspacing="1" cellpadding="0" bordercolor="#0033FF" align="center" class="contents" height="109">
        <tr> 
          <td>
            <table width="100%" border="0" height="99">
              <tr> 
                <td>
                  <div align="right"><a href="http://www.kikasete.net/" target="_blank"><img src="img/k_88x31.gif" width="88" height="31" border="0" alt="きかせて・net"></a></div>
                </td>
              </tr>
              <tr> 
                <td>
                    <table width="600" border="0" height="24">
                      <tr>
                        <td bgcolor="#99CCFF"> 
                          <div align="center"><font size="+1"><b>ワールドカップに関するアンケートは・・・<a href="http://www.kikasete.net/monitor/tw47.php" target="_blank">こちら</a></b></font></div>
                        </td>
                      </tr>
                    </table>
                </td>
              </tr>
              <tr> 
                <td height="159"> 
                  <table width="550" border="0" cellspacing="5" cellpadding="0" align="center">
                    <tr> 
                      <td colspan="2" height="14"><b><font size="-1">Ｑ．今大会の試合を見る予定ですか？</font></b> 
                      </td>
                    </tr>
                    <tr> 
                      <td width="275" class="question"> 
                        <div align="center">【　日本戦　】</div>
                      </td>
                      <td width="275" class="question"> 
                        <div align="center">【　日本戦以外　】</div>
                      </td>
                    </tr>
                    <tr> 
                      <td width="275" height="104">
                        <div align="center"><img src="img/graph_1.gif" width="219" height="120" vspace="5"></div>
                      </td>
                      <td width="275" height="104">
                        <div align="center"><img src="img/graph_2.gif" width="263" height="82" vspace="5"></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <div align="right"><font size="-1">2002年3月実施</font></div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
    </td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">　</td>
</tr>
<tr> 
<td width="50">　</td>
<td class="text12" width="700"> 
<div align="center" class="copyright">※<a href="../../index.php">めろんぱん</a>は、（株）○○○○○○の事業理念「発信する生活者を支援する」に基づいて運営されています。<br>
<hr noshade width="75%">
Copyright(c)2001-2002 xx xxxxx Inc.  All rights reserved.<br>
<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
</td>
</tr>
</table>
</body>
</html>