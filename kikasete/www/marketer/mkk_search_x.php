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

	// 検索モニター数検索
	$search = new search_class;
	$search->read_db($fetch->ms_search_id);
	$monitor_num = $search->get_monitor_num();

	$sql = "UPDATE t_my_search SET ms_monitor_num=$monitor_num WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
	db_exec($sql);
}
?>
<? marketer_header('設定条件モニター数検索結果', PG_NULL) ?>

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
                <td><img src="images/mkk_mysearch/title_01.gif" alt="Myサーチ" width="123" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td>　 
                  <? disp_marketer_name() ?>
                  さんにご登録いただいているMyサーチ設定条件のモニター数をお知らせします。</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"> 
                </td>
              </tr>
              <tr> 
                <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0"> 
                      <td colspan="3">設定済の検索条件のモニター数は・・・</td>
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
                <td class="footer_text">※ 条件を変更される場合は、タイトルをクリックしてください。</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="15"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_mysearch/title_04.gif" alt="サンプル" width="161" height="21"></td>
              </tr>
              <tr> 
                <td> 
                  <p>アンケートの返信率を平均的な２５％とした場合</p>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td> 
                  <p>■ 定性アンケート ２００人が目安です（５０サンプル獲得）<br>
                    <br>
                    ■ 定量アンケート ６００人が目安です（１５０サンプル獲得）<br>
                    <br>
                    ■ 大規模アンケート ４０００人が目安です（１０００サンプル獲得）<br>
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
                      <Td align="right" Style="font-weight:bold;">設定条件モニター数が300人を超えたら・・・</Td>
                      <Td align="center"><a href="mkk_myenq.php"><img src="images/mkk_mysearch/my_bt.gif" border="0"></a></Td>
                    </Tr>
                  </Table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <Td Style=" font-weight:bold; color:#FFA500;">設定条件モニター数が３００人を超えたら、Ｍｙアンケートでモニターにいろいろ聞いてみませんか？</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td bgcolor="#dadada">
      <table border=0 cellspacing=1 cellpadding=10 width="100%" class="nor_text">
        <tr bgcolor="#ffffff"> 
          <td>
				きかせて・net Ｍｙアンケートでは、３問までのアンケートなら、思ったその日にすぐ実施できます。<br>
                  択回答方式、自由回答方式かの設問タイプを選び、質問文・選択肢を入れるだけで自動的にアンケートが作成され、Webアンケート・メールアンケートのいずれかが選択できます。対象者の絞り込みも可能なので、設定している条件のモニターに、すぐにアンケートを実施できます！<br>
                  <br>
                  また、リアルタイム集計・データダウンロードがあなたのＭｙページからおこなえるので、回答状況、大まかな結果などをすぐに把握していただくことができます。<br>
                  <br>
                  Ｍｙアンケートを設定する上で、分からないことなどございましたら、ぜひあなたの専属エージェントへご連絡ください。専属エージェントががっちりとサポートさせていただきます。<br>
                  <br>
                  その他にも、大量サンプルを必要とするアンケートや、大量設問のアンケートをやりたい！などご要望がございましたら、専属エージェントまでご連絡いただければと思います。
		  </td>
        </tr>
		</table>
				</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="center"><a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="閉じる" name="Image19" width="108" height="31" border="0"></a></td>
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
