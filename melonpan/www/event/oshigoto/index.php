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
<title>わたしのお仕事ウラ話！？リレー連載コーナー</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
.text12 {  font-size: 12px; line-height: 125%}
.text10 {  font-size: 10px; line-height: 125%}
.syokugyou {  font-size: 12px; line-height: 125%; background: #CEDBFD; font-weight: bold}
.mailtitle {  font-size: 12px; line-height: 125%; background: #ACACFF}
.mailex {  font-size: 12px; line-height: 125%; background: #FFFFFF}
.read {  font-size: 12px; line-height: 125%; background: #CEDBFD}
-->
</style>
<script language="javascript">
<!--
function subWin(occupation){
	window.open(occupation,'colum','width=420,height=450,resizable=yes,scrollbars=yes');
	}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("../..");	?>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700"> 
      <div align="center"><img src="img/title.gif" width="323" height="79" alt="私のお仕事　ウラ話"></div>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="118"><img src="img/spacer.gif" width="100" height="1"></td>
    <td width="531" class="text12"> 
      <div align="center"><b>みなさんが購読しているメルマガの発行者さんは<br>
        実は（当然ながら？）お仕事のあいまを縫って発行している方が多いのです。<br><br>
        このコーナーは、そんな発行者さんたちによる<br>
        “ わたしのお仕事ウラ話！？ ” を、リレー連載でお届けします！</b></div>
    </td>
    <td width="101"><img src="img/spacer.gif" width="100" height="1"></td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700"> 
	<form action="http://www.melonpan.net/melonpa/register.php" method="post">
        <table width="300" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
          <td bgcolor="#669933">
              <div align="center"><font color="#ffffff"><b><span class="text12">この連載コーナーが楽しめる<br>『めろんぱんレター』のご購読はこちらから</span></b> 
                </font></div>
          </td>
        </tr>
        <tr>
          <td bgcolor="#fff9c2">
            <div align="center"><font color="#006600" class="text12">E-mail</font> 
              <input type="hidden" name="mag_id" value="000049">
              <input type="text" size="22" name="email">
              <input type="submit" value="購読" name="regist">
            </div>
			
          </td>
        </tr>
      </table>
	  </form>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="21"><img src="img/round_1.gif" width="21" height="21"></td>
    <td width="658" bgcolor="#99CC66">　</td>
    <td width="21"><img src="img/round_2.gif" width="21" height="21"></td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">お仕事　＆　連載記事</div>
          </td>
          <td width="450" class="mailtitle"> 
            <div align="center"><b>この記事のライターさんが書いているメルマガ</b></div>
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">アナウンサー</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001872" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0">◇◇アナウンサー生活　〜ワタシの場合〜◇◇</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay01.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">現役の局アナまるがアナウンサーの普段の生活を紹介。<br>アナウンサーを目指す方、同業者の方もどうぞ♪ 
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50" height="14">　</td>
    <td width="21" bgcolor="#ffffc0" height="14">　</td>
    <td width="658" bgcolor="#ffffc0" height="14">　</td>
    <td width="21" bgcolor="#ffffc0" height="14">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">コピーライター</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000691" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000691" target="_blank">クリエーターコラムマガジン「zekt」</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay03.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">クリエーターコラムマガジン「zekt」は、プロがつぶやくメールマガジンです。<br>毎週日曜日配信です。
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">ＩＴ業界</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000212" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000212" target="_blank">実戦・情報処理の現場から</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay10.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">現職ソフトエンジニア(SE)が、現場において感じたことを書いたコラム。<br>業界に関係無い方も楽しめます。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">カウンセラー</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002087" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002087" target="_blank">カウンセリングという名の対話</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay02.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">独自の〈対話法〉を提唱するカウンセラーが、「質の高い対話」という観点からカウンセリングを語ります。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">幼稚園ＰＴＡ役員</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002368" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002368" target="_blank">ママ's　ＨＰ</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay04.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">ママの作っているＨＰを1つずつ丁寧に紹介していくメルマガです。<br>気の合うママを見つけてください。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">ケーキ屋</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000866" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000866" target="_blank">恋愛詞集-pc版</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay05.php')">連載を読む</a></div>
           </td>
          <td width="450" class="mailex">最愛の恋人は、わたしのことを忘れていた──大学生・坂月萌実は、突然誰からも忘れられてしまって…!?</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">金融業界</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000933" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000933" target="_blank">…‥・Heaven's 
            Place・‥…</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay07.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">恋愛エッセイ風のひとりごとが、時に短い物語や詩、誰かに宛てた手紙だったり。<br>痛い系もややあり？ 
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">部品製造・アクセサリー販売</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002062" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002062" target="_blank">草加 
            Treasure Box</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay12.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex"> 
            <div align="left">埼玉・草加で暮らす遊ぶ情報メールマガジン「草加 Treasure Box」</div>
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">海外駐在の商品企画マン</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002199" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002199" target="_blank">VIC's 
            D.I.Y.最新情報</a></td>
        </tr>
        <tr> 
          <td width="208" class="read">
            <div align="center"><a href="javascript:subWin('relay06.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">誰にも出来る日曜大工を製作記事を含め提唱しているVIC'ｓDIYの最新情報と、DIYヒントをお届けします。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">映像演出家</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000246" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000246" target="_blank">「つき」を呼ぶメールマガジン</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay14.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">つきを呼ぶためのこつを潜在意識を利用したイメージトレーニングを中心にお届けする実践的幸運招福法。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">会社役員</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002290" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002290" target="_blank">藤原雄一郎の「この一冊で企業がよみがえる」</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay11.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">デフレ時代の生き残り必勝法です。<br>世界規模の弱肉強食の世界でどのようにするべきか？明快に答えます。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">添乗員</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000136" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000136" target="_blank">セカンドクラスの添乗員</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay16.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">元添乗員が経験したお客様との攻防戦、変な手配との格闘をお話しします。 </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">中国で日本料理のアルバイト</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002164" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002164" target="_blank">夢・夢・ラッキー７！！</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
             <div align="center"><a href="javascript:subWin('relay08.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">只今花の１７歳！笑）日記形式で、最近思った事、気付いた事、詩などを、面白おかしく書きます！</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">商社マン</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001041" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001041" target="_blank">【 
            金融機関は恐くない 】</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay13.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">『金融機関を利用するための』『金融機関に利用されないための』情報を公開します。</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">Webショップ運営</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001932" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001932" target="_blank">働きながら家庭も大切にしたいあなたへ　〜花十彩通信</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay09.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">１０年後の自分が輝く為に今の自分ができることを考えてみませんか？<br>店主の独立開業コラムと花十彩の情報♪</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50" height="60">　</td>
    <td width="21" bgcolor="#ffffc0" height="60">　</td>
    <td width="658" bgcolor="#ffffc0" height="60"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">電器屋店員</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000123" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000123" target="_blank">SonyShop 
            C-TEC News</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay15.php')">連載を読む</a></div>
          </td>
          <td width="450" class="mailex">ショップの店員が実際に触っての商品レビュー等をお届けします。ソニーファン必見です！</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0" height="60">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21" bgcolor="#ffffc0">　</td>
    <td width="658" bgcolor="#ffffc0">　 </td>
    <td width="21" bgcolor="#ffffc0">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="21"><img src="img/round_3.gif" width="21" height="21"></td>
    <td width="658" bgcolor="#99CC66">　</td>
    <td width="21"><img src="img/round_4.gif" width="21" height="21"></td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50" height="97"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700" height="97"> 
      <form action="http://www.melonpan.net/melonpa/register.php" method="post">
        <table width="300" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr> 
          <td bgcolor="#669933"> 
              <div align="center"><font color="#ffffff"><b><span class="text12">この連載コーナーが楽しめる<br>『めろんぱんレター』のご購読はこちらから</span></b><span class="text12"> 
                </span> </font></div>
          </td>
        </tr>
        <tr> 
          <td bgcolor="#fff9c2">
            <div align="center"><font color="#006600" class="text12">E-mail</font> 
              <input type="hidden" name="mag_id" value="000049">
              <input type="text" size="22" name="email">
              <input type="submit" value="購読" name="regist">
            </div>
			</td>
        </tr>
      </table>
	  </form>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="30"></td>
    <td>　</td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td class="text12"> 
      <div align="center">※<a href="../index.php">めろんぱん</a>は、（株）○○○○○○の事業理念「発信する生活者を支援する」に基づいて運営されています。</div>
    </td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td class="comment"> 
      <hr noshade width="75%">
    </td>
  </tr>
  <tr>
    <td width="25">　</td>
    <td class="text12">
      <div align="center">Copyright(c)2001 xxxxxxx, Co., Ltd. All rights reserved.<br>
        <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
    </td>
  </tr>
</table>
</body>
</html>
