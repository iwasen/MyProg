<?
include("../sub/head.inc");
include("inc/database.php");

/* --------------- 説明文（新規） --------------------- */
$cmnt_shinki = 
 "<tr><td valign=\"top\" class=\"oubo\">１．</td><td class=\"oubo\">期間中に「めろんぱん」から、あなたのお気に入りのメルマガ３誌を探して購読手続きをします。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">２．</td><td class=\"oubo\">メルマガを講読しているメールアドレスを記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">３．</td><td class=\"oubo\">購読手続き後すぐに届く購読確認メールの一番下に「プレゼント応募券」がついています。その中の「応募コード（２０桁）」をそのままコピーして、このページの「応募券貼り付け欄」に３誌貼り付けてください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">４．</td><td class=\"oubo\">「今日のひとりごと」の欄には、めろんぱんトップページ左上にある「今日のひとりごと」をそのまま記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">５．</td><td class=\"oubo\">このページ一番下の「応募する」ボタンを押して、応募が完了です。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">６．</td><td class=\"oubo\">当選者の発表は、当選者の方へ直接メールにて行わせていただきます。</td></tr>";

/* --------------- 説明文（既存・キーワード有） --------------------- */
$cmnt_kidoku_ari = 
 "<tr><td valign=\"top\" class=\"oubo\">１．</td><td class=\"oubo\">期間中に「めろんぱん」から届く各メールマガジンのフッター（一番下）をご覧ください。そこに「プレゼント応募券」がついているものがあります。それぞれの「プレゼント応募券」には、３種類の異なる「キーワード」が入っていますので、異なるキーワードの入った応募券を３枚集めてください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">２．</td><td class=\"oubo\">集めた「プレゼント応募券」の中の「応募コード（２０桁）」をそのままコピーして、このページの「応募コード貼り付け欄」に３枚分貼り付けてください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">３．</td><td class=\"oubo\">メールアドレスの欄には、その３誌を購読しているメールアドレスを記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">４．</td><td class=\"oubo\">「今日のひとりごと」の欄には、めろんぱんトップページ左上にある「今日のひとりごと」をそのまま記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">５．</td><td class=\"oubo\">このページ一番下の「応募する」ボタンを押して、応募が完了です。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">６．</td><td class=\"oubo\">当選者の発表は、当選者の方へ直接メールにて行わせていただきます。</td></tr>";

/* --------------- 説明文（既存・キーワード無） --------------------- */
$cmnt_kidoku_nashi = 
 "<tr><td valign=\"top\" class=\"oubo\">１．</td><td class=\"oubo\">期間中に「めろんぱん」から届く各メールマガジンのフッター（一番下）をご覧ください。そこに「プレゼント応募券」がついているものがあります。その「プレゼント応募券」を３枚集めていただきます。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">２．</td><td class=\"oubo\">「プレゼント応募券」の中にある「応募コード（２０桁）」をそのままコピーして、このページの「応募コード貼り付け欄」に３枚分貼り付けてください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">３．</td><td class=\"oubo\">メールアドレスの欄には、その３誌を購読しているメールアドレスを記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">４．</td><td class=\"oubo\">「今日のひとりごと」の欄には、めろんぱんトップページ左上にある「今日のひとりごと」をそのまま記入してください。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">５．</td><td class=\"oubo\">このページ一番下の「応募する」ボタンを押して、応募が完了です。</td></tr>"
."<tr><td valign=\"top\" class=\"oubo\">６．</td><td class=\"oubo\">当選者の発表は、当選者の方へ直接メールにて行わせていただきます。</td></tr>";

/* --------------------------------------------- */
/* 　応募方法取得
/* --------------------------------------------- */
function oubo_cmnt($present_type, $keyword_flag) {
	global $cmnt_shinki, $cmnt_kidoku_ari, $cmnt_kidoku_nashi;

	if ($present_type == "A"){ 		//新規
		return $cmnt_shinki;
	} else {
		if ($keyword_flag == "1"){	//キーワードあり
			return $cmnt_kidoku_ari;
		} else {
			return $cmnt_kidoku_nashi;
		}
	}
}
/* --------------- for test --------------------- 
include("inc/present.inc");
$present_id = "00001";

$keyword = "かっぱえびせん";
$mag_id = "000135";
$vol_no = 3;
$code1 = encode_present($present_id, $mag_id, $keyword, $vol_no);
$mag_id = "000754";
$keyword = "サッポロポテト";
$vol_no = 4;
$code2 = encode_present($present_id, $mag_id, $keyword, $vol_no);
$mag_id = "000422";
$keyword = "ばかうけ";
$vol_no = 5;
$code3 = encode_present($present_id, $mag_id, $keyword, $vol_no);

$mail_adr="oracle@hmg.local.net";
$hitorigoto="今日のひとりごと";
$oubo1=$code1;
$oubo2=$code2;
$oubo3=$code3;
 ------------------------------------------ */
