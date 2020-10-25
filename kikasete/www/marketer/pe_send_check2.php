<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if (isset($pro_enq_no)) {
    $pro_enq = &$_SESSION['ss_pro_enq'];
    if ($pro_enq->pro_enq_no != $pro_enq_no)
        redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
    redirect('pe_index.php');

$search = &$pro_enq->search;
$cell = &$pro_enq->cell;

// markert/pe_edit3.php�ˤơ�ȯ�����γ�ǧ�ץܥ��󲡲�����g_table�б� 2006/03/15 BTI
$mon_flg = $search->get_target_flg_by_ref(&$pro_enq);
$search_cell = $cell->get_search_cell($search->make_sql(), true, $pro_enq->appearance_ratio, $mon_flg);

//$search_cell = $cell->get_search_cell($search->make_sql(), true, $pro_enq->appearance_ratio);
$send_cell = $cell->get_send_cell();

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

// ��­�����å�
$short = false;
for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
    for ($age = $age_s; $age <= $age_e && !$short; $age++) {
        for ($rate = $rate_s; $rate <= $rate_e && !$short; $rate++) {
            if ((int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate])
                $short = true;
        }
    }
}

$rowspan = $cell->age_option == 2 ? 3 : 2;
$colspan = $age_e - $age_s + 1;
?>
<? marketer_header('�У�異�󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
    </tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="788">
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
            <table width="650" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <span style="font-weight:bold;">ȯ�����������</span>
                    </td>
                </tr>
                <tr>
                    <td>
<?
if ($short) {
?>
                        <br>����ץ����­��ʤ����뤬����褦�Ǥ���<br>
                        <a href="javascript:history.back()">������Υڡ���</a>����ꥵ��ץ���������ľ���Ƥ���������
<?
} else {
?>
                        <br>
                        �ƥ���Ȥ�Ϳ���­��Ƥ���褦�Ǥ���
<?
}
?>
                    </td>
                </tr>
            </table>
            <br>
            <form>
            <center>
            <table width="650" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="100%" valign="top" bgcolor="#ffffff" align="center">
                        <font size="2" class="honbun2">
            <table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
                <tr bgcolor="#eeeeee" align="center">
                    <td nowrap width="10%" rowspan="<?=$rowspan?>">&nbsp;</td>
<?
if ($cell->sex_option == 2) {
?>
                    <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
                    <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
<?
} else {
?>
                    <td nowrap width="80%" colspan="<?=$colspan?>">���̻���ʤ�</td>
<?
}
?>
                    <td nowrap width="10%" rowspan="<?=$rowspan?>">���</td>
                </tr>
                <tr bgcolor="#eeeeee" align="center">
<?
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
    if ($cell->age_option == 2) {
?>
                    <td nowrap width="5%" class="footer_text">10��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">20��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">30��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">40��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">50��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">60��</td>
                    <td nowrap width="5%" rowspan=2 class="footer_text">70��<br>�ʾ�</td>
<?
    } elseif ($cell->age_option == 3) {
?>
                    <td nowrap width="10%" class="footer_text">10��</td>
                    <td nowrap width="10%" class="footer_text">20��</td>
                    <td nowrap width="10%" class="footer_text">30��</td>
                    <td nowrap width="10%" class="footer_text">40��</td>
                    <td nowrap width="10%" class="footer_text">50��</td>
                    <td nowrap width="10%" class="footer_text">60��</td>
                    <td nowrap width="10%" class="footer_text">70��<br>�ʾ�</td>
<?
    } else {
?>
                    <td nowrap width="10%" class="footer_text">ǯ�����ʤ�</td>
<?
    }
}
?>
                </tr>
<?
if ($cell->age_option == 2) {
?>
                <tr bgcolor="#eeeeee" align="center">
<?
    for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
<?
    }
?>
                </tr>
<?
}

if ($cell->rate_option == 2) {
    for ($i = 1; $i <= 3; $i++)
        $rate_name[$i] = decode_mikikon($i);
} else {
    $sql = "SELECT ar3_area3_cd,ar3_area3_name FROM m_area3";
    $result = db_exec($sql);
    $nrow = pg_numrows($result);
    for ($i = 0; $i < $nrow; $i++) {
        $fetch = pg_fetch_object($result, $i);
        $rate_name[$fetch->ar3_area3_cd] = $fetch->ar3_area3_name;
    }
}

$sum_row = array();
for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
?>
                <tr bgcolor="#ffffff">
                    <td bgcolor="#eeeeee" nowrap align="center"><?=$rate_name[$rate]?></td>
<?
    $sum_col = 0;
    for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
        for ($age = $age_s; $age <= $age_e; $age++) {
            $num = min($search_cell[$sex][$age][$rate], $send_cell[$sex][$age][$rate]);
            $sum_col += $num;
            $sum_row[$sex][$age] += $num;
            $bgcolor = (int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate] ? '#ffddef' : '#ffffff';
?>
                    <td nowrap align="right" bgcolor="<?=$bgcolor?>"><?=number_format($num)?></td>
<?
        }
    }
