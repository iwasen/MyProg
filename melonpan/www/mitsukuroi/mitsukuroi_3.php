<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("$compath/sql_mpai.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl = "みつくろい依頼";
$send_mail_addr = trim(strtolower($send_mail_addr));
$print_msg = "";
if (strlen($com_title) == 0) {
	$print_msg .=  "・「タイトル」が入力されていないようです。<br>";
} elseif (strlen($com_title) > 100) {
	$print_msg .=  "・「タイトル」は全角５０文字以内で入力してください。<br>";
}
if (strlen($com_text) == 0) {
	$print_msg .=  "・「コメント」が入力されていないようです。<br>";
} elseif (strlen($com_text) > 800) {
	$print_msg .=  "・「コメント」は全角４００文字以内で入力してください。<br>";
}
if (strlen($send_mail_addr) == 0) {
	$print_msg .=  "・「メールアドレス」が入力されていないようです。<br>";
} elseif (strlen($send_mail_addr) > 800) {
	$print_msg .=  "・「メールアドレス」は半角５０文字以内で入力してください。<br>";
} else {
	if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $send_mail_addr)) ) {
		$print_msg .= "・「メールアドレス」が正しく入力されていないようです。<br>";
	}
}
if ($print_msg !=  "") {
	include("../sub/err_msg.html");
	exit();
}

$sql = "SELECT *  from m_melonpai where  melonpai_id = ";
$sql = $sql . $melonpai_id;
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_pro = $fetch->melonpai_pro;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>

<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/g1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/g3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/g2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/g4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/pitari.gif" width="271" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --><br>
            <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
<!---->
<form name = "TaiForm4" method="post" action = "mitsukuroi_4.php">
<!---->
      <table width="513" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td>
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                <td><font color="#99ccff"><span class="text01"><font color="#FF9900">■</font><b><font color="#000000">みつくろい依頼【確認】</font></b></span></font></td>
              </tr>
            </table>
            <font color="#99ccff"><span class="text01"><br>
            　</span></font>下記の内容で依頼してよろしいですか？　修正する場合は「戻る」ボタンでお戻りください。 
            <p>
            <table border="0" cellpadding="0" cellspacing="0" bgcolor="#91c741">
              <tr> 
                <td>
                  <table width="539" border="0" cellspacing="1" cellpadding="2">
                    <tr> 
                      <td width="185" bgcolor="#e2f0c9" class="text9ptnoColor"> 
                        <div align="right"> <font color="#336600">依頼するメルマガナビ</font></div>
                      </td>
                      <td width="343" bgcolor="white">
                        <? echo $melonpai_nic ?>
                        　</td>
                    </tr>
                    <tr> 
                      <td width="185" bgcolor="#e2f0c9" valign="top" class="text9ptnoColor"> 
                        <div align="right"> <font color="#336600">タイトル</font></div>
                      </td>
                      <td width="343" bgcolor="white">
                        <? echo htmlspecialchars(stripcslashes($com_title)) ?>
                        　</td>
                    </tr>
                    <tr> 
                      <td width="185" bgcolor="#e2f0c9" valign="top" class="text9ptnoColor"> 
                        <div align="right"> <font color="#336600">リクエスト</font></div>
                      </td>
                      <td width="343" bgcolor="white">
                        <? echo htmlspecialchars(stripcslashes($com_text)) ?>
                        　</td>
                    </tr>
                    <tr> 
                      <td width="185" bgcolor="#e2f0c9" class="text9ptnoColor"> 
                        <div align="right"> <font color="#336600">返信先</font></div>
                      </td>
                      <td width="343" bgcolor="white">
                        <? echo $send_mail_addr ?>
                        　</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <br>
            <hr size="1" width="100%">
            <input type="submit" name="TaiBot4" value="　依　頼　">
            <input type="button" name="Submit2" value="戻る" onClick="history.back()">

<input type="hidden" value="<? echo $melonpai_id ?> " name="melonpai_id">
<input type="hidden" value="<? echo $melonpai_pro ?> " name="melonpai_pro">
<input type="hidden" value="<? echo $send_mail_addr ?> " name="send_mail_addr">
<input type="hidden" value="<? echo $com_title ?> " name="com_title">
<input type="hidden" value="<? echo $com_text ?> " name="com_text">
<input type="hidden" value="<? echo $mail_addr ?> " name="mail_addr">
<input type="hidden" value="<? echo trim($mail_addr); ?> " name="mail_addr">
<input type="hidden" value="<? echo trim($reader_id); ?>" name="reader_id">
<input type="hidden" value="<? echo trim($reader_pswd); ?>" name="reader_pswd">
<!--
            <input type="hidden" value="&lt;? echo $melonpai_id ?&gt; " name="melonpai_id">
            <input type="hidden" value="&lt;? echo $melonpai_pro ?&gt; " name="melonpai_pro">
            <input type="hidden" value="&lt;? echo $send_mail_addr ?&gt; " name="send_mail_addr">
            <input type="hidden" value="&lt;? echo $com_title ?&gt; " name="com_title">
            <input type="hidden" value="&lt;? echo $com_text ?&gt; " name="com_text">
            <input type="hidden" value="&lt;? echo $mail_addr ?&gt; " name="mail_addr">
            <input type="hidden" value="&lt;? echo trim($mail_addr); ?&gt; " name="mail_addr">
            <input type="hidden" value="&lt;? echo trim($reader_id); ?&gt;" name="reader_id">
            <input type="hidden" value="&lt;? echo trim($reader_pswd); ?&gt;" name="reader_pswd">
-->
          </td>
        </tr>
      </table>
<!---->
</form>
<!---->      
	  <br>
      <br>
      <br>
      <br>
      <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">　</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">　</td>
          <td width="557" bgcolor="#90c84b">　</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php draw_footer( ".." ); ?>

      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
