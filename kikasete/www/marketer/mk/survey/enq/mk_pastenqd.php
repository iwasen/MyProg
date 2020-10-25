<?
$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enq_graph.php");
$inc = "$top/inc";
include("$inc/mk_header.php");

// ID指定
if (is_numeric($id)) {
	$sql = "SELECT fe_enquete_id FROM t_free_enquete WHERE fe_seq_no=$id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$enquete_id = $fetch->fe_enquete_id;

		$sql = "SELECT en_enquete_id"
				. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
				. " WHERE en_status=7"
				. " ORDER BY en_enquete_id DESC";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			if ($fetch->en_enquete_id == $enquete_id) {
				$no = $i;
				break;
			}
		}
	}
}

if (!isset($no))
	redirect('pastenq.php');

// １万人アンケート
$sql = "SELECT en_enquete_id,en_title,en_description"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=7"
		. " ORDER BY en_enquete_id DESC"
		. " LIMIT 2 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
	$description = nl2br(htmlspecialchars($fetch->en_description));
} else
	redirect('mk_pastenq.php');
?>
<? marketer_header('過去に実施したアンケート', PG_SURVEY) ?>
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
<td align="right" bgcolor="#FFFFFF"><img src="../../../images/mk_survey/title01.gif" alt="きかせて・net 調べ" width="149" height="18"></td>
</tr>
<tr> 
                <td><a href="../tw/mk_kkstsurvey.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image48','','../../../images/mk_survey/bt01_ov.gif',1)"><img src="../../../images/mk_survey/bt01.gif" alt="トレンドウオッチ" name="Image48" width="170" height="30" border="0"></a></td>
</tr>
<tr> 
                <td><a href="../mk_ppm.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image49','','../../../images/mk_survey/bt02_ov.gif',1)"><img src="../../../images/mk_survey/bt02.gif" alt="ポジティブ・パーセプション・マップ " name="Image49" width="170" height="35" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../../images/mk_survey/bt03_ov.gif" alt="きかせて！1万人アンケート" width="170" height="30"></td>
</tr>
<tr> 
                <td><a href="../mk_mllogin.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image51','','../../../images/mk_survey/bt04_ov.gif',1)"><img src="../../../images/mk_survey/bt04.gif" alt="おしゃべり100人会議室" name="Image51" width="170" height="32" border="0"></a></td>
</tr>
<tr> 
<td align="right" class="footer_text"><img src="../../../images/common/spacer.gif" width="10" height="5" /></td>
</tr>
<tr> 
                <td align="right" class="footer_text"><a href="../mk_survey_price.php">データ販売の料金表はこちら＞＞</a></td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../../images/common/spacer.gif" width="1" height="10" /> 
<td width="12" valign="top"><img src="../../../images/common/spacer.gif" width="12" height="10"> 
<td valign="top"> 
            <table width="578" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../../index.php">HOME</a>　＞　<a href="../mk_survey.php">きかせて･net調べ</a>　＞　<a href="../mk_aboutenq.php">きかせて！1万人アンケート</a>　＞　<a href="mk_pastenq_1.php">過去に実施したアンケート一覧</a>　＞　アンケート集計結果</td>
</tr>
<tr>
<td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td width="578"><img src="../../../images/mk_survey/title02.gif" alt="きかせて・net 調べ" width="142" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../../images/common/spacer.gif" width="10" height="20" /></td>
              </tr>
              <tr> 
                <td><img src="../../../images/mk_survey/title08.gif" alt="きかせて！1万人アンケート" width="578" height="24"></td>
              </tr>
              <tr> 
                <td><img src="../../../images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td>

<table width="578" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td rowspan="2"><img src="../../../images/enq_kako.gif" width="100" height="80" border="0"></td>
          <td rowspan="2"><img src="../../../images/common/spacer.gif" width="10" height="1" border="0"></td>
                            <td width="570"> 
                              <p>過去に実施した「ちょっと気になる、みんなの気持ち」アンケートの集計結果です。<br>
                                意外な発見はありましたか？</p>
                              <p><a href="mk_pastenq_1.php">&gt;&gt; 
                                アンケートリストに戻る</a></p>
                            </td>
        </tr>
        <tr> 
                            <td align="right"> 〔 <a href="mk_pastenq_1.php">過去に実施したアンケート一覧</a> 
                              〕 </td>
        </tr>
      </table>
      <br>
