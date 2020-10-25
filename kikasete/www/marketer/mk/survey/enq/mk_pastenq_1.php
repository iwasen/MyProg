<?
$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
?>

<?
$enq = array();

// 1���ͥ��󥱡���ɽ��
if ($no == '') $no = 0;
$sub1 = "SELECT en_enquete_id"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=7"
		. " ORDER BY en_enquete_id DESC"
		. " LIMIT 11 OFFSET $no";
$sql = "SELECT en_enquete_id,en_title,en_start_date,en_end_date,em_sum"
		. " FROM t_enquete LEFT JOIN t_enquete_sum ON em_enquete_id=en_enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0"
		. " WHERE en_enquete_id IN ($sub1)"
		. " ORDER BY en_enquete_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < 10; $i++) 
{
	if ($i < $nrow) 
	{
		$fetch = pg_fetch_object($result, $i);
		$enq[$i]["title"] = '<a href="mk_pastenqd.php?no=' . ($no + $i) . '">' . htmlspecialchars($fetch->en_title) . '</a>';
		$enq[$i]["date"] = '<nobr>' . format_date($fetch->en_start_date) . '��' . format_date($fetch->en_end_date) . '</nobr>';
		$enq[$i]["count"] = number_format($fetch->em_sum) . '��';
	} else {
		$enq[$i]["title"] = '<br>';
		$enq[$i]["date"] = '<br>';
		$enq[$i]["count"] = '<br>';
	}
}
?>


<? marketer_header('�����ӥ���˥塼 | �������ơ�net', PG_SURVEY) ?>


<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="../../../images/common/spacer.gif" width="10" height="15" /></td>
<td bgcolor="#dbdbdb"> 
<td valign="top"> 
<td valign="top">&nbsp;</td>
<td valign="top"><img src="../../../images/common/spacer.gif" width="20" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr> 
<td align="right" bgcolor="#FFFFFF"><img src="../../../images/mk_survey/title01.gif" alt="�������ơ�net Ĵ��" width="149" height="18"></td>
</tr>
<tr> 
                <td><a href="../tw/mk_kkstsurvey.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image48','','../../../images/mk_survey/bt01_ov.gif',1)"><img src="../../../images/mk_survey/bt01.gif" alt="�ȥ��ɥ����å�" name="Image48" width="170" height="30" border="0"></a></td>
</tr>
<tr> 
                <td><a href="../mk_ppm.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image49','','../../../images/mk_survey/bt02_ov.gif',1)"><img src="../../../images/mk_survey/bt02.gif" alt="�ݥ��ƥ��֡��ѡ����ץ���󡦥ޥå� " name="Image49" width="170" height="35" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../../images/mk_survey/bt03_ov.gif" alt="�������ơ�1���ͥ��󥱡���" width="170" height="30"></td>
</tr>
<tr> 
                <td><a href="../mk_mllogin.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image51','','../../../images/mk_survey/bt04_ov.gif',1)"><img src="../../../images/mk_survey/bt04.gif" alt="������٤�100�Ͳ�ļ�" name="Image51" width="170" height="32" border="0"></a></td>
</tr>
<tr> 
<td align="right" class="footer_text"><img src="../../../images/common/spacer.gif" width="10" height="5" /></td>
</tr>
<tr> 
                <td align="right" class="footer_text"><a href="../mk_survey_price.php">�ǡ������������ɽ�Ϥ�������</a></td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../../images/common/spacer.gif" width="1" height="10" /> 
<td width="12" valign="top"><img src="../../../images/common/spacer.gif" width="12" height="10"> 
<td valign="top"> 
<table width="578" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../../index.php">HOME</a>���䡡<a href="../mk_survey.php">�������Ǝ�netĴ��</a>���䡡<a href="../mk_aboutenq.php">�������ơ�1���ͥ��󥱡���</a>���䡡���˼»ܤ������󥱡��Ȱ���</td>
</tr>
<tr>
<td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td width="578"><img src="../../../images/mk_survey/title02.gif" alt="�������ơ�net Ĵ��" width="142" height="28"></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td><img src="../../../images/mk_survey/title08.gif" alt="�������ơ�1���ͥ��󥱡���" width="578" height="24"></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<p>����ޤǤ˼»ܤ����֤������ơ������ͥ��󥱡��ȡפΰ����Ǥ��� <br>
���󥱡��ȤǤϡ����˰��٥ơ��ޤ��Ѥ�����˥����κǿ�ư���򥦥��å����Ƥ��ޤ������η�̤⤼�Ҥ����������� </p>
</td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td><img src="../../../images/mk_survey/title09.gif" alt="���˼»ܤ������󥱡��ȥꥹ��" width="450" height="15"></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<table width="100%" border="0">
<tr> 
<td class="footer_text"> 

<?
if($no>0) echo("<a href='mk_pastenq_1.php?no=".max($no-10,0)."'><< ����10��</a> ");
else echo("&nbsp;");
?>
</td>
<td align="right" class="footer_text"> 
<?
if($nrow>10) echo("<a href='mk_pastenq_1.php?no=".($no+10)."'>����10�� >></a> ");
else echo("&nbsp;");
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="53%">�����ȥ�</td>
<td align="center" width="27%">�»ܴ���</td>
<td align="center" width="20%">���ÿͿ�</td>
</tr>
<? 
foreach($enq as $n => $list)
{
	echo("
<tr bgcolor='#FFFFFF'>
<td width='53%'><a href='#'>".$list['title']."</a> </td>
<td width='27%' align='center'>".$list['date'] ."</td>
<td width='20%' align='center'>".$list['count'] ."</td>
</tr>
	");
}
?>

</table>
</td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
  <td>
  <div style="background-color:#FFFFFF; padding:10px; margin:20px; border-color:#e5eaf0; border-style:solid; border-width:5px;">
<img src="../../../images/top/center_img02.gif" alt="�����ץ�ǡ���" align="right" hspace="5" />�����ƥ����ץ�ǡ����Σԣ֡���ʹ�����HP���ؤηǺܤ䡢�ĶȻ������ǤΤ����Ѥ˴ؤ��ޤ��Ƥϡ�
�᡼�����Ϥ����äˤƤ��䤤��碌����������Ĵ����̥ǡ��������ԤäƤ���ޤ���
����ˤĤ��ޤ��Ƥ�<a href="../mk_survey_price.php">������</a>��������������
	
  </div></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="../../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
<td valign="top">&nbsp;</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>
