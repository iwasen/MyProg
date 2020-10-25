<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 cellspacing=0 cellpadding=0 width="100%">', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', ">", htmlspecialchars($fetch[1]), "</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$search = &$myp->search;
?>
<? marketer_header('Myパートナー', PG_NULL, "", false) ?>

<script type="text/javascript">

<!--
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function all_check(radio, flag) {
	var f = document.form1;
	for (var i = 0; i < f[radio].length; i++)
		f[radio][i].checked = flag;
}
//jeon_start seq=mp3
function view(){
	var f = document.form1;	
	if(f.ch_sele.checked==true){
		document.getElementById("se_zi_ki").style.display="";
		document.getElementById("sezu").style.display="";
		document.getElementById("view").style.display="";
		td.rowSpan=1;
		if(f.ch_zip.checked==true){
			document.getElementById("v_zip").style.display="";
			td.rowSpan=2;			
		}else{
			document.getElementById("v_zip").style.display="none";
			f.zip.value="";
		}
		if(f.ch_kinmu.checked==true){
			document.getElementById("v_kinmu").style.display="";
			td.rowSpan=3;
		}else{
			document.getElementById("v_kinmu").style.display="none";
			f.kinmuchi.value="";
		}
		if(f.ch_zip.checked==true && f.ch_kinmu.checked==true){
			document.getElementById("v_andor").style.display="";
			document.getElementById("view").style.display="none";
			td.rowSpan=3;
		}else{
			document.getElementById("v_andor").style.display="none";		
		}
		if(f.ch_zip.checked==false && f.ch_kinmu.checked==false){
			td.rowSpan=1;
		}
	}else{
		document.getElementById("se_zi_ki").style.display="none";
		document.getElementById("sezu").style.display="none";
		document.getElementById("v_andor").style.display="none";
		document.getElementById("v_kinmu").style.display="none";
		document.getElementById("v_zip").style.display="none";
		document.getElementById("view").style.display="none";
		td.rowSpan=1;
		f.ch_zip.checked=false;
		f.ch_kinmu.checked=false;
		f.zip.value="";
		f.kinmuchi.value="";	
	}
}
function onlyNumberForPlan(sql){
	var f = document.form1;	
	var objEv = f.zip;
	var objEv2 = f.kinmuchi;
	if(isNumComma(objEv)==false || isNumComma(objEv2)==false){
		alert("郵便番号の指定に誤りがあります。\n７桁以下の半角数字のみ、入力してください。");
		return false;
	}
	f.next_action.value=sql;
	f.submit();
	return true;
}

function isNumComma(input) {
 	var chars = ",0123456789\r\n";
 	return containsCharsOnly(input,chars);
}

function containsCharsOnly(input,chars) {
var i=0; 
	 for (var inx = 0; inx < input.value.length; inx++) {
	 	if (chars.indexOf(input.value.charAt(inx)) == -1)
	 	return false;
	 if(input.value.charAt(inx)==',' || input.value.charAt(inx)=='\r' || input.value.charAt(inx)=='\n'){
	  i=0;
	 }else{
	  i++;
	 }
 	 if(i>7)
	 return false;
	 }
	 return true;
}
//jeon_end seq=mp3
window.onload = function() {
  change_age_type();
  //jeon_start seq=mp3
  //view();
  //jeon_end seq=mp3
}

//-->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>

			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_1.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_0.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>
			<form method="post" name="form1" action="mp_r_job_update3.php" >
			<table border=0 cellspacing=2 cellpadding=3 width="90%">
				<tr>
					<td bgcolor="#eff7e8">■条件の設定</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff">各項目で全て選択していない場合は、全て選択している場合と同じです。<div class="footer_text">※　<span style="color:#FF0000;">セル指定、子供の有無、同居家族の指定は、<a href="myp_price.php" target="_blank">オプション料金</a>がかかります。</span></div></td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%">最大発信数</td>
								<td width="85%" bgcolor="#ffeecc" colspan="2"><input type="text" name="send_num" size="5" maxlength="5" <?=value($myp->send_num)?>> 通</td>
							</tr>


							<tr>
								<td bgcolor="#eeeeee" width="15%">対象モニター</td>
								<td width="85%" bgcolor="#ffeecc" colspan="2">
									<input type="radio" name="type" <?=value_checked('2', $search->type)?>>リサーチパートナーのみ<br>
									<input type="radio" name="type" <?=value_checked('4', $search->type)?>>リサーチパートナー優先<br>
									<input type="radio" name="type" <?=value_checked('3', $search->type)?>>通常のモニター優先<br>
									<input type="radio" name="type" <?=value_checked('1', $search->type)?>>通常のモニターのみ<br>
									<input type="radio" name="type" <?=value_checked('0', $search->type)?>>全て
								</td>
							</tr>



<!--
<input type="hidden" name="type" value="2">
-->
							<tr>
								<td bgcolor="#eeeeee" width="15%">性別</td>
								<td width="85%" bgcolor="#ffeecc" colspan="2">
									<input type="checkbox" name="sex[]" <?=value_checked('1', $search->sex)?>>男性&nbsp;
									<input type="checkbox" name="sex[]" <?=value_checked('2', $search->sex)?>>女性
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">年代</td>
<?
// 年齢指定方法
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
?>
								<td bgcolor="#ffeecc"  colspan="2">
									<select name="age_type" onchange="change_age_type()">
										<option <?=value_selected('', $age_type)?>>形式を選択してください</option>
										<option <?=value_selected('1', $age_type)?>>5歳刻みの年齢</option>
										<option <?=value_selected('2', $age_type)?>>任意の年齢</option>
									</select>
									<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 4);
