<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

?>
<? marketer_header('Myパートナー', PG_NULL) ?>
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
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td class="m0" colspan=2>■アンケートの設定</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100% bgcolor="#eff7e8">
							<tr>
								<td><input checked type="checkbox" name="check1" onclick="" >実施概要</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%">開始日時</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">年
									<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">月
									<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">日
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">終了日時</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">年
									<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">月
									<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">日
									<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">時
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">ポイント数</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="point" size="3" maxlength="3" value="10">ポイント
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">最大発信数</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">人
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">アンケートタイトル</td>
								<td bgcolor="#ffeecc">
									<input class="kanji" type="text" name="title" size="50" maxlength="20" value="「キャラクター」に関するアンケート">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1" bgcolor="#eff7e8">
						<input checked type="checkbox" name="check1" onclick="" >設問の設定
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="3">Ｑ１</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">次にあげるキャラクターのうち、あなたがご存知のキャラクターはどれですか？知っているもの全てにチェックをしてください。</textarea>
									<div class="small">■画像の指定<br>
									<input type="radio" checked>なし<br>
									<input type="radio">URLを指定する 　<input type="text" name="" size="30"><br>
									<input type="radio">画像を埋め込む　<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>単一選択(SA)
									<option value='2' selected>複数選択(MA)
									<option value='4' >ﾏﾄﾘｸｽ(SA)
									<option value='5' >ﾏﾄﾘｸｽ(MA)
									<option value='6'>自由回答(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<strong>選択肢（20個まで)</strong><br>
									<textarea name="" cols="50" rows="10"></textarea>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="3">Ｑ２</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">以下の画像のキャラクターはご存知ですか？</textarea>
									<div class="small">■画像の指定<br>
									<input type="radio" checked>なし<br>
									<input type="radio">URLを指定する 　<input type="text" name="" size="30"><br>
									<input type="radio">画像を埋め込む　<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>単一選択(SA)
									<option value='2' >複数選択(MA)
									<option value='4' >ﾏﾄﾘｸｽ(SA)
									<option value='5' selected>ﾏﾄﾘｸｽ(MA)
									<option value='6'>自由回答(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td width="50%"><strong>表側（10個まで）</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
											<td width="50%"><strong>表頭（10個まで）</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
										</tr>
										<tr>
											<td align="left">
												<table>
													<tr>
														<td>
															<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
																<tr>
																	<td width="50%" align="center"></td>
																	<td width="50%" align="center">表頭</td>
																</tr>
																<tr>
																	<td align="center">表側</td>
																	<td>
																	</td>
																</tr>
															</table>
														</td>
														<td>
															<input type="button" name="" value="プレビュー" onclick="onclick_pre_matrix()">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>回答の重複を許さない</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="button" value="　質問を追加　">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1">
						<table border=0 cellspacing=0 cellpadding=3 width=100% bgcolor="#eff7e8">
							<tr>
								<td><input checked type="checkbox" name="check1" onclick="" >条件の設定</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<!--
				<tr>
					<td bgcolor="#eeeeee" width="15%">対象モニター</td>
					<td width="85%">
						<input type="radio" name="" value='1'>リサーチパートナーのみ<br>
						<input type="radio" name="" value='2'>リサーチパートナー優先<br>
						<input type="radio" name="" value='3'>通常のモニター優先<br>
						<input type="radio" name="" value='4'>通常のモニターのみ<br>
						<input type="radio" name="" value='5' checked>全て
				</td>
				</tr>
-->
							<tr>
								<td bgcolor="#eeeeee" width="15%">性別</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="checkbox" name="sex[]" value='1'>男性&nbsp;
									<input type="checkbox" name="sex[]" value='2'>女性
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">年代</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='1'>１０才未満</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='2'>１０代前半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='3'>１０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='4'>２０代前半</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='5'>２０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='6'>３０代前半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='7'>３０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='8'>４０代前半</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='9'>４０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='10'>５０代前半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='11'>５０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='12'>６０代前半</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='13'>６０代後半</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='14'>７０才以上</td>
										</tr>
										<tr>
											<td colspan="2"> 任意の年代設定も可能（Ｍｙアンケートの修正を反映</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">未既婚</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="mikikon[]" value='1'>独身&nbsp;
									<input type="checkbox" name="mikikon[]" value='2'>既婚
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">居住地域</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'>北海道</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'>青森県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'>岩手県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'>宮城県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'>秋田県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'>山形県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'>福島県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'>茨城県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'>栃木県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'>群馬県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11'>埼玉県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12'>千葉県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13'>東京都</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14'>神奈川県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'>新潟県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'>富山県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'>石川県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'>福井県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'>山梨県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'>長野県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'>岐阜県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'>静岡県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'>愛知県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'>三重県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'>滋賀県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'>京都府</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'>大阪府</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'>兵庫県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'>奈良県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'>和歌山県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'>鳥取県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'>島根県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'>岡山県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'>広島県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'>山口県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'>徳島県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'>香川県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'>愛媛県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'>高知県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'>福岡県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'>佐賀県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'>長崎県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'>熊本県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'>大分県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'>宮崎県</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'>鹿児島県</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'>沖縄県</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">職業</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'>会社員</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'>会社経営・役員</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'>公務員</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'>自営業</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'>パート・アルバイト</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'>専門職（弁護士・医師・会計士等）</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'>専門職（SOHO・デザイナー・クリエイター等）</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'>教職</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'>学生</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'>専業主婦・主夫</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'>無職</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">コンビニ</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='1'>セブンイレブン</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='2'>ローソン</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='3'>ファミリーマート</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='4'>サンクス</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='5'>ミニストップ</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='6'>am.pm.</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='7'>ヤマザキ</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='8'>サークルＫ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='9'>スリーエフ</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='10'>その他</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">スーパー</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='1'>イトーヨーカドー</td>
											<td width='10%'><input type='checkbox' name='super[]' value='2'>ダイエー</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='3'>ジャスコ</td>
											<td width='10%'><input type='checkbox' name='super[]' value='4'>西友</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='5'>生協</td>
											<td width='10%'><input type='checkbox' name='super[]' value='6'>マイカル</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='7'>マルエツ</td>
											<td width='10%'><input type='checkbox' name='super[]' value='8'>東急ストア</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='9'>ユニー</td>
											<td width='10%'><input type='checkbox' name='super[]' value='10'>その他</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">興味ジャンル</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='1'>グルメ・料理</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='2'>飲み物（お酒以外）</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='3'>お酒</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='4'>ファッション</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='5'>ビジネス</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='6'>お金（貯蓄・投資・保険）</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='7'>学校・資格</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='8'>エンターテインメント</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='18'>旅行・レジャー</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='9'>コンピュータ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='10'>アート</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='11'>スポーツ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='12'>健康</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='13'>介護・福祉</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='14'>住宅</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='15'>インテリア</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='16'>自動車・バイク</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='17'>新製品・新商品</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</tr>

				<tr>
					<td class="m0" colspan=2 bgcolor="#eff7e8">アンケートＷＥＢ画面とメール文のカスタマイズ</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">アンケートＷＥＢ画面のカスタマイズ</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>しない：設定内容から自動的にＷＥＢ画面を作ります。<br>
									<input type="radio" name="" value='2' checked>する　：設定されたＷＥＢ画面に、修正を加えたい場合はこちらをお選びください。
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">メール文のカスタマイズ</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>しない：設定内容から自動的にメール文を作ります。<br>
									<input type="radio" name="" value='2' checked>する　：設定されたメール文に、修正を加えたい場合はこちらをお選びください。
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- テーブル大枠 -->
			</table>


			<br>
			<input type="button" value="ＷＥＢ画面の手動修正" onclick="location.href='edit_web.php'">
			<input type="button" value="メール文面の手動修正" onclick="location.href='edit_mail.php'">
			<input type="button" value="ＷＥＢプレビュー" onclick="location.href='preveiw_web.php'">
			<input type="button" value="メールプレビュー" onclick="location.href='preveiw_mail.php'">
			<input type="button" value="　登録　" onclick="">
			<input type="button" value="　戻る　" onclick="history.back()">

			<input type="hidden" name="next_action">
			<input type="hidden" name="marketer_id" value="1047">
			<input type="hidden" name="my_enq_no" value="2">
			</form>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
