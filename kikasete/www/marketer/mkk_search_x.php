<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/search.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_disp.php");

$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT ms_search_id,ms_title FROM t_my_search WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->ms_title);

	// ������˥���������
	$search = new search_class;
	$search->read_db($fetch->ms_search_id);
	$monitor_num = $search->get_monitor_num();

	$sql = "UPDATE t_my_search SET ms_monitor_num=$monitor_num WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
	db_exec($sql);
}
?>
<? marketer_header('�������˥������������', PG_NULL) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
      <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td bgcolor="#dbdbdb"> 
          <td width="18" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
          <td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
        </tr>
        <tr> 
          <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
          <td valign="top"> 
            <table width="578" border="0" cellpadding="0" cellspacing="0" align="center">
              <tr> 
                <td width="578"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mysearch/title_01.gif" alt="My������" width="123" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td>�� 
                  <? disp_marketer_name() ?>
                  ����ˤ���Ͽ���������Ƥ���My������������Υ�˥��������Τ餻���ޤ���</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"> 
                </td>
              </tr>
              <tr> 
                <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0"> 
                      <td colspan="3">����Ѥθ������Υ�˥������ϡ�����</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="10%" align="center"> 
                        <?=mb_convert_kana($search_no, 'N')?>
                      </td>
                      <td bgcolor="#FFFFFF" width="70%"> 
                        <?=$title?>
                      </td>
                      <td bgcolor="#FFFFFF" align="center" width="20%"> 
                        <?=$monitor_num?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td class="footer_text">�� �����ѹ��������ϡ������ȥ�򥯥�å����Ƥ���������</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="15"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mysearch/title_04.gif" alt="����ץ�" width="161" height="21"></td>
              </tr>
              <tr> 
                <td> 
                  <p>���󥱡��Ȥ��ֿ�Ψ��ʿ��Ū�ʣ�����Ȥ������</p>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td> 
                  <p>�� �������󥱡��� �������ͤ��ܰ¤Ǥ��ʣ�������ץ������<br>
                    <br>
                    �� ���̥��󥱡��� �������ͤ��ܰ¤Ǥ��ʣ���������ץ������<br>
                    <br>
                    �� �絬�ϥ��󥱡��� ���������ͤ��ܰ¤Ǥ��ʣ�����������ץ������<br>
                  </p>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td> 
                  <Table Border="0" CellSpacing="0" CellPadding="0" width="100%">
                    <Tr> 
                      <Td align="right" Style="font-weight:bold;">�������˥�������300�ͤ�Ķ�����顦����</Td>
                      <Td align="center"><a href="mkk_myenq.php"><img src="images/mkk_mysearch/my_bt.gif" border="0"></a></Td>
                    </Tr>
                  </Table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <Td Style=" font-weight:bold; color:#FFA500;">�������˥��������������ͤ�Ķ�����顢�ͣ����󥱡��Ȥǥ�˥����ˤ�����ʹ���Ƥߤޤ��󤫡�</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td bgcolor="#dadada">
      <table border=0 cellspacing=1 cellpadding=10 width="100%" class="nor_text">
        <tr bgcolor="#ffffff"> 
          <td>
				�������ơ�net �ͣ����󥱡��ȤǤϡ�����ޤǤΥ��󥱡��Ȥʤ顢�פä��������ˤ����»ܤǤ��ޤ���<br>
                  �������������ͳ���������������䥿���פ����ӡ�����ʸ�����������������Ǽ�ưŪ�˥��󥱡��Ȥ��������졢Web���󥱡��ȡ��᡼�륢�󥱡��ȤΤ����줫������Ǥ��ޤ����оݼԤιʤ���ߤ��ǽ�ʤΤǡ����ꤷ�Ƥ�����Υ�˥����ˡ������˥��󥱡��Ȥ�»ܤǤ��ޤ���<br>
                  <br>
                  �ޤ����ꥢ�륿���ླྀ�ס��ǡ�����������ɤ����ʤ��Σͣ��ڡ������餪���ʤ���Τǡ�������������ޤ��ʷ�̤ʤɤ򤹤����İ����Ƥ����������Ȥ��Ǥ��ޤ���<br>
                  <br>
                  �ͣ����󥱡��Ȥ����ꤹ���ǡ�ʬ����ʤ����Ȥʤɤ������ޤ����顢���Ҥ��ʤ�����°����������Ȥؤ�Ϣ������������°����������Ȥ����ä���ȥ��ݡ��Ȥ����Ƥ��������ޤ���<br>
                  <br>
                  ����¾�ˤ⡢���̥���ץ��ɬ�פȤ��륢�󥱡��Ȥ䡢��������Υ��󥱡��Ȥ��ꤿ�����ʤɤ���˾���������ޤ����顢��°����������ȤޤǤ�Ϣ����������ФȻפ��ޤ���
		  </td>
        </tr>
		</table>
				</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="center"><a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="�Ĥ���" name="Image19" width="108" height="31" border="0"></a></td>
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

<? marketer_footer() ?>
