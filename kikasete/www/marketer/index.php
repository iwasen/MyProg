<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/count.php");
include("$inc/enq_graph.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_disp.php");

$month = arrayGetValue($_GET, "month", date('m'));
$day = arrayGetValue($_GET, "day", date('j'));

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>�������ơ�net���ͥåȥꥵ����ASP�����ӥ�</title>
<meta name="keywords" content="�ͥåȥꥵ����,���󥿡��ͥå�Ĵ��,�ꥵ����,���󥿡��ͥåȥꥵ����,�ޡ����ƥ��󥰥ꥵ����,�Ծ�Ĵ��,���롼�ץ��󥿥ӥ塼,���󥱡���,������Ĵ��">
<meta name="description" content="����������Ȥˤ��ͥåȥꥵ���������ӥ���Ĵ���߷ס����󥱡��ȼ»ܡ�Ĵ����̥�ݡ�������Ĵ����̳��ԡ����롼�ץ��󥿥ӥ塼��ŹƬĴ���ʤ��͡���Ĵ����ˡ������ޤ�">
<meta name="robots" content="index,follow">
<link rel="home" href="http://www.kikasete.net" title="�ͥå�Ĵ�������ӥ��������ơ�net-Ĵ���߷פ����̥�ݡ������">
<link rel="index" href="http://www.kikasete.net/marketer/mk/kkstfrom/mk_map.php" title="�����ȥޥå�">
<link rel="copyright" href="http://www.xxxxxxx.co.jp" title="�������������ꥵ����">
<link rel="author" href="http://www.xxxxxxx.co.jp" title="�������������ꥵ����">
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="js/w_open.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/rollover.js"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

</head>
<body bgcolor="#ffffff" onLoad="MM_preloadImages('images/common/menu2_on.gif','images/common/menu3_on.gif','images/common/menu4_on.gif','images/common/menu5_on.gif','images/common/menu6_on.gif','images/common/menu7_on.gif','images/top/right_menu1_1_on.gif','images/top/right_menu1_2_on.gif','images/top/right_menu1_login_on.gif','images/top/left_menu1_on.gif','images/top/left_menu2_on.gif','images/top/left_menu3_on.gif','images/top/left_menu4_on.gif','images/top/left_menu5_on.gif','images/top/left_menu6_on.gif','images/top/left_menu7_on.gif','images/top/left_menu8_on.gif','images/top/left_menu9_on.gif','images/top/left_menu10_on.gif','images/top/left_menu0_on.gif','images/top/flashside_02_on.gif','images/top/flashside_01_on.gif')">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788"><table width="788" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" bgcolor="#002b68"><img src="images/common/spacer.gif" width="10" height="3"></td>
      </tr>
      <tr>
        <td width="232"><img src="images/common/logo.gif" alt="�ͥåȥꥵ���������ӥ�[�������ơ�net]Ĵ���߷פ����̥�ݡ������" width="232" height="57" /></td>
        <td width="556" align="right" valign="bottom">
		<!-- ��������إå��� -->		
		<table border="0" cellpadding="0" cellspacing="8" class="header_text">
            <tr>
              <td colspan="5" align="right"><h1 align="left">�ͥåȥꥵ����ASP�ʤ餭�����Ǝ�net�åͥåȥꥵ�������äȿȶ�ˡ���äȴ�ñ��</h1></td>
              </tr>
            <tr>
                <td width="128" align="right" class="footer_text"><img src="images/common/icon_que.gif" alt="�褯���뤴����" align="absmiddle"><a href="mk/kkstfrom/mk_faq.php"> �褯���뤴����</a></td>
              <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="28" /></td>
                <td width="149" align="right" class="footer_text">
