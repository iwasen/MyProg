<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ͹���ֹ�ޥ��������ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");

// �ڡ����إå���������
$header = new header_class('͹���ֹ�ޥ�������', BACK_TOP);

if ($_FILES['zip_csv']['tmp_name']) {
	// ����¹Ի��֤�10ʬ������
	set_time_limit(600);

	// ������®�����뤿���ö����ǥå�������
	//db_exec("DROP INDEX idx_zip_zip_cd");
	db_exec("DROP INDEX idx_zip_todoufuken");
	db_exec("DROP INDEX idx_zip_shikuchouson");
	db_exec("DROP INDEX idx_zip_zip_uniq");

	db_begin_trans();

	// ��¸�Υǡ�������
	db_exec("DELETE FROM m_zip");

	// CSV����m_zip��ž��
	$handle = fopen($_FILES['zip_csv']['tmp_name'], 'r');
	$seq_no = 1;
	while ($csv = fgetcsv($handle, 1000, ',')) {
		mb_convert_variables('EUC-JP', 'SJIS', $csv);

		$sql = "INSERT INTO m_zip (zip_seq_no, zip_zip_cd, zip_todoufuken, zip_shikuchouson, zip_chouiki)"
				. " VALUES ($seq_no, '{$csv[2]}', '{$csv[6]}', '{$csv[7]}', '{$csv[8]}')";
		db_exec($sql);

		$seq_no++;
	}

	// ��ˡ�������������
	$sql = "UPDATE m_zip SET zip_zip_uniq = zip_zip_cd WHERE zip_seq_no IN (SELECT MIN(zip_seq_no) FROM m_zip GROUP BY zip_zip_cd);";
	db_exec($sql);

	db_commit_trans();

	db_vacuum('m_zip', true);

	// ����ǥå��������
	//db_exec("CREATE INDEX idx_zip_zip_cd ON m_zip USING btree (zip_zip_cd)");
	db_exec("CREATE INDEX idx_zip_todoufuken ON m_zip USING btree (zip_todoufuken)");
	db_exec("CREATE INDEX idx_zip_shikuchouson ON m_zip USING btree (zip_shikuchouson)");
	db_exec("CREATE INDEX idx_zip_zip_uniq ON m_zip USING btree (zip_zip_uniq)");
} else
	redirect('upload.php');
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<p class="msg">͹���ֹ�ޥ����򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='../menu.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
