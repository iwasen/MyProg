<?
/******************************************************
' System :�������ơ�net�ޡ��������ڡ���
' Content:���顼ɽ������
'******************************************************/

function error_msg($msg, $back=false, $hidden=false) {
?>

<table width="100%" border="0" cellspacing="0" cellpadding="15" bgcolor="FFFFFF">
  <tr> 

    <td>
      <form method="post" action="<?=$back?>">
        <Div Style=" font-weight:bold; color:#2b2b63;">���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���</Div>
        <Hr Size="1" Color="#cccccc">
        <br>
        <?=disp_msg($msg, '����', '<br><br>')?>
        <Hr Size="1" Color="#cccccc">
        <div align="center"> 
          <?
if ($back) {
?>
          <input type="submit" value="����롡">
          <?
} else {
?>
          <input type="button" value="����롡" onclick="history.back()">
          <?
}
?>
        </div>
        <? if ($hidden) disp_msg($hidden) ?>
      </form>
    </td>
  </tr>
  <tr> 
    <td> <img src="images/common/spacer.gif" width="1" height="5"> </td>
  <tr> 
</table>
<?
}
?>