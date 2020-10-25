<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$enquete = &$pro_enq->enquete;
$search = &$pro_enq->search;
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
						<span style="font-weight:bold;">本当に削除されますか？</span>
					</td>
				</tr>
			</table>
			<br>
			<table width="680" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">概要設定内容</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>アンケートタイトル</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=htmlspecialchars($enquete->title)?>　についてのアンケート</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>開始日時</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$enquete->start_date_y?>年<?=$enquete->start_date_m?>月<?=$enquete->start_date_d?>日</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>終了日時</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$enquete->end_date_y?>年<?=$enquete->end_date_m?>月<?=$enquete->end_date_d?>日　<?=$enquete->end_date_h?>時</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>本アンケート目標回収数</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=$pro_enq->sample_num?>人</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center">条件設定内容</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>性別</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=decode_sex($search->sex, '<br>')?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>年代</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>未既婚</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=decode_mikikon($search->mikikon, '<br>')?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>居住地域</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_area($search->jitaku_area) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>職業</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>業種</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>職種</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_shokushu($search->shokushu) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>子供の有無</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>住居形態</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>車の所有</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=multi_decode(decode_have_car, $search->have_car)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>コンビニ</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_conveni($search->conveni) ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>スーパー</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_super($search->super) ?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=htmlspecialchars($profile_name)?></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>興味ジャンル</td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><? disp_genre($search->genre) ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<form method="post" action="pe_erased.php">
			<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
			<table width="768" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td width="50%" align="right">
						<input type="submit" value="　削除　">
					</td>
					<td width="50%" align="left">
						<input type="button" value="　戻る　" onclick="history.back()">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
