<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_mye_check.php");
?>
<? marketer_header('送信者プロファイル', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top">≪送信者プロファイル≫</td>
	</tr>
</table>
      <table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tr> 
          <td rowspan="5" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
          <td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
        </tr>
        <tr> 
          <td> 
            <table width="768" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="500" rowspan="2"> アンケートを発信したモニターのプロファイルです。<br>
                  また、データをダウンロードすることもできます。 </td>
                <td width="200" align="right"> 
                  <form method="post" action="mkk_enq_dl1.php">
                    <input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
                    <input type="hidden" name="type" value="send">
                    <input type="image" src="images/mkk_bt/dl_csv.gif" alt="CSVのダウンロード">
                  </form>
                </td>
              </tr>
              <tr> 
                <td width="200" align="right"> <a href="javascript:history.back()">アンケート詳細表示に戻る</a> 
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
        </tr>
        <tr> 
          <td> 
            <table width="99%" border="1" cellspacing="0" cellpadding="3" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
              <tr bgcolor="#eeeeee"> 
                <td nowrap class="footer_text">番号</td>
                <td nowrap class="footer_text">送信日</td>
                <td nowrap class="footer_text">性別</td>
                <td nowrap class="footer_text">年齢</td>
                <td nowrap class="footer_text">未既婚</td>
                <td nowrap class="footer_text">居住地域</td>
                <td nowrap class="footer_text">職業</td>
                <td nowrap class="footer_text">業種</td>
                <td nowrap class="footer_text">職種</td>
                <td nowrap class="footer_text">勤務先地域</td>
              </tr>
              <?
$sql = "SELECT el_send_date,mn_sex,mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
		. " FROM (((((t_enquete_list JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id)"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area)"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area)"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd)"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd)"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY el_monitor_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
              <tr bgcolor="#ffffff"> 
                <td nowrap class="footer_text"> 
                  <?=$i + 1?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=format_date($fetch->el_send_date)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=decode_sex($fetch->mn_sex)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=$fetch->mn_age?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=decode_mikikon($fetch->mn_mikikon)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=htmlspecialchars($fetch->jitaku_area_name)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=htmlspecialchars($fetch->sg_shokugyou_name)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=htmlspecialchars($fetch->gs_gyoushu_name)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=htmlspecialchars($fetch->ss_shokushu_name)?>
                </td>
                <td nowrap class="footer_text"> 
                  <?=htmlspecialchars($fetch->kinmu_area_name)?>
                </td>
              </tr>
              <?
}
?>
            </table>
          </td>
        </tr>
        <tr>
          <td align="right"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
        </tr>

      </table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
