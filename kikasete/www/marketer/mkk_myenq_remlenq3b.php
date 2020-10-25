<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
//include("$inc/mkk_mypage.php");
session_start();
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

$answer_ary = &$_SESSION['ss_answer'];
$back = '';

// ���󥱡���ID¸�ߥ����å�
if ($enquete_id == '')
	$msg[] = '���󥱡��Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';

// ���󥱡���ͭ���������å�
if (!$msg) {
	$sql = "SELECT en_point FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$point = $fetch->en_point;
	} else
		$msg[] = '���򤵤줿���󥱡��Ȥ�ͭ���ǤϤ���ޤ���';
}

$enquete = new enquete_class;
$enquete->read_db($enquete_id);

if (!$msg) {
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno >= $first_qno && $qno <= $last_qno) {
				// ɬ�ܲ��������å�
				if ($question->must_flag == 't') {
					switch ($question->question_type) {
					case 1:		// ñ�������
					case 2:		// ʣ�������
					case 6:		// ���̲���
					case 7:		// �ץ������
						if ($_POST["answer$qno"] == '')
							$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
						break;
					case 3:		// �ե꡼����
					case 8:		// �ե꡼�����ʣ��ԡ�
						if (trim(str_replace('��', '', $_POST["answer$qno"])) == '')
							$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
						break;
					case 4:		// �ޥȥꥯ��(SA)
					case 5:		// �ޥȥꥯ��(MA)
						$sno_ary = array();
						if (is_array($_POST["answer$qno"])) {
							foreach ($_POST["answer$qno"] as $no) {
								$ary = explode(':', $no);
								$sno_ary[] = $ary[0];
							}
						}
						foreach (array_keys($question->hyousoku) as $sno) {
							if (!in_array($sno, $sno_ary))
								$msg[] = "��{$qno}��{$sno}���ܤβ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
						}
						break;
					}
				}

				// ñ�������,ʣ�������Υե꡼���������å�
				if ($question->fa_sno) {
					switch ($question->question_type) {
					case 1:		// ñ�������
						if ($_POST["answer$qno"] == $question->fa_sno) {
							if (trim(str_replace('��', '', $_POST["free_answer$qno"])) == '')
								$msg[] = "��{$qno}�Υե꡼���������Ϥ���Ƥ��ʤ��褦�Ǥ���";
						}
						break;
					case 2:		// ʣ�������
						if (is_array($_POST["answer$qno"])) {
							foreach ($_POST["answer$qno"] as $answer) {
								if ($answer == $question->fa_sno) {
									if (trim(str_replace('��', '', $_POST["free_answer$qno"])) == '')
										$msg[] = "��{$qno}�Υե꡼���������Ϥ���Ƥ��ʤ��褦�Ǥ���";
								}
							}
						}
						break;
					}
				}

				// ��¾��������å�
				if ($question->ex_sno != 0 && $question->question_type == 2) {
					if (count($_POST["answer$qno"]) > 1 && in_array($question->ex_sno, $_POST["answer$qno"]))
							$msg[] = "��{$qno}�ǡ�{$question->sel_text[$question->ex_sno]}�פ����򤵤줿���Ϥ���¾������ϤǤ��ޤ���";
				}

				// ���̲����ο��ͥ����å�
				if ($question->question_type == 6) {
					$len = strlen($_POST["answer$qno"]);
					for ($i = 0; $i < $len; $i++) {
						$c = $_POST["answer$qno"][$i];
						if (!(($c == '-' && $i == 0) || ($c >= '0' && $c <= '9') || $c == '.')) {
							$msg[] = "��{$qno}�Ͽ��������Ϥ��Ƥ���������";
							break;
						}
					}
				}
			}
		}
	}
}

// �ޥȥꥯ���ν�ʣ���������å�
if (!$msg) {
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno >= $first_qno && $qno <= $last_qno) {
				if ($question->question_type == 4 || $question->question_type == 5) {
					if ($question->dup_flag == 't') {
						if (is_array($_POST["answer$qno"])) {
							$tno = array();
							foreach ($_POST["answer$qno"] as $no) {
								$ary = explode(':', $no);
								$tno[] = $ary[1];
							}
							if (count($tno) != count(array_unique($tno)))
								$msg[] = "��{$qno}�β����˽�ʣ������褦�Ǥ���";
						}
					}
				}
			}
		}
	}

	// �˥å��͡�������ϥ����å�
	if ($enquete->enq_kind == 4) {
		if (isset($nickname) && $nickname == '')
			$msg[] = '�˥å��͡��ब���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	}
}

