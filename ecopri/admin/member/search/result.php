<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員数検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/list_item.php");

//メイン処理
set_global('member', '会員情報管理', '会員数検索', BACK_TOP);

// セッション処理
session_start();
if (!isset($_SESSION['ss_search']))
	$_SESSION['ss_search'] = new search_class;
$search = &$_SESSION['ss_search'];

if (!isset($_SESSION['ss_litem']))
	$_SESSION['ss_litem'] = new list_item_class;
$litem = &$_SESSION['ss_litem'];

$search->get_form();

// 検索結果表示
$sql = 'SELECT COUNT(*) FROM t_member';
$all = db_fetch1($sql);

$sql = 'SELECT COUNT(*) FROM t_member where mb_step=3';
$mb_count = db_fetch1($sql);

if ($litem->where == '')
	$where = $search->make_sql();
else
	$where = $litem->where;

$sql = "SELECT COUNT(*) FROM t_member WHERE $where";

$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$find = $fetch[0];
}

if ($litem->condition == '')
	$condition = $search->get_condition();
else
	$condition = $litem->condition;

$litem->renew_flag = 'on';

// 情報更新日時取得
$sql = "SELECT MAX(lb_date) AS lb_date FROM l_batch WHERE lb_file_name='co2_update.php'";
$lb_date = db_fetch1($sql);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function open_list() {
	window.open("<?=$top?>/member/search/list.php" , "_blank", "scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}
function all_nocheck() {
	var n = document.form2.length;
	for (var i = 0; i < n; i++)
		document.form2[i].checked = '';
	document.form2.id.checked = true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0" width=700>■検索結果</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width='100%'>
							<tr>
								<td class="m2" width="25%">検索された会員数</td>
								<td class="n2"><?=number_format($find)?></td>
							</tr>
							<tr>
								<td class="m2">登録会員数</td>
								<td class="n2"><?=number_format($mb_count)?></td>
							</tr>
							<tr>
								<td class="m2">検索された割合</td>
								<td class="n2"><?=number_format($find / $mb_count * 100, 1)?>%</td>
							</tr>
							<tr>
								<td class="m2">検索条件</td>
								<td class="n2"><font size="-1"><?=nl2br($condition)?></font></td>
							</tr>
							<tr>
								<td class="m2">ＳＱＬ</td>
								<td class="n2"><font class="small"><?=$sql?></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div align="center"><br>
			<form method="post" name="form1" action="list.php" target="_blank">
				<input type="hidden" name="where" <?=value($where)?>>
				<input type="submit" value="一覧表示">
				<input type="button" value="　戻る　" onclick="location.href='condition.php'">
			</form>
			</div>

			<form method="post" name="form2" action="list_item.php">
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0" width=700>■表示項目指定</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width='100%' class="small">
							<tr>
								<td class="m2" width="25%">登録者情報</td>
								<td class="n2">
									<nobr><input type="checkbox" name="id" <?=checked($litem->id)?> checked>ID&nbsp;</nobr>
									<nobr><input type="checkbox" name="name" <?=checked($litem->name)?>>氏名&nbsp;</nobr>
									<nobr><input type="checkbox" name="kana" <?=checked($litem->kana)?>>フリガナ&nbsp;</nobr>
									<nobr><input type="checkbox" name="mail_addr" <?=checked($litem->mail_addr)?>>メールアドレス&nbsp;</nobr>
									<nobr><input type="checkbox" name="sex" <?=checked($litem->sex)?>>性別&nbsp;</nobr>
									<nobr><input type="checkbox" name="birthday" <?=checked($litem->birthday)?>>生年月日&nbsp;</nobr>
									<nobr><input type="checkbox" name="age" <?=checked($litem->age)?>>年齢&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">住居情報</td>
								<td class="n2">
									<nobr><input type="checkbox" name="area" <?=checked($litem->area)?>>居住地域&nbsp;</nobr>
									<nobr><input type="checkbox" name="zip" <?=checked($litem->zip)?>>郵便番号&nbsp;</nobr>
									<nobr><input type="checkbox" name="todoufuken" <?=checked($litem->todoufuken)?>>都道府県&nbsp;</nobr>
									<nobr><input type="checkbox" name="address" <?=checked($litem->address)?>>住所&nbsp;</nobr>
									<nobr><input type="checkbox" name="keitai" <?=checked($litem->keitai)?>>住居形態&nbsp;</nobr>
									<nobr><input type="checkbox" name="sozai" <?=checked($litem->sozai)?>>住居構造(木造：y/n)&nbsp;</nobr>
									<nobr><input type="checkbox" name="hebel" <?=checked($litem->hebel)?>>ヘーベルハウス&nbsp;</nobr>
									<nobr><input type="checkbox" name="chikunen" <?=checked($litem->chikunen)?>>建設年次&nbsp;</nobr>
									<nobr><input type="checkbox" name="room" <?=checked($litem->room)?>>住居間取り&nbsp;</nobr>
									<nobr><input type="checkbox" name="space" <?=checked($litem->space)?>>住居面積&nbsp;</nobr>
									<nobr><input type="checkbox" name="boiler" <?=checked($litem->boiler)?>>給湯器タイプ&nbsp;</nobr>
									<nobr><input type="checkbox" name="gas_kind" <?=checked($litem->gas_kind)?>>ガス種&nbsp;</nobr>
									<nobr><input type="checkbox" name="gas_type" <?=checked($litem->gas_type)?>>ガスタイプ&nbsp;</nobr>
									<nobr><input type="checkbox" name="wt_month" <?=checked($litem->wt_month)?>>水道検針月&nbsp;</nobr>
									<nobr><input type="checkbox" name="car" <?=checked($litem->car)?>>車総数&nbsp;</nobr>
									<nobr><input type="checkbox" name="gcar" <?=checked($litem->gcar)?>>ガソリン車&nbsp;</nobr>
									<nobr><input type="checkbox" name="dcar" <?=checked($litem->dcar)?>>ディーゼル車&nbsp;</nobr>
									<nobr><input type="checkbox" name="bike" <?=checked($litem->bike)?>>バイク&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">家族構成</td>
								<td class="n2">
									<nobr><input type="checkbox" name="fm_ctg" <?=checked($litem->fm_ctg)?>>家族カテゴリ&nbsp;</nobr>
									<nobr><input type="checkbox" name="fm_detail" <?=checked($litem->fm_detail)?>>家族の続柄と年齢</nobr>
									<nobr><input type="checkbox" name="fm_child" <?=checked($litem->fm_child)?>>子供(20歳未満)の有無&nbsp;</nobr>
									<nobr><input type="checkbox" name="fm_youngest" <?=checked($litem->fm_youngest)?>>最年少家族の年齢&nbsp;</nobr>
<!--									<nobr><input type="checkbox" name="fm_age" <?=checked($litem->fm_age)?>>家族の年齢&nbsp;</nobr>-->
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">入力項目設定</td>
								<td class="n2">
									<nobr><input type="checkbox" name="inp_ol" <?=checked($litem->inp_ol)?>>灯油入力&nbsp;</nobr>
									<nobr><input type="checkbox" name="inp_gm" <?=checked($litem->inp_gm)?>>ゴミ入力&nbsp;</nobr>
									<nobr><input type="checkbox" name="inp_gl" <?=checked($litem->inp_gl)?>>ガソリン入力&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">登録・完了情報</td>
								<td class="n2">
									<nobr><input type="checkbox" name="reg_date_kari" <?=checked($litem->reg_date_kari)?>>仮登録日&nbsp;</nobr>
									<nobr><input type="checkbox" name="reg_date" <?=checked($litem->reg_date)?>>本登録日&nbsp;</nobr>
									<nobr><input type="checkbox" name="entry" <?=checked($litem->entry)?>>入会経過月数&nbsp;</nobr>
									<nobr><input type="checkbox" name="reg_inp" <?=checked($litem->reg_inp)?>>自己完了回数&nbsp;</nobr>
								</td>
							</tr>
							<tr>
								<td class="m2" width="25%">入力結果情報</td>
								<td class="n2"><font class="small">
									<select name="inp_y"><?=select_year('2002', '指定なし', $litem->inp_y)?></select>年
									<select name="inp_m"><?=select_month('　', $litem->inp_m)?></select>月<br>
<!--									<nobr>&nbsp;自己完了の有無（<input type="checkbox" name="commit_m" <?=checked($litem->commit_m)?>>表示）</nobr><br>-->
									<nobr>&nbsp;入力フラグ（
										<input type="checkbox" name="inp_flag_ol" <?=checked($litem->inp_flag_ol)?>>灯油,
										<input type="checkbox" name="inp_flag_gm" <?=checked($litem->inp_flag_gm)?>>ゴミ,
										<input type="checkbox" name="inp_flag_gl" <?=checked($litem->inp_flag_gl)?>>ガソリン）
									</nobr><br><br>
									<nobr>&nbsp;使用量（
										<input type="checkbox" name="use_al" <?=checked($litem->use_al)?>>全て,
										<input type="checkbox" name="use_el" <?=checked($litem->use_el)?>>電気,
										<input type="checkbox" name="use_gs" <?=checked($litem->use_gs)?>>ガス,
										<input type="checkbox" name="use_wt" <?=checked($litem->use_wt)?>>水道,
										<input type="checkbox" name="use_ol" <?=checked($litem->use_ol)?>>灯油,
										<input type="checkbox" name="use_gl" <?=checked($litem->use_gl)?>>ガソリン,
										<input type="checkbox" name="use_gm" <?=checked($litem->use_gm)?>>ゴミ）
									</nobr><br>
									<nobr>&nbsp;金額　 （
										<input type="checkbox" name="sum_al" <?=checked($litem->sum_al)?>>全て,
										<input type="checkbox" name="sum_el" <?=checked($litem->sum_el)?>>電気,
										<input type="checkbox" name="sum_gs" <?=checked($litem->sum_gs)?>>ガス,
										<input type="checkbox" name="sum_wt" <?=checked($litem->sum_wt)?>>水道,
										<input type="checkbox" name="sum_ol" <?=checked($litem->sum_ol)?>>灯油,
										<input type="checkbox" name="sum_gl" <?=checked($litem->sum_gl)?>>ガソリン）（
										<input type="checkbox" name="sum_4total" <?=checked($litem->sum_4total)?>>4種合計,
										<input type="checkbox" name="sum_6total" <?=checked($litem->sum_6total)?>>6種合計）
									</nobr><br>
									<nobr>&nbsp;CO2量（
										<input type="checkbox" name="co2_al" <?=checked($litem->co2_al)?>>全て,
										<input type="checkbox" name="co2_el" <?=checked($litem->co2_el)?>>電気,
										<input type="checkbox" name="co2_gs" <?=checked($litem->co2_gs)?>>ガス,
										<input type="checkbox" name="co2_wt" <?=checked($litem->co2_wt)?>>水道,
										<input type="checkbox" name="co2_ol" <?=checked($litem->co2_ol)?>>灯油,
										<input type="checkbox" name="co2_gl" <?=checked($litem->co2_gl)?>>ガソリン,
										<input type="checkbox" name="co2_gm" <?=checked($litem->co2_gm)?>>ゴミ）（
										<input type="checkbox" name="co2_4total" <?=checked($litem->co2_4total)?>>4種合計,
										<input type="checkbox" name="co2_6total" <?=checked($litem->co2_6total)?>>6種合計）
									<nobr><br><br>
									<nobr>&nbsp;削減使用量（
										<input type="checkbox" name="use_cut_al" <?=checked($litem->use_cut_al)?>>全て,
										<input type="checkbox" name="use_cut_el" <?=checked($litem->use_cut_el)?>>電気,
										<input type="checkbox" name="use_cut_gs" <?=checked($litem->use_cut_gs)?>>ガス,
										<input type="checkbox" name="use_cut_wt" <?=checked($litem->use_cut_wt)?>>水道,
										<input type="checkbox" name="use_cut_ol" <?=checked($litem->use_cut_ol)?>>灯油,
										<input type="checkbox" name="use_cut_gl" <?=checked($litem->use_cut_gl)?>>ガソリン,
										<input type="checkbox" name="use_cut_gm" <?=checked($litem->use_cut_gm)?>>ゴミ）
									</nobr><br>
									<nobr>&nbsp;削減金額 　（
										<input type="checkbox" name="sum_cut_al" <?=checked($litem->sum_cut_al)?>>全て,
										<input type="checkbox" name="sum_cut_el" <?=checked($litem->sum_cut_el)?>>電気,
										<input type="checkbox" name="sum_cut_gs" <?=checked($litem->sum_cut_gs)?>>ガス,
										<input type="checkbox" name="sum_cut_wt" <?=checked($litem->sum_cut_wt)?>>水道,
										<input type="checkbox" name="sum_cut_ol" <?=checked($litem->sum_cut_ol)?>>灯油,
										<input type="checkbox" name="sum_cut_gl" <?=checked($litem->sum_cut_gl)?>>ガソリン）（
										<input type="checkbox" name="sum_4cut" <?=checked($litem->sum_4cut)?>>4種合計,
										<input type="checkbox" name="sum_6cut" <?=checked($litem->sum_6cut)?>>6種合計）
									</nobr>
									<nobr>&nbsp;CO2削減量（
										<input type="checkbox" name="co2_cut_al" <?=checked($litem->co2_cut_al)?>>全て,
										<input type="checkbox" name="co2_cut_el" <?=checked($litem->co2_cut_el)?>>電気,
										<input type="checkbox" name="co2_cut_gs" <?=checked($litem->co2_cut_gs)?>>ガス,
										<input type="checkbox" name="co2_cut_wt" <?=checked($litem->co2_cut_wt)?>>水道,
										<input type="checkbox" name="co2_cut_ol" <?=checked($litem->co2_cut_ol)?>>灯油,
										<input type="checkbox" name="co2_cut_gl" <?=checked($litem->co2_cut_gl)?>>ガソリン,
										<input type="checkbox" name="co2_cut_gm" <?=checked($litem->co2_cut_gm)?>>ゴミ）（
										<input type="checkbox" name="co2_4cut" <?=checked($litem->co2_4cut)?>>4種合計,
										<input type="checkbox" name="co2_6cut" <?=checked($litem->co2_6cut)?>>6種合計）
									<nobr><br><br>
									<nobr>&nbsp;累積削減使用量（
										<input type="checkbox" name="use_rui_al" <?=checked($litem->use_rui_al)?>>全て,
										<input type="checkbox" name="use_rui_el" <?=checked($litem->use_rui_el)?>>電気,
										<input type="checkbox" name="use_rui_gs" <?=checked($litem->use_rui_gs)?>>ガス,
										<input type="checkbox" name="use_rui_wt" <?=checked($litem->use_rui_wt)?>>水道,
										<input type="checkbox" name="use_rui_ol" <?=checked($litem->use_rui_ol)?>>灯油,
										<input type="checkbox" name="use_rui_gl" <?=checked($litem->use_rui_gl)?>>ガソリン,
										<input type="checkbox" name="use_rui_gm" <?=checked($litem->use_rui_gm)?>>ゴミ）
									</nobr>
									<nobr>&nbsp;累積削減金額　 （
										<input type="checkbox" name="sum_rui_al" <?=checked($litem->sum_rui_al)?>>全て,
										<input type="checkbox" name="sum_rui_el" <?=checked($litem->sum_rui_el)?>>電気,
										<input type="checkbox" name="sum_rui_gs" <?=checked($litem->sum_rui_gs)?>>ガス,
										<input type="checkbox" name="sum_rui_wt" <?=checked($litem->sum_rui_wt)?>>水道,
										<input type="checkbox" name="sum_rui_ol" <?=checked($litem->sum_rui_ol)?>>灯油,
										<input type="checkbox" name="sum_rui_gl" <?=checked($litem->sum_rui_gl)?>>ガソリン）（
										<input type="checkbox" name="sum_4rui" <?=checked($litem->sum_4rui)?>>4種合計,
										<input type="checkbox" name="sum_6rui" <?=checked($litem->sum_6rui)?>>6種合計）
									</nobr>
									<nobr>&nbsp;累積CO2削減量（
										<input type="checkbox" name="co2_rui_al" <?=checked($litem->co2_rui_al)?>>全て,
										<input type="checkbox" name="co2_rui_el" <?=checked($litem->co2_rui_el)?>>電気,
										<input type="checkbox" name="co2_rui_gs" <?=checked($litem->co2_rui_gs)?>>ガス,
										<input type="checkbox" name="co2_rui_wt" <?=checked($litem->co2_rui_wt)?>>水道,
										<input type="checkbox" name="co2_rui_ol" <?=checked($litem->co2_rui_ol)?>>灯油,
										<input type="checkbox" name="co2_rui_gl" <?=checked($litem->co2_rui_gl)?>>ガソリン,
										<input type="checkbox" name="co2_rui_gm" <?=checked($litem->co2_rui_gm)?>>ゴミ）（
										<input type="checkbox" name="co2_4rui" <?=checked($litem->co2_4rui)?>>4種合計,
										<input type="checkbox" name="co2_6rui" <?=checked($litem->co2_6rui)?>>6種合計）
									<nobr><br>
									<span class="note">※ 累積表示には時間がかかります。</span>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right"><font color="red" size=-1>最終更新日時：<?=date('Y/m/d H:i:s', sql_time($lb_date))?></font></td>
	</tr>
		<td>
			<div align="center">
				<input type="hidden" name="where" <?=value($where)?>>
				<input type="hidden" name="find" <?=value($find)?>>
				<input type="hidden" name="mb_count" <?=value($mb_count)?>>
				<input type="hidden" name="condition" <?=value($condition)?>>
				<input type="hidden" name="sql" <?=value($sql)?>>
				<input type="submit" value=" 一覧表示 ">
				<input type="button" value=" リセット " onclick="document.form1.reset();all_nocheck()">
				<input type="button" value="最新情報取得" onclick="location.href='../../renew.php'">
				<input type="button" value="　戻る　" onclick="location.href='condition.php'">
			</form>
			</div>
		</td>
	</tr>
</table>

</div>

<? page_footer() ?>
</body>
</html>