?>
									</span>
									<span id="age_type2">
									<table border=0 width="100%">
										<tr>
											<td><input type="text" name="age_from" <?=value($search->age_from)?> size="4" maxlength="2">
											才〜
											<input type="text" name="age_to" <?=value($search->age_to)?> size="4" maxlength="2">
											才 （半角数字）</td>
										</tr>
									</table>
									</span>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">未既婚</td>
								<td bgcolor="#ffeecc" colspan="2">
									<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>独身&nbsp;
									<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>既婚&nbsp;
									<input type="checkbox" name="mikikon[]" <?=value_checked('3', $search->mikikon)?>>その他
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">居住地域</td>
								<td bgcolor="#ffeecc" colspan="2">
									<div>
										<input type="button" value="全選択" onclick="all_check('jitaku_area[]', true)">
										<input type="button" value="全ｸﾘｱ" onclick="all_check('jitaku_area[]', false)">
									</div>
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $search->jitaku_area, 5);
?>
								</td>
							</tr>
							<!--jeon_start seq=mp3-->
							<? /*	<tr>
								<td bgcolor="#eeeeee" id="td">郵便番号<br>（半角数字）</td>
								<td bgcolor="#ffeecc"  colspan="2">		<table border='0' width=100%><tr><td width='33%'>
																		<input type="checkbox" name="ch_sele" <?=zip_checked($search->zip,$search->zip_kinmuchi)?> onclick="view()">指定する
																		</td>
																		<td id="se_zi_ki" style="display:none" width='33%'>
																		<input type="checkbox" name="ch_zip" <?=zip_checked($search->zip,0)?> onclick="view()">居住地域
																		<input type="checkbox" name="ch_kinmu" <?=zip_checked($search->zip_kinmuchi,0)?> onclick="view()">勤務地
																		</td>
																		<td id="v_andor" style="display:none" width='33%'>
																		<input type="radio" name="andor" checked <?=value_checked('1',$search->zip_kinmuchi_andor)?>>AND
																		<input type="radio" name="andor" <?=value_checked('2',$search->zip_kinmuchi_andor)?>>OR
																		</td>
																		<td id="view" style="display:none" width='33%'>
																		&nbsp;
																		</td>
																		</tr>
																		<tr id="sezu" style="display:none"><td colspan="3" >郵便番号を複数入れる場合、カンマ区切りあるいは改行区切りにて入力してください。</td></tr>
																</table>	
								</td>
							</tr> */?>
							<tr id="v_zip" style=display:"none"><td bgcolor="#eeeeee" width=10%>居住地域</td><td bgcolor="#ffeecc"><textarea rows="3" cols="69%" name="zip"><?=$search->zip?></textarea></td></tr>													
							<tr id="v_kinmu" style=display:"none"><td bgcolor="#eeeeee" width=10%>勤務地</td><td bgcolor="#ffeecc"><textarea rows="3" cols="69%" name="kinmuchi"><?=$search->zip_kinmuchi?></textarea></td></tr>
									
							<!--jeon_end seq=mp3-->
							
							<tr>
								<td bgcolor="#eeeeee">職業</td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">業種</td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
checkbox_common($sql, 'gyoushu', $search->gyoushu, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">職種</td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
checkbox_common($sql, 'shokushu', $search->shokushu, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">子供の有無</td>
								<td bgcolor="#ffeecc" colspan='2'>
									<input type="checkbox" name="have_child[]" <?=value_checked_multi('1', $search->have_child)?>>有り&nbsp;
									<input type="checkbox" name="have_child[]" <?=value_checked_multi('2', $search->have_child)?>>無し
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">住居形態</td>
								<td bgcolor="#ffeecc" colspan='2'>
									<input type="checkbox" name="housing_form[]" <?=value_checked_multi('1', $search->housing_form)?>>一戸建て&nbsp;
									<input type="checkbox" name="housing_form[]" <?=value_checked_multi('2', $search->housing_form)?>>集合住宅
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">車の所有</td>
								<td bgcolor="#ffeecc" colspan='2'>
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>免許と車を所有している&nbsp;
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>免許のみ所有している&nbsp;
									<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>免許は所有していない
								</td>
							</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
							<tr>
								<td bgcolor="#eeeeee"><?=htmlspecialchars($profile_name)?></td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
checkbox_common($sql, 'ma_profile', $search->ma_profile, 2);
?>
								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">よく行くコンビニ</td>
								<td bgcolor="#ffeecc">
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">よく行くスーパー</td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">興味ジャンル</td>
								<td bgcolor="#ffeecc" colspan='2'>
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
								</td>
							</tr>
						</table>
					</tr>
				</td>
			</table>

			<br>
			<!--jeon_start seq=mp3-->
			<input type="button" value="　人数検索　" onclick=onlyNumberForPlan('search')>
			<input type="button" value="<?=$myp->recruit_flag & 0x04 ? '　更新　' : '　登録　'?>" onClick=onlyNumberForPlan('update')>
			<!--
			<input type="submit" value="　人数検索　" onclick="document.form1.next_action.value='search'">
			<input type="submit" value="<?=$myp->recruit_flag & 0x04 ? '　更新　' : '　登録　'?>" onclick="document.form1.next_action.value='update'">
			-->
			<!--jeon_end seq=mp3-->
			<input type="button" value="　戻る　" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">
			
			<input type="hidden" name="next_action">
			</form>
			<!-- myパートナー -->
<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
