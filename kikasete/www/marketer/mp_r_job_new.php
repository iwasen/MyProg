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
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
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
			<!-- my�ѡ��ȥʡ� -->

			<div align="center">
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td class="m0" colspan=2>�����󥱡��Ȥ�����</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100% bgcolor="#eff7e8">
							<tr>
								<td><input checked type="checkbox" name="check1" onclick="" >�»ܳ���</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%">��������</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">ǯ
									<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">��
									<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">��
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��λ����</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">ǯ
									<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">��
									<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">��
									<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">��
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�ݥ���ȿ�</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="point" size="3" maxlength="3" value="10">�ݥ����
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����ȯ����</td>
								<td bgcolor="#ffeecc">
									<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">��
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
								<td bgcolor="#ffeecc">
									<input class="kanji" type="text" name="title" size="50" maxlength="20" value="�֥���饯�����פ˴ؤ��륢�󥱡���">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1" bgcolor="#eff7e8">
						<input checked type="checkbox" name="check1" onclick="" >���������
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="3">�ѣ�</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">���ˤ����륭��饯�����Τ��������ʤ�����¸�ΤΥ���饯�����Ϥɤ�Ǥ������ΤäƤ��������Ƥ˥����å��򤷤Ƥ���������</textarea>
									<div class="small">�������λ���<br>
									<input type="radio" checked>�ʤ�<br>
									<input type="radio">URL����ꤹ�� ��<input type="text" name="" size="30"><br>
									<input type="radio">�����������ࡡ<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>ñ������(SA)
									<option value='2' selected>ʣ������(MA)
									<option value='4' >�ώĎ؎���(SA)
									<option value='5' >�ώĎ؎���(MA)
									<option value='6'>��ͳ����(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<strong>������20�Ĥޤ�)</strong><br>
									<textarea name="" cols="50" rows="10"></textarea>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="3">�ѣ�</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">�ʲ��β����Υ���饯�����Ϥ�¸�ΤǤ�����</textarea>
									<div class="small">�������λ���<br>
									<input type="radio" checked>�ʤ�<br>
									<input type="radio">URL����ꤹ�� ��<input type="text" name="" size="30"><br>
									<input type="radio">�����������ࡡ<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>ñ������(SA)
									<option value='2' >ʣ������(MA)
									<option value='4' >�ώĎ؎���(SA)
									<option value='5' selected>�ώĎ؎���(MA)
									<option value='6'>��ͳ����(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td width="50%"><strong>ɽ¦��10�Ĥޤǡ�</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
											<td width="50%"><strong>ɽƬ��10�Ĥޤǡ�</strong><br>
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
																	<td width="50%" align="center">ɽƬ</td>
																</tr>
																<tr>
																	<td align="center">ɽ¦</td>
																	<td>
																	</td>
																</tr>
															</table>
														</td>
														<td>
															<input type="button" name="" value="�ץ�ӥ塼" onclick="onclick_pre_matrix()">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�����ν�ʣ������ʤ�</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="button" value="��������ɲá�">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1">
						<table border=0 cellspacing=0 cellpadding=3 width=100% bgcolor="#eff7e8">
							<tr>
								<td><input checked type="checkbox" name="check1" onclick="" >��������</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<!--
				<tr>
					<td bgcolor="#eeeeee" width="15%">�оݥ�˥���</td>
					<td width="85%">
						<input type="radio" name="" value='1'>�ꥵ�����ѡ��ȥʡ��Τ�<br>
						<input type="radio" name="" value='2'>�ꥵ�����ѡ��ȥʡ�ͥ��<br>
						<input type="radio" name="" value='3'>�̾�Υ�˥���ͥ��<br>
						<input type="radio" name="" value='4'>�̾�Υ�˥����Τ�<br>
						<input type="radio" name="" value='5' checked>����
				</td>
				</tr>
-->
							<tr>
								<td bgcolor="#eeeeee" width="15%">����</td>
								<td width="85%" bgcolor="#ffeecc">
									<input type="checkbox" name="sex[]" value='1'>����&nbsp;
									<input type="checkbox" name="sex[]" value='2'>����
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">ǯ��</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='1'>������̤��</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='2'>��������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='3'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='4'>��������Ⱦ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='5'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='6'>��������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='7'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='8'>��������Ⱦ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='9'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='10'>��������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='11'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='12'>��������Ⱦ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='13'>�������Ⱦ</td>
											<td width='10%'><input type='checkbox' name='age_cd[]' value='14'>�����Ͱʾ�</td>
										</tr>
										<tr>
											<td colspan="2"> Ǥ�դ�ǯ��������ǽ�ʣͣ����󥱡��Ȥν�����ȿ��</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">̤����</td>
								<td bgcolor="#ffeecc">
									<input type="checkbox" name="mikikon[]" value='1'>�ȿ�&nbsp;
									<input type="checkbox" name="mikikon[]" value='2'>����
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�ｻ�ϰ�</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'>�̳�ƻ</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'>�Ŀ���</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'>��긩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'>�ܾ븩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'>���ĸ�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'>������</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'>ʡ�縩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'>��븩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'>���ڸ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'>���ϸ�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11'>��̸�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12'>���ո�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13'>�����</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14'>�����</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'>���㸩</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'>�ٻ���</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'>���</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'>ʡ�温</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'>������</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'>Ĺ�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'>���츩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'>�Ų���</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'>���θ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'>���Ÿ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'>���츩</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'>������</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'>�����</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'>ʼ�˸�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'>���ɸ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'>�²λ���</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'>Ļ�踩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'>�纬��</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'>������</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'>���縩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'>������</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'>���縩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'>���</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'>��ɲ��</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'>���θ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'>ʡ����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'>���츩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'>Ĺ�긩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'>���ܸ�</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'>��ʬ��</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'>�ܺ긩</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'>�����縩</td>
											<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'>���츩</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'>��Ұ�</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'>��ҷбġ����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'>��̳��</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'>���Ķ�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'>�ѡ��ȡ�����Х���</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'>���翦���۸�Ρ���ա���׻�����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'>���翦��SOHO���ǥ����ʡ������ꥨ����������</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'>����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'>����</td>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'>��ȼ��ء�����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'>̵��</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����ӥ�</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='1'>���֥󥤥�֥�</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='2'>������</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='3'>�ե��ߥ꡼�ޡ���</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='4'>���󥯥�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='5'>�ߥ˥��ȥå�</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='6'>am.pm.</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='7'>��ޥ���</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='8'>���������</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='conveni[]' value='9'>���꡼����</td>
											<td width='10%'><input type='checkbox' name='conveni[]' value='10'>����¾</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�����ѡ�</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='1'>���ȡ��衼���ɡ�</td>
											<td width='10%'><input type='checkbox' name='super[]' value='2'>��������</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='3'>���㥹��</td>
											<td width='10%'><input type='checkbox' name='super[]' value='4'>��ͧ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='5'>����</td>
											<td width='10%'><input type='checkbox' name='super[]' value='6'>�ޥ�����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='7'>�ޥ륨��</td>
											<td width='10%'><input type='checkbox' name='super[]' value='8'>��ޥ��ȥ�</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='super[]' value='9'>��ˡ�</td>
											<td width='10%'><input type='checkbox' name='super[]' value='10'>����¾</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��̣������</td>
								<td bgcolor="#ffeecc">
									<table border=0 width="100%">
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='1'>����ᡦ����</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='2'>����ʪ�ʤ���ʳ���</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='3'>����</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='4'>�ե��å����</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='5'>�ӥ��ͥ�</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='6'>��������ߡ�����ݸ���</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='7'>�ع������</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='8'>���󥿡��ƥ������</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='18'>ι�ԡ��쥸�㡼</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='9'>����ԥ塼��</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='10'>������</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='11'>���ݡ���</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='12'>��</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='13'>��ʡ��</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='14'>����</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='15'>����ƥꥢ</td>
										</tr>
										<tr>
											<td width='10%'><input type='checkbox' name='genre[]' value='16'>��ư�֡��Х���</td>
											<td width='10%'><input type='checkbox' name='genre[]' value='17'>�����ʡ�������</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</tr>

				<tr>
					<td class="m0" colspan=2 bgcolor="#eff7e8">���󥱡��ȣףţ²��̤ȥ᡼��ʸ�Υ������ޥ���</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">���󥱡��ȣףţ²��̤Υ������ޥ���</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>���ʤ����������Ƥ��鼫ưŪ�ˣףţ²��̤���ޤ���<br>
									<input type="radio" name="" value='2' checked>���롡�����ꤵ�줿�ףţ²��̤ˡ�������ä��������Ϥ���������Ӥ���������
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">�᡼��ʸ�Υ������ޥ���</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>���ʤ����������Ƥ��鼫ưŪ�˥᡼��ʸ����ޤ���<br>
									<input type="radio" name="" value='2' checked>���롡�����ꤵ�줿�᡼��ʸ�ˡ�������ä��������Ϥ���������Ӥ���������
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- �ơ��֥����� -->
			</table>


			<br>
			<input type="button" value="�ףţ²��̤μ�ư����" onclick="location.href='edit_web.php'">
			<input type="button" value="�᡼��ʸ�̤μ�ư����" onclick="location.href='edit_mail.php'">
			<input type="button" value="�ףţ¥ץ�ӥ塼" onclick="location.href='preveiw_web.php'">
			<input type="button" value="�᡼��ץ�ӥ塼" onclick="location.href='preveiw_mail.php'">
			<input type="button" value="����Ͽ��" onclick="">
			<input type="button" value="����롡" onclick="history.back()">

			<input type="hidden" name="next_action">
			<input type="hidden" name="marketer_id" value="1047">
			<input type="hidden" name="my_enq_no" value="2">
			</form>
			</div>
			<!-- my�ѡ��ȥʡ� -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
