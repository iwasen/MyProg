<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
?>
<? marketer_header('��̳�ɤ���β��Τ��Τ餻', PG_CENTER) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          
<td width="170" valign="top" bgcolor="#e5eaf0">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr> 
<td align="right" bgcolor="#FFFFFF"><img src="../../images/mk_kkstfrom/img_01.gif" alt="��̳�ɤ��" width="93" height="18"></td>
</tr>
<tr> 
<td><img src="../../images/mk_kkstfrom/bt_01_ov.gif" alt="��̳�ɤ���Τ��Τ餻" name="Image80" width="170" height="31" border="0"></td>
</tr>
<tr> 
                <td><a href="../regist/mk_privacy.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image81','','../../images/mk_kkstfrom/bt_02_ov.gif',1)"><img src="../../images/mk_kkstfrom/bt_02.gif" alt="Privacy Policy" name="Image81" width="170" height="30" border="0"></a></td>
</tr>
<tr>
                <td><a href="mk_rules.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image82','','../../images/mk_kkstfrom/bt_03_ov.gif',1)"><img src="../../images/mk_kkstfrom/bt_03.gif" alt="����" name="Image82" width="170" height="30" border="0"></a></td>
</tr>
<tr>
                <td><a href="mk_faq.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image83','','../../images/mk_kkstfrom/bt_04_ov.gif',1)"><img src="../../images/mk_kkstfrom/bt_04.gif" alt="FAQ" name="Image83" width="170" height="31" border="0"></a></td>
</tr>
</table>
</td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          
<td valign="top"> 
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡<a href="mk_kkstfrom.php">��̳�ɤ��</a>���䡡<a href="mk_news_1.php">��̳�ɤ���Τ��Τ餻</a>���䡡��̳�ɤ���β��Τ��Τ餻</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td width="578"><img src="../../images/mk_kkstfrom/title_01.gif" alt="��̳�ɤ��" width="85" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td class="right_menu_title">�������ơ�net ����β��Τ��Τ餻�Ǥ���</td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20" /></td>
              </tr>
              <tr> 
                <td class="footer_text"><img src="../../images/mk_kkstfrom/t_mk_newsd.gif" width="553" height="24"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="../../images/mk_kkstfrom/c_newsd.gif" width="125" height="15"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td> 
<table width="100%" border="0">
<tr> 
<td class="footer_text"> 
<?
// ���Τ��Τ餻
if ($no > 0) {
?>
<a href="mk_pastnews.php?no=<?=max($no - 10, 0)?>">&lt;&lt; ���Τ��Τ餻</a> 
<?
}
?>
</td>
<td align="right" class="footer_text"> 
<?
// ���Τ��Τ餻
if ($nrow > 10) {
?>
<a href="mk_pastnews.php?no=<?=$no + 10?>">���Τ��Τ餻 &gt;&gt;</a> 
<?
}
?>
</td>
</tr>
</table>
                </td>
              </tr>
              <tr> 
                <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0"> 
                      <td width="20%" align="center">����</td>
                      <td width="60%" align="center">�����ȥ�</td>
                    </tr>
<?
// ��̳�ɤ���Τ��Τ餻
$sql = 'SELECT cs_date,cs_title'
		. ' FROM t_center_msg'
		. " WHERE cs_marketer_top AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) ORDER BY cs_date DESC,cs_seq_no DESC limit 11 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < 10; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title = htmlspecialchars($fetch->cs_title);
		$date = format_date($fetch->cs_date);
	} else {
		$title = '<br>';
		$date = '<br>';
	}
?>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">
                        <?=$date?>
                      </td>
                      <td bgcolor="#FFFFFF" width="60%">
                        <a href="mk_newsd.php?no=<?=$no + $i?>"><?=$title?></a>
                      </td>
                    </tr>
<?
}
?>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>

              <tr> 
                <td align="right"><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
</td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

