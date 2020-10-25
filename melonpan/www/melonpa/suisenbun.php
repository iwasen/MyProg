<?php
include("../inc/com_db_func.inc");
include("../sub/head.inc");
include("../sub/csub.inc");

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
<title>めろんぱん || よりすぐりメルマガサイト</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
.text12 {  font-size: 12px; line-height: 145%; color: #000000}
.text14 {  font-size: 14px; line-height: 145%; color: #000000; font-weight: bold}
.bold {  font-size: 12px; line-height: 145%; font-weight: bold; color: #FF6600}
.text10 {  font-size: 10px; line-height: 125%; color: #666666}
-->
</style>
<script LANGUAGE="javascript">
<!--
	function subWin(){
		window.open('thankyoutip.html','newWin','width=318,height=300,scrollbars=yes,resizable=no');
		}
-->
</script>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("..");	?>
<table width="670" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="30"></td>
    <td width="620">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="620"><img src="img/touroku_header.gif" width="620" height="37"></td>
  </tr>
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="20"></td>
    <td width="620" bgcolor="#FFFFCC"> 
      <div align="center"><img src="img/flow.gif" width="504" height="60" vspace="20"> 
      </div>
    </td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="620" bgcolor="#FFFFCC"> 
      <table width="500" border="1" cellspacing="0" cellpadding="0" bordercolor="#99CC33" align="center">
        <tr> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td bgcolor="#99CC33" width="500"><img src="img/touroku.gif" width="240" height="20" hspace="2" alt="Myめろんぱん登録をすると？" vspace="2"></td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>購読しているメルマガが一覧で見られます。</div>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#e2f0c9" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>購読しているメールアドレスを新しいアドレスに一括変換できます。</div>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>新しいメルマガを購読する際に、メールアドレスを入力せずに購読できます。</div>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#e2f0c9" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>購読しているメルマガに<span class="bold">推薦文</span>を書くことが出来ます。<span class="bold">推薦文</span>を書くと<span class="bold">ポイント</span>が貯まります。</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="20"></td>
    <td width="620" bgcolor="#FFFFCC">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="620" bgcolor="#FFFFCC">
      <table width="500" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF6600" align="center">
        <tr> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td bgcolor="#FF6600" width="500"><img src="img/suisenbun.gif" width="122" height="18" hspace="2" alt="推薦文って？" vspace="3"></td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>Ｍｙめろんぱんに登録すると、あなたが読んでいるメルマガに推薦文を書くことができます。</div>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>心のこもった推薦文はめろんぱんのサイト上に公開されます。</div>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" valign="middle" width="500">
                  <div class="text12"><font color="#FF9933">■</font>推薦文がサイト上に公開されると、事務局からもれなくポイントがもらえます。</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="20"></td>
    <td width="620" bgcolor="#FFFFCC">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="620" bgcolor="#FFFFCC"> 
      <table width="500" border="1" cellspacing="0" cellpadding="0" bordercolor="#99CC33" align="center">
        <tr> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr> 
                <td bgcolor="#99CC33" width="343"><img src="img/use_point.gif" width="234" height="19" hspace="2" alt="貯まったポイントを使うには？" vspace="2"></td>
                <td bgcolor="#99CC33" width="157"> 
                  <div align="right"><img src="img/spacer.gif" width="157" height="1"></div>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="343"><div class="text12"><font color="#FF9933">■</font>お気に入りのメルマガにサンキューチップをあげる</div></td>
                <td width="157" valign="bottom"> 
                  <div align="right"><a href="javascript:subWin()"><img src="img/thankyoutip.gif" width="157" height="18" border="0" alt="サンキューチップって何？"></a></div>
                </td>
              </tr>
              <tr bgcolor="#e2f0c9"> 
                <td width="343" valign="middle"><div class="text12"><font color="#FF9933">■</font>図書券・商品券と交換</div>
                </td>
                <td width="157" valign="middle"> 
                  <div align="right" class="text12">（3000ポイント〜）</div>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="343" valign="middle"><div class="text12"><font color="#FF9933">■</font><a href="http://www.haimail.net/" target="_blank">はいめーる・net</a>で多くの人へメッセージを発信</div>
                </td>
                <td width="157" valign="middle"> 
                  <div align="right" class="text12">（5000ポイント〜）</div>
                </td>
              </tr>
              <tr bgcolor="#e2f0c9"> 
                <td width="343" valign="middle"><div class="text12"><font color="#FF9933">■</font>現金と交換</div>
                </td>
                <td width="157" bgcolor="#e2f0c9" valign="middle"> 
                  <div align="right" class="text12">（準備中です）</div>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="343" valign="middle"><div class="text12"><font color="#FF9933">■</font>ecサイトでショッピング</div>
                </td>
                <td width="157" valign="middle"> 
                  <div align="right" class="text12">（準備中です）</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="20"></td>
    <td width="620" bgcolor="#FFFFCC">　</td>
  </tr>
  <tr> 
    <td width="50">　</td>
    <td width="620" bgcolor="#FFFFCC"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="210"><img src="img/spacer.gif" width="210" height="1"></td>
          <td width="200"><a href="../melonpan/melonpatoha.php"><img src="img/regist.gif" width="200" height="30" border="0" alt="Ｍｙめろんぱん登録をする"></a></td>
          <td valign="bottom" width="210"> 
            <div align="right"><a href="../melonpa/MelonpanRef.php"><img src="img/regist2.gif" width="148" height="17" hspace="10" border="0" alt="登録をお済みの方はこちら"></a></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="20"></td>
    <td width="620" bgcolor="#FFFFCC">　</td>
  </tr>
  <tr>
    <td width="50">　</td>
    <td width="620">
	 <table width="620" border="0" cellspacing="0" cellpadding="0">
		 <tr><td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="578" bgcolor="#90c84b">　</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td></tr></table>
		  </td>
  </tr>
</table>
<table width="670" border="0" cellspacing="0" cellpadding="0">
<?php draw_footer( ".." ); ?>
</table>
</body>
</html>