<a href="mk/kkstfrom/mk_contact.php"><img src="images/common/icon_mail.gif" alt="�᡼��֥ͥåȥꥵ����(���󥿡��ͥå�Ĵ��)�ޡ����ƥ��󥰥ꥵ���������ӥ��Τ������ơ�net��" width="14" height="9" align="absmiddle" /> 
                �᡼��ǤΤ��䤤��碌</a></td>
              <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="28" /></td>
              <td width="276" align="right"><img src="images/top/tel.gif" alt="�����ڤˤ����ä������� 03-5472-7903"></td>
            </tr>
        </table>
		<!-- �����ޤǥإå��� -->
		</td>
      </tr>
      <tr>
        <td colspan="2">
		</td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="index.php"><img src="images/common/menu1_on.gif" alt="HOME" width="86" height="47" border="0"></a></td>
            <td><a href="mk/about/mk_about.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu2','','images/common/menu2_on.gif',1)"><img src="images/common/menu2_off.gif" alt="�������ơ�net �ˤĤ���" name="menu2" width="159" height="47" border="0"></a></td>
            <td><a href="mk/service/mk_service.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu3','','images/common/menu3_on.gif',1)"><img src="images/common/menu3_off.gif" alt="�����ӥ�MENU" name="menu3" width="122" height="47" border="0"></a></td>
            <td><a href="mk/regist/mk_regist.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu4','','images/common/menu4_on.gif',1)"><img src="images/common/menu4_off.gif" alt="��Ͽ" name="menu4" width="70" height="47" border="0"></a></td>
            <td><a href="mk/hint/hint.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu7','','images/common/menu7_on.gif',1)"><img src="images/common/menu7_off.gif" alt="�������ƥҥ��" name="menu7" width="108" height="47" border="0"></a></td>
            <td><a href="mk/survey/mk_survey.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu5','','images/common/menu5_on.gif',1)"><img src="images/common/menu5_off.gif" alt="�������ơ�net Ĵ��" name="menu5" width="140" height="47" border="0"></a></td>
            <td><a href="mk/kkstfrom/mk_kkstfrom.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu6','','images/common/menu6_on.gif',1)"><img src="images/common/menu6_off.gif" alt="��̳�ɤ��" name="menu6" width="103" height="47" border="0"></a></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="5"><img src="images/common/spacer.gif" width="10" height="5" /> </td>
        </tr>
        <tr>
          <td width="177" align="center" valign="top"><table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="top"><img src="images/top/menu_service.gif" alt="�����ӥ�MENU" width="158" height="25" /></td>
              </tr>
              <tr>
                <td><img src="images/common/spacer.gif" width="10" height="5"></td>
              </tr>
              <tr>
                <td align="center" valign="top"><table width="159" border="0" cellspacing="0" cellpadding="0">
                
                  <tr style="cursor: hand">
                    <td width="159" height="20" valign="top" style="background-image:url(images/top/left_menu0.gif);" id="leftmenu0" onClick="window.location.href='mk/service/mk_3_price.php'" onMouseOver="setBGIMG('leftmenu0','images/top/left_menu0_on.gif');" onMouseOut="setBGIMG('leftmenu0','images/top/left_menu0.gif')"><div style="margin:2px 5px 0px 17px;" class="footer_text"><a href="mk/service/mk_3_price.php">�����ӥ��������</a></div></td>
                  </tr>
                  <tr style="cursor: hand">
                    <td width="159" height="35" valign="top" style="background-image:url(images/top/left_menu2.gif);" id="leftmenu2" onClick="window.location.href='mk/service/mk_myenq.php'" onMouseOver="setBGIMG('leftmenu2','images/top/left_menu2_on.gif');" onMouseOut="setBGIMG('leftmenu2','images/top/left_menu2.gif')"><div style="margin:2px 5px 0px 5px;" class="footer_text">���䣳���ߤ���δʰץꥵ����</div></td>
                  </tr>
				  <tr style="cursor: hand">
                    <td width="159" height="36" valign="top" style="background-image:url(images/top/left_menu1.gif);" id="leftmenu1" onClick="window.location.href='mk/service/mk_proenq.php'" onMouseOver="setBGIMG('leftmenu1','images/top/left_menu1_on.gif');" onMouseOut="setBGIMG('leftmenu1','images/top/left_menu1.gif')"><div style="margin:2px 5px 0px 5px;" class="footer_text">����շ��ͥåȥꥵ����ASP</div></td>
                  </tr>
                  
                  <tr style="cursor: hand">
                    <td width="159" height="35" valign="top" style="background-image:url(images/top/left_menu3.gif);" id="leftmenu3" onClick="window.location.href='mk/service/mk_mymonitor.php'" onMouseOver="setBGIMG('leftmenu3','images/top/left_menu3_on.gif');" onMouseOut="setBGIMG('leftmenu3','images/top/left_menu3.gif')"><div style="margin:2px 5px 0px 5px;" class="footer_text">�ͥåȲ�ļ���������������������</div></td>
                  </tr>
                </table></td>
              </tr>
          </table>
		    <table width="159" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /></td>
  </tr>
    <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /></td>
  </tr>
