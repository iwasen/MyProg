<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 郵便番号マスタ更新ページ
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

// ページヘッダ情報設定
$header = new header_class('郵便番号マスタ更新', BACK_TOP);

if ($_FILES['zip_csv']['tmp_name']) {
	// 最大実行時間を10分に設定
	set_time_limit(600);

	// 処理を速くするため一旦インデックスを削除
	//db_exec("DROP INDEX idx_zip_zip_cd");
	db_exec("DROP INDEX idx_zip_todoufuken");
	db_exec("DROP INDEX idx_zip_shikuchouson");
	db_exec("DROP INDEX idx_zip_zip_uniq");

	db_begin_trans();

	// 既存のデータを削除
	db_exec("DELETE FROM m_zip");

	// CSVからm_zipに転送
	$handle = fopen($_FILES['zip_csv']['tmp_name'], 'r');
	$seq_no = 1;
	while ($csv = fgetcsv($handle, 1000, ',')) {
		mb_convert_variables('EUC-JP', 'SJIS', $csv);

		$sql = "INSERT INTO m_zip (zip_seq_no, zip_zip_cd, zip_todoufuken, zip_shikuchouson, zip_chouiki)"
				. " VALUES ($seq_no, '{$csv[2]}', '{$csv[6]}', '{$csv[7]}', '{$csv[8]}')";
		db_exec($sql);

		$seq_no++;
	}

	// ユニークキーを設定
	$sql = "UPDATE m_zip SET zip_zip_uniq = zip_zip_cd WHERE zip_seq_no IN (SELECT MIN(zip_seq_no) FROM m_zip GROUP BY zip_zip_cd);";
	db_exec($sql);

	db_commit_trans();

	db_vacuum('m_zip', true);

	// インデックスを作成
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

<p class="msg">郵便番号マスタを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../menu.php'"></p>

<?php $header->page_footer(); ?>

</body>
</html>
