<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("$compath/sql_mpai.inc");
include ( "../sub/head.inc");

function set_global () {
	global $inc_path;
	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

/****************************************************************
 Main
****************************************************************/
set_global ();
//$conn = db_conxxxt();
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
<title>����Ѥ� || ��ꤹ������ޥ�������</title>
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
          <td width="21">��</td>
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
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" -->
      <table width="513" border="0" cellspacing="1" cellpadding="5">
        <tr > 
          <td> 
            <p class="text01">
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                <td> 
                  <p class="text01"><font color="#99ccff"><span class="text01"><font color="#FF9900">��</font><b><font color="#000000">���������ޥ��ʥӤˤߤĤ���äƤ�餪����</font></b></span></font> 
                </td>
              </tr>
            </table>
            <font color="#99ccff"><span class="text01"><br>
            </span></font>�������ǽ񤤤����ʤ�����Τ��ꤤ����������ѥ���åȤ��Ϥ��ޤ���<br>
            �����ʤ��ε������򥿥��ȥ�˥��Х���ᡢ<br>
            �����ʤ����ɤߤ������ޥ�����ħ��ꥯ�����Ȥ˥ꥢ��˽񤤤Ƥ���������<br>
            ��������Ȥ˥��ޥ��ʥӤ�����Ũ�ʥ��ޥ���ߤĤ����ޤ���<br>
            <br>
            �����ʤ����ɤߤ������ޥ��Ϥɤ�ʥ��ޥ��Ǥ�����<br>
            <br>
            ���ɤߤ������ޥ�����ħ��񤤤��顢�Ǹ�ˡֳ�ǧ�ץܥ���򲡤��Ƥ���������<br>
            <br>
            <table width="538" border="0" cellspacing="1" cellpadding="2">
              <tr> 
                <td width="150" class="text9pt"> 
                  <center>
                    <font color="#336600">���ޥ��ʥ�</font> 
                  </center>
                </td>
                <td class="text9pt" width="427"> 
                    <font color="#336600">�ץ�ե�����</font> 
                </td>
              </tr>
            </table>
            <br>
<!---->
<table width="538" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="150" class="text9pt"> 
      <div align="center"><?php echo $melonpai_nic ?></div>
    </td>
    <td class="text9pt" width="427"> 
      <span class="text01"><?php echo $melonpai_pro ?>
      </span>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="text9pt">
      <hr size="1">
    </td>
  </tr>
</table>
<!---->
            <br>
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                <td> 
                  <p class="text01"><font color="#99ccff"><span class="text01"><font color="#FF9900">��</font></span></font><font color="#336600"><b><font color="#000000">�ɤΤ褦�ʥ��ޥ����ɤ�Ǥߤ��������ꥯ�����Ȥ򤪽񤭤���������</font></b></font> 
                </td>
              </tr>
            </table>
           <font color="#99ccff"><span class="text01"><br>
            </span></font><font size="2">�����ߤĤ�������ʥ��ޥ���õ����Ū�˰ʳ��Τ��䤤��碌�Ϥ���θ����������<br>
            ���� ���䤤��碌��<a href="http://www.melonpan.net/help/">�ȣţ̣Хڡ���</a>�ˤƤ��Ԥ����Ƥ���ޤ���<br></font>
            <br>
            <form name="form2" method="post" action="mitsukuroi_3.php">
              <table border="0" cellpadding="5" cellspacing="1" width="539" bgcolor="#FFFFFF">
                <tr> 
                  <td bgcolor="#fffdd5"> <font color="#336600"><br>
                    </font><font size="2">�������ȥ롧<br>
                    <input type="text" name="com_title" size="73">
                    </font><font size="2"><br>
                    <br>
                    ���ꥯ�����ȡ�</font><br>
                    <textarea value="<?echo $com_text ?>" name="com_text" cols="71" rows="14"></textarea>
                    <br>
                    <br>
                    <font size="2">�����ʤ��Υ᡼�륢�ɥ쥹��<br>
                    </font> 
                    <input type="text" name="send_mail_addr" size="54">
                    <font size="2" color="#990000"><br>
                    <br>
                    </font> 
                    <hr>
                    <input type="submit" name="TaiBot3" value="���Ρ�ǧ��">
                    <input type="button" name="Submit2" value="������󥻥롡" onClick="history.back()">
<input type="hidden" value="<? echo $melonpai_id ?> " name="melonpai_id">
<input type="hidden" value="<? echo trim($mail_addr); ?> " name="mail_addr">
<input type="hidden" value="<? echo trim($reader_id); ?>" name="reader_id">
<input type="hidden" value="<? echo trim($reader_pswd); ?>" name="reader_pswd">
                  </td>
                </tr>
              </table>
              <br>
              <br>
              <br>
            </form>
          </td>
        </tr>
      </table>
      <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">��</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">��</td>
          <td width="557" bgcolor="#90c84b">��</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php draw_footer( ".." ); ?>

      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