$sql = "SELECT PD_present_type"
		. ", PD_present_id"
		. ", PD_present_name"
		. ", PD_explain"
		. ", PD_presenter"
		. ", PD_link_url"
		. ", PD_picture"
		. ", PD_keyword_flag"
		. ", PD_keyword1"
		. ", PD_keyword2"
		. ", PD_keyword3"
		. ", TO_CHAR(PD_campaign_start, 'YYYY/MM/DD') AS campaign_s"
		. ", TO_CHAR(PD_campaign_end,   'YYYY/MM/DD') AS campaign_e"
		. ", TO_CHAR(PD_insert_start,   'YYYY/MM/DD') AS insert_s"
		. ", TO_CHAR(PD_insert_end,     'YYYY/MM/DD') AS insert_e"
		. ", TO_CHAR(PD_lottery_date,   'YYYY/MM/DD') AS lottery"
		. ", PD_prize_1"
		. ", PD_prize_2"
		. ", PD_prize_3"
		. ", PD_prize_name1"
		. ", PD_prize_name2"
		. ", PD_prize_name3"
		. ", PD_status"
		. ", PD_page_title"
		. " FROM M_PRESENT_DATA ";
if ( trim($opt_present_id) == "" ){
	$sql.=" WHERE to_char(now(), 'YYYYMMDD') between to_char(PD_campaign_start, 'YYYYMMDD') and to_char(PD_campaign_end, 'YYYYMMDD') "
		. " ORDER BY PD_campaign_end";
}else{
	$sql.=" WHERE PD_present_id = '$opt_present_id' ";
}
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$present_type = $fetch->pd_present_type;
	$present_id = $fetch->pd_present_id;
	$present_name = $fetch->pd_present_name;
	$explain = $fetch->pd_explain;
	$presenter = $fetch->pd_presenter;
	$link_url = $fetch->pd_link_url;
	$picture = $fetch->pd_picture;
	$keyword_flag = $fetch->pd_keyword_flag;
	$keyword1 = $fetch->pd_keyword1;
	$keyword2 = $fetch->pd_keyword2;
	$keyword3 = $fetch->pd_keyword3;
	$campaign_s = $fetch->campaign_s;
	$campaign_e = $fetch->campaign_e;
	$insert_s = $fetch->insert_s;
	$insert_e = $fetch->insert_e;
	$lottery = $fetch->lottery;
	$prize_1 = $fetch->pd_prize_1;
	$prize_2 = $fetch->pd_prize_2;
	$prize_3 = $fetch->pd_prize_3;
	$prize_name1 = $fetch->pd_prize_name1;
	$prize_name2 = $fetch->pd_prize_name2;
	$prize_name3 = $fetch->pd_prize_name3;
	$status = $fetch->pd_status;
	$page_title = $fetch->pd_page_title;
}else{
	$mail_adr="";
	$print_msg = "■ただいまの期間プレゼントの応募受付はおこなっておりません。";
	$check_ok=1;
    include("../melonpa/mag-present2.html");
	exit;
}

/******************************************************
' Content:プレゼント応募ページ
'******************************************************/
?>

