<?php
include ( "../sub/head.inc");

Function chkpwd($text_data) {
	if ( $text_data != null ) {
		$text_data = ereg_replace("[^a-zA-Z0-9]","",$text_data);
		if ( ereg("([a-zA-Z0-9]+)",$text_data,$regs) ) {
			if ( strlen($text_data) == strlen($regs[0]) ) {
				return $text_data;
			} else {
				return -1;
			}
		} else {
			return -1;
		}
	} else {
		return -1;
	}
}

/****************************************************************
	Main [ all code from /melonpa/register4.php]
****************************************************************/
$log_key_flg = "0";

$sql = "select * from m_melonpa where mail_addr='$mail_addr'";
$result = db_exec($sql);
$ncols = pg_numrows($result);
if ($ncols == 0) {
	Do {
		$log_key = crypt($mail_addr,substr(time(),-2));
		$log_key = chkpwd($log_key);
	}While ($log_key == -1 || $log_key == "");
	$log_key = substr($log_key,0,6);
	$reader_id = update_squ_key ("M_MELONPA", $reader_id);
	if ($reader_id != "-1") {
		$pt_MELONPAN = 0;
		$pt_melmel = 0;
		$sql = "INSERT INTO M_MELONPA (reader_id,mail_addr,reader_pswd,reader_birth,reader_sex,reader_mrg,reader_zip1,reader_zip2,reader_occup,pt_melonpan,"
			. "pt_melmel,log_key,log_key_flg,modify_dt,regist_dt)"
			. " VALUES ($reader_id,'$mail_addr','$reader_pswd','$reader_birth','$reader_sex','$reader_mrg','$reader_zip1','$reader_zip2','$reader_occup'"
			. ",$pt_MELONPAN,$pt_melmel,'$log_key','$log_key_flg','now','now')";
		db_exec($sql);
	}
} else {
	$fetch = pg_fetch_object($result, 0);
	$reader_id = $fetch->reader_id;
	$reader_id = sprintf("%09d", $reader_id);
	$log_key = $fetch->log_key;

	$sql = "UPDATE M_MELONPA SET mail_addr = '$mail_addr', "
					. "reader_pswd = '$reader_pswd',"
					. "reader_birth = '$reader_birth', "
          . "reader_sex = '$reader_sex', "
          . "reader_mrg = '$reader_mrg', "
          . "reader_zip1 = '$reader_zip1', "
          . "reader_zip2 = '$reader_zip2', "
          . "reader_occup = '$reader_occup', "
//          . "log_key = '$log_key', "
          . "log_key_flg = '$log_key_flg', "
					. "modify_dt    = 'now'"
				. " WHERE reader_id = $reader_id ";
	db_exec($sql);
}

setcookie("cookie_mail_addr", trim($mail_addr), time()+604800, "/~melonpa/");
setcookie("cookie_reader_pswd", trim($reader_pswd), time()+604800, "/~melonpa/");
setcookie("cookie_reader_id", $reader_id, time()+604800, "/~melonpa/");
//include ("tmpl_html/regist4.html");

mag_new_mail($mail_addr, $log_key ,$reader_id);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/melonpa1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/melonpa3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/melonpa2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/melonpa4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/toroku.gif" width="129" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" -->
      <p>　</p>
      <p>　</p>
      <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
      <table width="557" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td width="550"><br>
            <p><font color="#336600"><b>めろんぱん登録ありがとうございます。<br>
              </b></font></p>
            <hr size="1" width="100%">
            <p><font color="#336600"></font>登録していただいたメールアドレスに、あなた<font color="#336600"><b><font color="#990000">専用のURL</font></b></font>を記載した<br>
              <font color="#336600"><b><font color="#990000">「確認メール」</font></b></font>をお送りしますので、その<font color="#336600"><b><font color="#990000">URL</font></b></font>からジャンプしてください。<br>
              <br>
              これで登録は全て完了です。<br>
              <br>
              あなたのメールアドレスとあなたの決めたパスワードでマイページに入れるようになります。<br>
              <br>
              ※なお、登録が完了しないと、マイページに入ることができません。<br>
            </p>
            <hr size="1" width="100%">
            <p> <a href="../index.html" target="_top"><img src="../common/home.gif" width="41" height="41" border="0"><br>
              <br>
            </p>
            </td>
        </tr>
      </table>
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