?>
                    <td nowrap align="right"><?=number_format($sum_col)?></td>
                </tr>
<?
}
?>
                <tr bgcolor="#ffffff">
                    <td bgcolor="#eeeeee" nowrap align="center">���</td>
<?
$sum_col = 0;
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
    for ($age = $age_s; $age <= $age_e; $age++) {
        $num = $sum_row[$sex][$age];
        $sum_col += $num;
?>
                    <td nowrap align="right"><?=number_format($num)?></td>
<?
    }
}
?>
                    <td nowrap align="right"><?=number_format($sum_col)?></td>
                </tr>
            </table>
<?
if ($short) {
?>
            <br>
            <table width="650" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <span style="font-weight:bold;">�����̤Ǥ�������</span>
                    </td>
                </tr>
            </table>
            <table width="650" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="100%" valign="top" bgcolor="#ffffff" align="center">

            <table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
                <tr bgcolor="#eeeeee" align="center">
                    <td nowrap width="10%" rowspan="<?=$rowspan?>">&nbsp;</td>
<?
    if ($cell->sex_option == 2) {
?>
                    <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
                    <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
<?
    } else {
?>
                    <td nowrap width="80%" colspan="<?=$colspan?>">���̻���ʤ�</td>
<?
    }
?>
                    <td nowrap width="10%" rowspan="<?=$rowspan?>">���</td>
                </tr>
                <tr bgcolor="#eeeeee" align="center">
<?
    for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
        if ($cell->age_option == 2) {
?>
                    <td nowrap width="5%" class="footer_text">10��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">20��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">30��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">40��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">50��</td>
                    <td nowrap width="10%" colspan=2 class="footer_text">60��</td>
                    <td nowrap width="5%" rowspan=2 class="footer_text">70��<br>�ʾ�</td>
<?
        } elseif ($cell->age_option == 3) {
?>
                    <td nowrap width="10%" class="footer_text">10��</td>
                    <td nowrap width="10%" class="footer_text">20��</td>
                    <td nowrap width="10%" class="footer_text">30��</td>
                    <td nowrap width="10%" class="footer_text">40��</td>
                    <td nowrap width="10%" class="footer_text">50��</td>
                    <td nowrap width="10%" class="footer_text">60��</td>
                    <td nowrap width="10%" class="footer_text">70��<br>�ʾ�</td>
<?
        } else {
?>
                    <td nowrap width="10%" class="footer_text">ǯ�����ʤ�</td>
<?
        }
    }
?>
                </tr>
<?
    if ($cell->age_option == 2) {
?>
                <tr bgcolor="#eeeeee" align="center">
<?
        for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
                    <td class="footer_text">��</td>
<?
        }
?>
                </tr>
<?
    }

    if ($cell->rate_option == 2) {
        for ($i = 1; $i <= 3; $i++)
            $rate_name[$i] = decode_mikikon($i);
    } else {
        $sql = "SELECT ar3_area3_cd,ar3_area3_name FROM m_area3";
        $result = db_exec($sql);
        $nrow = pg_numrows($result);
        for ($i = 0; $i < $nrow; $i++) {
            $fetch = pg_fetch_object($result, $i);
            $rate_name[$fetch->ar3_area3_cd] = $fetch->ar3_area3_name;
        }
    }

    $sum_row = array();
    for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
?>
                <tr bgcolor="#ffffff">
                    <td bgcolor="#eeeeee" nowrap align="center"><?=$rate_name[$rate]?></td>
<?
        $sum_col = 0;
        for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
            for ($age = $age_s; $age <= $age_e; $age++) {
                $num = $send_cell[$sex][$age][$rate];
                $sum_col += $num;
                $sum_row[$sex][$age] += $num;
?>
                    <td nowrap align="right"><?=number_format($num)?></td>
<?
            }
        }
?>
                    <td nowrap align="right"><?=number_format($sum_col)?></td>
                </tr>
<?
    }
?>
                <tr bgcolor="#ffffff">
                    <td bgcolor="#eeeeee" nowrap align="center">���</td>
<?
    $sum_col = 0;
    for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
        for ($age = $age_s; $age <= $age_e; $age++) {
            $num = $sum_row[$sex][$age];
            $sum_col += $num;
?>
                    <td nowrap align="right"><?=number_format($num)?></td>
<?
        }
    }
?>
                    <td nowrap align="right"><?=number_format($sum_col)?></td>
                </tr>
            </table>
<?
}
?>

                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="500" valign="top" bgcolor="#ffffff" align="center">
                            <tr>
                                <td width="100%" align="center">
                                    <a href="pe_edit3.php?edit=1"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
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
