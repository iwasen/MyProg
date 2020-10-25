<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

define("DEBUG", false);

// セッション処理
if (isset($pro_enq_no)) {
    $marketer_id= $_SESSION['ss_marketer_id'];
    $owner_id = $_SESSION['ss_owner_id'];

    if (isset($_SESSION['ss_pro_enq'])) {
        $pro_enq = &$_SESSION['ss_pro_enq'];
        if ($pro_enq->pro_enq_no != $pro_enq_no)
            $pro_enq->read_db($owner_id, $pro_enq_no);
    } else {
        $_SESSION['ss_pro_enq'] = new pro_enquete_class;
        $pro_enq = &$_SESSION['ss_pro_enq'];
        $pro_enq->read_db($owner_id, $pro_enq_no);
    }
} else
    redirect('pe_index.php');

$search = &$pro_enq->search;

// モニター数取得   // 2006/02/21 BTI
//$monitor_num = $search->get_monitor_num();
// inc/pro_search.phpの関数呼び方変更、画面上のセル情報に従う人数検索処理のため 2006/03/14 BTI
//$monitor_num = $search->get_monitor_num($marketer_id, $pro_enq_no, $pro_enq->sample_num);
$monitor_num = $search->get_monitor_num_by_ref(&$pro_enq);
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
    </tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
        <td width="778" align="center" valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="778">
                <tr>
                    <td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
                    <td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
                </tr>
            </table>
            <br>
            <table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
                <tr>
                    <td>
                        <span style="font-weight:bold;">設定した条件による絞込み結果です。</span>
                    </td>
                </tr>
            </table>
            <br>
            <form>
            <center>
            <table width="500" border="0" cellspacing="0" cellpadding="0">
                <tr>

<td>
<table border=0 cellspacing=1 cellpadding=5 width="100%">
<tr bgcolor="#e5eaf0">
<td align="center">
                        <b><?=$monitor_num?></b> 人が該当しました<? if (DEBUG) echo("（".$search->get_target_flg()."）"); ?>
                        <br><br>
                        ※ここでの結果は参考値であり、実施時の対象モニターとは異なる場合があります</td>
</tr>

                <tr>
                    <td>
                        <table width="500" valign="top" bgcolor="#ffffff" align="center">
                            <tr>
                                <td width="100%" align="center">

<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </center>
            </form>
        </td>
    </tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