<table width="553" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr> 
    <td bgcolor="#dadada"> 
      <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
        <tr> 
          <td bgcolor="#FFFFFF" width="100%" align="center">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>
                  <?=$title?>
                  </td>
              </tr>
            </table>
            <br>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>
                  <?=$description?>
                  </td>
              </tr>
            </table>

        

    </td>
  </tr>
</table>

		  
		  </tr>
      </table>
      <br>
      <br>
<?
$sql = "SELECT eq_question_no,eq_question_text FROM t_enq_question WHERE eq_enquete_id=$enquete_id ORDER BY eq_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_no = $fetch->eq_question_no;
	if ($fetch->eq_question_text != '') {
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <hr color="#999999">
            Ｑ
            <?=$question_no?>
            ．
            <?=nl2br(htmlspecialchars($fetch->eq_question_text))?>
             
            <hr color="#999999">
          </td>
        </tr>
      </table>
      <?
	}
?>
			<table width="578" cellspacing="6" cellpadding="0" border="0">
				<tr>
					<td width="100%" colspan="2"><p><img src="../../../images/survey/enq/all.gif" width="150" height="20" border="0"></p></td>
				</tr>
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/common/spacer.gif" width="5" height="5" border="0"></td>
				</tr>
<?
	$scale = 360;
	$html = '<td width="150">●　%TEXT%</td>'
				. '<td width="428"><img src="../../../images/survey/enq/gbar4.gif" width="%WIDTH%" height="10"> %COUNT% </td>';
	enq_graph_all($enquete_id, $question_no, $scale, $html, true);
?>
			</table>
			<br>
			<table width="578" cellspacing="6" cellpadding="0" border="0">
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/survey/enq/woman.gif" width="150" height="20" border="0"></td>
				</tr>
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/common/spacer.gif" width="5" height="5" border="0"></td>
				</tr>
<?
	enq_graph_woman($enquete_id, $question_no, $scale, $html, true);
?>
			</table>
			<br>
			<table width="578" cellspacing="6" cellpadding="0" border="0">
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/survey/enq/man.gif" width="150" height="20" border="0"></td>
				</tr>
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/common/spacer.gif" width="5" height="5" border="0"></td>
				</tr>
<?
	enq_graph_man($enquete_id, $question_no, $scale, $html, true);
?>
			</table>
			<br>
			<table width="578" cellspacing="6" cellpadding="0" border="0">
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/survey/enq/single.gif" width="150" height="20" border="0"></td>
				</tr>
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/common/spacer.gif" width="5" height="5" border="0"></td>
				</tr>
<?
	enq_graph_mikon($enquete_id, $question_no, $scale, $html, true);
?>
			</table>
			<br>
			<table width="578" cellspacing="6" cellpadding="0" border="0">
				<tr>
					<td width="100%" colspan="3"><img src="../../../images/survey/enq/married.gif" width="150" height="20" border="0"></td>
				</tr>
				<tr>
					<td width="100%" colspan="2"><img src="../../../images/common/spacer.gif" width="5" height="5" border="0"></td>
				</tr>
<?
	enq_graph_kikon($enquete_id, $question_no, $scale, $html, true);
?>
			</table>
			<br>
<?
}
?>
    </td>
  </tr>
</table>
<br>
			<table border="0" cellpadding="0" cellspacing="2" width="100%">
				<tr>
					<td width="33%" align="right">
<?
if ($no > 0) {
?>
						<a href="mk_pastenqd.php?no=<?=$no - 1?>">＜前のアンケート</a>
<?
}
?>
					</td>
					<td width="33%" align="center"><a href="mk_pastenq_1.php?no=<?=$no?>">一覧</a></td>
					<td width="34%" align="left">
<?
if ($no >= 0) {
?>
						<a href="mk_pastenqd.php?no=<?=$no + 1?>">次のアンケート＞</a>
<?
}
?>
					</td>
				</tr>
			</table>


                </td>
              </tr>
<tr> 
<td><img src="../../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
  <td>
  <div style="background-color:#FFFFFF; padding:10px; margin:20px; border-color:#e5eaf0; border-style:solid; border-width:5px;">
<img src="../../../images/top/center_img02.gif" alt="オープンデータ" align="right" hspace="5" />※　各オープンデータのＴＶ・新聞・雑誌・HP等への掲載や、営業資料等でのご利用に関しましては、
メール又はお電話にてお問い合わせください。調査結果データ販売も行っております。
料金につきましては<a href="../mk_survey_price.php">こちら</a>をご覧ください。
	
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
