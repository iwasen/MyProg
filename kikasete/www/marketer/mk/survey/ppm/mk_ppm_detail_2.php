<?
$top = '../../..';
$inc = "$top/inc";
include("$inc/mk_header.php");
include("../$inc/common.php");

// �ڡ���ID����
$page_id = $_GET["page_id"];

// �ڡ���ID���顼�����å�
if(!is_file("result_inc/ppm_" . $page_id . ".inc")){

  redirect("../mk_ppm.php");

} else {

marketer_header('�ݥ��ƥ��֡��ѡ����ץ���󡦥ޥå�', PG_SURVEY);

?>
<a name='top'></a>
<table width="788" border="0" cellpadding="0" cellspacing="0" bgcolor="FFFFFF">
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr> 
    <td align="center" class="footer_text"> <a href="../mk_ppm.php">�ݥ��ƥ��֡��ѡ����ץ���󡦥ޥåװ��������</a> 
    </td>
  </tr>
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="20" /></td>
  </tr>
  <tr> 
    <td valign="top" align="center"> 
      <?
	include("result_inc/ppm_" . $page_id . ".inc");				// PPM
?>
    </td>
  </tr>
  <tr> 
    <td align="center">
	  <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center"><a href="mailto:k-info@kikasete.net"><img src="../../../images/survey/ppm/ppm_mail_a.gif" alt="" width="197" height="81" border="0" onmouseover="this.src='../../../images/survey/ppm/ppm_mail_b.gif'" onmouseout="this.src='../../../images/survey/ppm/ppm_mail_a.gif'"></a></td>
          </tr><tr>
          <td class="footer_text">���֥ݥ��ƥ��֡��ѡ����ץ���󡦥ޥåספˤĤ��Ƥ�<a href="../mk_ppm.php">������</a>�� 
            ��</td>
        </tr>
			<tr>
         <td class="footer_text">���֥ꥵ�����ѡ��ȥʡ��פˤĤ��Ƥ�<a href="../../kkstfrom/mk_faq.php#3">������</a>��
            </td>
        </tr>

      </table>

    </td>
  </tr>
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
  </tr>
  <tr> 
    <td align="right"><a href="#top"><img src="../../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a><img src="../../../images/common/spacer.gif" width="50" height="1"></td>
  </tr>
  <tr> 
    <td><img src="../../../images/common/spacer.gif" width="10" height="30"></td>
  </tr>
</table>


<? marketer_footer(NOBLUE);
}

 ?>