<tr>
    <td align="center"><a href="mk/service/mk_enqview.php"><img src="images/logo_enqview_p.gif" alt="EnqView" width="180" height="67" border="0"></a></td>
  </tr>
    
<tr>
    <td align="center"><a href="http://guruin.jp/index.html" target="_blank"><img src="images/img_guruin.gif" alt="�����ꥵ���������" width="180" height="67" border="0"></a></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /><a href="http://www.tento-research.com/" target="_blank"><img src="images/img_tentoresearch.gif" alt="ŹƬ�ꥵ����.com" width="180" height="67" border="0"></a></td>
  </tr>
  <tr>
    <td align="center"><a href="http://factnews.xxxxxxx.co.jp/index.asp" target="_blank"><img src="images/img_jijitsu.gif" alt="���¿�ʹ" width="180" height="67" border="0"></a></td>
  </tr>
  <tr>
    <td align="center"><a href="http://kakakuflash.jp/" target="_blank"><img src="images/img_kakaku.gif" alt="���ʥե�å���" width="180" height="67" border="0"></a></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /></td>
  </tr>

  <tr>
    <td><img src="images/common/spacer.gif" width="1" height="10" /></td>
  </tr>
</table>
		  </td>
          <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" />
          <td width="390" align="center" valign="top">
<table width="370" border="0" cellpadding="0" cellspacing="0">

<tr> 
<td><img src="images/top/center_menu1.gif" alt="�ꥵ��������ߥ졼�����" width="370" height="26"></td>
</tr>
<tr> 
<td align="left"><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr>
  <td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><a href="mk/hint/hint.php"><img src="images/top/center_img01.gif" alt="�������ƥҥ��" border="0"></a></td>
    <td><a href="mk/hint/hint.php">�����������ǥ�����Ĵ���߷פ򥤥᡼���Ǥ��ޤ���<br>
   ��ʬ�ˤ��ä��ץ��򸫤Ĥ��뤳�Ȥ�����롢�ͥåȥꥵ������Ĵ���߷פ���Ω�ĥġ���Ǥ���</a></td>
  </tr>
</table> </td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
<td align="right" class="footer_text"><img src="images/common_my/bt_03.gif" width="12" height="11" align="absmiddle" hspace="5"><a href="mk/hint/hint.php">�٥��Ȥʥץ�󤬤ߤĤ���֤������ƥҥ�ȡ�</a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>

<tr> 
<td><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
<td><img src="images/top/center_menu3.gif" alt="�����Σ����ͥ��󥱡���" width="370" height="28"></td>
</tr>
<tr> 
<td align="left"><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr>
  <td><h6>�������Ǝ�net�Υ�˥������轵�ơ��ޤ��Ѥ��ƥ��󥱡��Ȥ�»ܡ�
  <BR>