if (!$msg) {
	// ���󥱡������Ƽ���
	for ($qno = $first_qno; $qno <= $last_qno; $qno++) {
		$question = &$enquete->question[$qno];
		unset($answer_ary[$qno]);

		switch ($enquete->question[$qno]->question_type) {
		case 1:		// ñ�������
		case 7:		// �ץ������
			$answer = $_POST["answer$qno"];
			if ($answer != '')
				$answer_ary[$qno][$answer] = ($answer == $question->fa_sno) ? $_POST["free_answer$qno"] : '';
			break;
		case 2:		// ʣ�������
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer)
					$answer_ary[$qno][$answer] = ($answer == $question->fa_sno) ? $_POST["free_answer$qno"] : '';
			}
			break;
		case 3:		// �ե꡼����
		case 6:		// ���̲���(NA)
		case 8:		// �ե꡼�����ʣ��ԡ�
			$answer = $_POST["answer$qno"];
			if ($answer != '')
				$answer_ary[$qno][0] = $answer;
			break;
		case 4:		// �ޥȥꥯ��(SA)
		case 5:		// �ޥȥꥯ��(MA)
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer)
					$answer_ary[$qno][$answer] = '';
			}
			break;
		}
	}
	unset($question);

	// ʬ���������å�
	$next_qno = 0;
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($qno <= $last_qno)
				continue;

			$branch_cond = &$enquete->branch_cond[$qno];

			if (isset($branch_cond)) {
				$ok = ($branch_cond->and_or == 'A') ? true : false;

				foreach ($branch_cond->cond as $cond) {
					$select_no_ary = explode(',', $cond->select_no);
					$match_count = 0;
					$unmatch_count = 0;

					if (isset($answer_ary[$cond->cond_question_no])) {
						foreach (array_keys($answer_ary[$cond->cond_question_no]) as $sno) {
							if (in_array($sno, $select_no_ary))
								$match_count++;
							else
								$unmatch_count++;
						}
					}

					$select_count = count($select_no_ary);

					switch ($cond->and_or) {
					case 'A':
						$ok2 = ($match_count == $select_count);
						break;
					case 'O':
						$ok2 = ($match_count >= 1);
						break;
					case 'F':
						$ok2 = ($match_count == $select_count && $unmatch_count == 0);
						break;
					}

					if ($cond->not_cond == DBTRUE)
						$ok2 = !$ok2;

					if ($ok2) {
						if ($branch_cond->and_or == 'O') {
							$ok = true;
							break;
						}
					} else {
						if ($branch_cond->and_or == 'A') {
							$ok = false;
							break;
						}
					}
				}

				if ($ok) {
					$next_qno = $qno;
					break;
				}
			} else {
				$next_qno = $qno;
				break;
			}
		}
	}

	// ���ڡ���������в����ڡ��������
	if ($next_qno)
		redirect("mkk_myenq_remlenq3a.php?enquete_id=$enquete_id&nqno=$next_qno&my_enq_no=$my_enq_no");

	if (is_array($answer_ary) && count($answer_ary) > 0) {
	} else {
		$msg[] = '�������ҤȤĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$back = "mkk_myenq_remlenq3a.php?enquete_id=$enquete_id&my_enq_no=$my_enq_no";
	}
}

unset($answer_ary);
?>
<?
if ($_SESSION['ss_marketer_id'])
	marketer_header('�ͣ����󥱡���web�ץ�ӥ塼', PG_NULL);
else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
<meta http-equiv="Pragma" content="no-cache">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="js/w_open.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>
</head>
<body>
<?
}
?>
<!-- �����Τ����ߥơ��֥� -->
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<?
if (!$msg) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="white">
	<tr>
		<td width="15"><img src="images/common/spacer.gif" width="1" height="1" border="0"></td>
		<td colspan="2"><table border="0" cellpadding="20" cellspacing="0" width="100%">
				<tr>
					<td><span style="color:#2b2b63;">���ͣ����󥱡���ư���ǧ<br><br>

����ϡ���<?=htmlspecialchars($enquete->title)?>�פˤĤ��ƤΥ��󥱡��Ȥˤ������������������꤬�Ȥ��������ޤ�����</span> <br>
						<br>


						<p align="center"><a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="�Ĥ���" name="Image19" width="108" height="31" border="0"></a></p>
					</td>
				</tr>
			</table></td>
	</tr>
</table>
<?
} else
	error_msg($msg, $back);
?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<!-- ��������� -->

<? marketer_footer() ?>