<html>
<head>
<title>めろんぱん || よりすぐりメルマガサイト</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
.present_name {  font-size: 20pt; line-height: 125%; font-weight: bold; color: #FF6600; background: #FFFFFF}
.page_title {  font-size: 16pt; line-height: 125%; font-weight: bold; color: #FF6600; background: #FFFFFF}
.explain {  font-size: 12pt; line-height: 125%; color: #333333; background: #FFFFFF}
.company {  font-size: 14pt; line-height: 125%; font-weight: bold}
.oubo {  font-size: 10pt; line-height: 125%; color: #333333}
.koumoku {  font-size: 10pt; line-height: 125%; color: #336600; background: #e2f0c9; font-weight: bold}
-->
</style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>　</td>
  </tr>
  <tr>
    <td>
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td>
            <h1><img src="img/obi.gif" width="620" height="25" alt="めろんぱん読者プレゼント" vspace="5"></h1>
          </td>
        </tr>
        <tr> 
          <td> 
		  <!-- 商品名-->
            <p class="page_title" align="center">
              <?php echo $page_title; ?>
            </p>
            <!--説明文と絵-->
            <table border=0 cellspacing=2 cellpadding=2 width="100%">
              <tr align="top"> 
                <td valign="top" class="explain"> 
                  <?php echo $explain; ?>
                </td>
                <td class="n6" colspan="2"> 
                  <?php
					if ( strlen($link_url) > 7 ){
						echo "<a href=\"".$link_url."\" target='_blank'>";
						echo "<img src=\"./mag-prize_pict.php?pd_picture=".$picture."\" id=\"pict\" alt=\"".$presenter."\" border=0>";
						echo "</a>";
						}else{
						echo "<img src=\"./mag-prize_pict.php?pd_picture=".$picture."\" id=\"pict\" alt=\"".$presenter."\">";
						}
					?>
                </td>
              </tr>
            </table>
            <p class="company" align="center"> 
              <?php
if ( strlen($link_url) > 7 ){
	echo "<a href=\"".$link_url."\" target='_blank'>";
	echo "<nobr>$presenter</nobr>";
	echo "</a>";
}else{
	echo "<nobr>$presenter</nobr>";
}
?>
            </p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<form name="Pform1" method="post" action="mag-present2.php">
  <table width="753" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td> 
        <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr> 
            <td width="21"><img src="../img/r_left.gif" width="21" height="21"></td>
            <td width="578" bgcolor="#90c84b">　</td>
            <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
          </tr>
          <tr> 
            <td width="21" bgcolor="#ffffd3">　</td>
            <td width="578" bgcolor="#ffffd3">　</td>
            <td width="21" bgcolor="#ffffd3">　</td>
          </tr>
          <tr> 
            <td width="21" bgcolor="#ffffd3">　</td>
            <td width="578" bgcolor="#ffffd3"> 
              <!--応募方法-->
              <table border=1 cellspacing=1 cellpadding=3 width="100%" >
                <tr> 
                  <td bgcolor="#ffffd3" class="oubo" > 
                    <center>
                      <b>【応募方法】</b> 
                    </center>
                    
                      <table border="0">
                        <!--
<tr><td valign="top">１．</td><td>期間中に「めろんぱん」から、あなたのお気に入りのメルマガ３誌を探して購読手続きをします。</td></tr>
<tr><td valign="top">２．</td><td>メルマガを講読しているメールアドレスを記入してください。</td></tr>
<tr><td valign="top">３．</td><td>購読手続き後すぐに届く購読確認メールの一番下に「プレゼント応募券」がついています。その中の「応募コード（２０桁）」をそのままコピーして、このページの「応募券貼り付け欄」に３誌貼り付けてください。</td></tr>
<tr><td valign="top">４．</td><td>「今日のひとりごと」の欄には、めろんぱんトップページ左上にある「今日のひとりごと」をそのまま記入してください。</td></tr>
<tr><td valign="top">５．</td><td>このページ一番下の「応募する」ボタンを押して、応募が完了です。</td></tr>
<tr><td valign="top">６．</td><td>当選者の発表は、当選者の方へ直接メールにて行わせていただきます。</td></tr>
-->
                        <div><?= oubo_cmnt($present_type, $keyword_flag); ?></div>
                        <tr> 
                          <td valign="top" class="oubo">　</td>
                          <td class="oubo">※応募は、１人１回しかできません。</td>
                      </table>
                    
                  </td>
                </tr>
              </table>
            </td>
            <td width="21" bgcolor="#ffffd3">　</td>
          </tr>
          <tr> 
            <td width="21" bgcolor="#ffffd3">　</td>
            <td width="578" bgcolor="#ffffd3">　 
              <!--期間-->
              <BR>
              <table border=0 align="center">
                <tr> 
                  <td bgcolor="#ffffd3" align="right" class="oubo"><B>プレゼント応募券の挿入期間：</B></td>
                  <td class="oubo"><B> 
                    <?php echo $insert_s ." 〜 " . $insert_e; ?>
                    </B></td>
                </tr>
                <tr> 
                  <td bgcolor="#ffffd3" align="right" class="oubo"><B>プレゼント受付期間：</B></td>
                  <td class="oubo"><B> 
                    <?php echo $campaign_s ." 〜 " . $campaign_e; ?>
                    </B></td>
                </tr>
                <tr> 
                  <td bgcolor="#ffffd3" align="right" class="oubo"><B>抽選日ならびに当選者へのお知らせ日：</B></td>
                  <td class="oubo"><B> 
                    <?php echo $lottery ?>
                    </B></td>
                </tr>
              </table>
              <BR>
              <!--必要事項-->
              <BR>
              <table width="550" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr> 
                  <td width="250" valign="top"> 
                    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741" align="center" width="250">
                      <tr> 
                        <td> 
                          <table width="100%">
                            <tr> 
                              <td class="koumoku"colspan="3">メールアドレス</td>
                            </tr>
                            <tr> 
                              <td bgcolor="white" class="text9pt"><font size="2"> 
                                <input type="text" name="mail_adr" value="<?php echo $mail_adr; ?>" size="35">
                                </font></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741" align="center" width="250">
                      <tr> 
                        <td> 
                          <table width="100%">
                            <tr> 
                              <td valign="top" class="koumoku"colspan="3">今日のひとりごと　（<a href="http://www.melonpan.net/" target="_blank">トップページ左上</a>）</td>
                            </tr>
                            <tr> 
                              <td bgcolor="white" class="text9pt"><font size="2"> 
                                <input type="text" name="hitorigoto" value="<?php echo $hitorigoto; ?>" size="35">
                                </font></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td width="50">　</td>
                  <td width="250"> 
                    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741" align="center" width="250">
                      <tr> 
                        <td> 
                          <table width="100%">
                            <tr> 
                              <td valign="top" class="koumoku"colspan="3">応募コード貼り付け欄【１】</td>
                            </tr>
                            <tr> 
                              <td bgcolor="white" class="text9pt"><font size="2"> 
                                <input type="text" name="oubo1" value="<?php echo $oubo1; ?>" size="35">
                                </font></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741" align="center" width="250">
                      <tr> 
                        <td> 
                          <table width="100%">
                            <tr> 
                              <td valign="top" class="koumoku"colspan="3">応募コード貼り付け欄【２】</td>
                            </tr>
                            <tr> 
                              <td bgcolor="white" class="text9pt"><font size="2"> 
                                <input type="text" name="oubo2" value="<?php echo $oubo2; ?>" size="35">
                                </font></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741" align="center" width="250">
                      <tr> 
                        <td> 
                          <table width="100%">
                            <tr> 
                              <td valign="top" class="koumoku"colspan="3">応募コード貼り付け欄【３】</td>
                            </tr>
                            <tr> 
                              <td bgcolor="white" class="text9pt"><font size="2"> 
                                <input type="text" name="oubo3" value="<?php echo $oubo3; ?>" size="35">
                                </font></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    
                  </td>
                </tr>
              </table>
              <br>
              <table border="0" cellspacing="0" cellpadding="0" align="center">
                <tr> 
                  <td align="center"> 
                    <input type="submit" value=" 応募する ">
                    <input type="hidden" value="<?php echo $present_id; ?>" name="present_id">
                    <input type="hidden" value="<?php echo $present_name; ?>" name="present_name">
                    <input type="hidden" value="<?php echo $present_type; ?>" name="present_type">
                    <input type="hidden" value="<?php echo $lottery; ?>" name="lottery">
                    <input type="hidden" value="<?php echo $keyword_flag; ?>" name="keyword_flag">
                    <input type="button" value="    戻る    " onclick="history.back()">
                  </td>
                </tr>
              </table>
              <br>
            </td>
            <td width="21" bgcolor="#ffffd3">　</td>
          </tr>
          <tr> 
            <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
            <td width="578" bgcolor="#90c84b">　</td>
            <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><?php	draw_footer( ".." );	?></td>
    </tr>
  </table>
</form>
</body>
</html>