����ԡ�����ԤΡֺ��פ���ܤǥͥåȤǥꥵ������</h6></td>
</tr>
<tr> 
<td> 
<table width="370" border="0"> <!-- add 2005.12.27 -->
<?
// �����ͥ��󥱡���
$sql = "SELECT fe_seq_no,fe_top_page,en_enquete_id,en_title"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=5 ORDER BY en_end_date LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$fno = $fetch->fe_seq_no;
	$top_page = $fetch->fe_top_page;
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
?>
<!-- <table width="370" border="0"> --> <!-- del 2005.12.27 -->
<tr> 
<td align="left" colspan="3" class="description">
<strong>��<?=$title?>��</strong></td>
</tr>
<tr> 
<td colspan="3"><img src="images/common/spacer.gif" width="5" height="5" border="0"></td>
</tr>
<?
	$scale = 120;
	$html = '<td width="140"align="right" nowrap class="description">%TEXT%</td>'
				. '<td width="160" align="left" class="description"><img src="images/gbar.gif" width="%WIDTH%" height="10" border="0"></td>'
				. '<td width="70" align="left" nowrap class="description">%COUNT%&nbsp;</td>';
	enq_graph_all($enquete_id, $top_page, $scale, $html, true);
} else {
	echo '<tr><td>�����󥱡��Ȥϸ��ߤ���ޤ���</td></tr>';
}
?>
</table></td>
</tr>
<tr> 
<td align="right"> 
<table border="0" cellspacing="0" cellpadding="3" class="header_text">
<?
if($fno) {
	echo("
<tr> 
<td><img src='images/common_my/bt_03.gif' width='12' height='11'></td>
<td class='footer_text'><a href='mk/survey/enq/mk_enqresult.php'>����̤�����̤η�̤򸫤�</a></td>
</tr>
	");
}
?>
<tr> 
<td><img src="images/common_my/bt_03.gif" width="12" height="11"></td>
                      <td class="footer_text"><a href="mk/survey/enq/mk_pastenq_1.php">���Σ����ͥ��󥱡��Ȥ򸫤�</a></td>
</tr>
<tr> 
<td><img src="images/common_my/bt_03.gif" width="12" height="11"></td>
                      <td class="footer_text"><a href="mk/survey/mk_survey.php">����¾�Τ������Ǝ�netĴ�٤򸫤�</a></td>
</tr>
</table></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
  <td><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
</table>
          <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /></td>
          <td width="218" align="center" valign="top"><table width="198" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><img src="images/top/right_menu1.gif" alt="MYPAGE" width="198" height="25" /></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#e5eaf0"><table width="180" border="0" cellpadding="0" cellspacing="0">
					<form method="post" action="mk_login.php">
                      <tr>
                      <td><img src="images/top/login_mail.gif" alt="MAIL" width="10" height="8" /></td>
                      <td><img src="images/common/spacer.gif" width="5" height="10"></td>
                      <td colspan="2"><input name="mail_addr" type="text" class="form_mail" id="userid2" /></td>
                    </tr>
                      <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="8" /></td>
                      <td><img src="images/common/spacer.gif" width="5" height="10"></td>
                      <td colspan="2" class="right_menu">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><img src="images/top/login_pass.gif" alt="PASS" width="28" height="8" /></td>
                      <td><img src="images/common/spacer.gif" width="5" height="10"></td>
                      <td align="left"><input name="password" type="password" class="form_pass" id="password" size="8" /></td>
                      <td align="right"> 
                        <input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('login','','images/top/right_menu1_login_on.gif',1)" src="images/top/right_menu1_login.gif" alt="LOGIN" name="login" width="55" height="24" border="0"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><img src="images/common/spacer.gif" width="5" height="5"></td>
                        <td colspan="2" class="right_menu"><img src="images/common/arrow.gif" width="3" height="5" /> 
                          <a href="mk/login/mk_pwrequest.php">�ѥ���ɤ�˺�줿���Ϥ�����</a></td>
                    </tr>
					</form>
                </table></td>
              </tr>
              <tr>
                <td bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#e5eaf0"><a href="mk/regist/mk_regist.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage','','images/top/right_menu1_1_on.gif',1)"><img src="images/top/right_menu1_1.gif" alt="MY�ڡ�����Ͽ" name="mypage" width="180" height="32" border="0"></a></td>
              </tr>
              <tr>
                <td align="right" bgcolor="#e5eaf0" class="right_menu"><img src="images/common/arrow.gif" width="3" height="5" /><a href="mk/regist/mk_regist.php"> 
                  MY �ڡ�����Ͽ�Ȥϡ�</a></td>
              </tr>
            </table>
              <p>&nbsp;</p>
              <table width="198" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="images/top/right_menu2.gif" alt="���ߤΥ�˥�����" width="198" height="25" /></td>
                </tr>
                <tr>
                  <td align="center"><img src="images/common/spacer.gif" width="10" height="2" /></td>
                </tr>
                <tr>
                  <td align="center" bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="10"></td>
                </tr>
                <tr>
                  <td align="center" bgcolor="#e5eaf0"><h2><?=number_format(count_monitor())?>��</h2>
<span class=footer_text>(��Ȳ�Ҥ�ޤ��</span>
</td>
                </tr>
                <tr>
                  <td align="center" bgcolor="#e5eaf0"><img src="images/common/spacer.gif" width="10" height="10"></td>
                </tr>
              </table>
              <p>&nbsp;</p>

<img src="images/top/right_menu3.gif" alt="�ͥåȲ�ļ�">

	              <table width="198" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="images/top/right_menu4.gif" alt="������٤꣱�����Ͳ�ļ�" width="198" height="24"></td>
                </tr>
                <tr>
                  <td>
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>�������Ǝ�net�Υꥵ�����ѡ��ȥʡ������ͥåȾ�ǥǥ������å���󤷤Ƥ��ޤ�������Ԥ���������ʹ���Ƥߤޤ��󤫡�</td>
  </tr>
  <tr>
    <td align="right"><a href="mk/survey/mk_mllogin.php"><img src="images/top/description.gif" alt="�ܺ٤Ϥ�����" width="78" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="20"><img src="images/common/arrow2.gif" width="10" height="10" hspace="3" align="absmiddle"><a href="mk/survey/mllogin/mk_js.php">�����㥹����ļ�</a></td>
  </tr>
  <tr>
    <td height="20"><img src="images/common/arrow2.gif" width="10" height="10" hspace="3" align="absmiddle"><a href="mk/survey/mllogin/mk_onew.php">�������ʤ����ļ�</a></td>
  </tr>
  <tr>
    <td height="20"><img src="images/common/arrow2.gif" width="10" height="10" hspace="3" align="absmiddle"><a href="mk/survey/mllogin/mk_cvs.php">������ӥˤʤ�Ǥ��ļ�</a></td>
  </tr>
  <tr>
    <td height="20"><img src="images/common/arrow2.gif" width="10" height="10" hspace="3" align="absmiddle"><a href="mk/survey/mllogin/mk_iy.php">�����ȡ��衼���ɡ���ļ�</a></td>
  </tr>
  <tr>
    <td align="right"><img src="images/common/spacer.gif" width="10" height="5" /></td>
  </tr>
  <tr>
     <td class="footer_text">���ꥵ�����ѡ��ȥʡ��ϡ��ꥵ�����ˤĤ��ƥȥ졼�˥󥰤�������¿�����ʹ�����Ȥ��Ǥ���1��󥯾�Υ�˥����Ǥ���</td>
  </tr>
					</table>
			 	 </td>
                </tr>
                <tr>
                  <td align="right"><img src="images/common/spacer.gif" width="10" height="10" /></td>
                </tr>
              </table>
		  
		          <p>&nbsp;</p>
		  </td>
        </tr>
        <tr>
          <td colspan="5" align="center" valign="top">&nbsp;</td>
        </tr>
      </table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(false); ?>


