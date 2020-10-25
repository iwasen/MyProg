<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜似顔絵関連マスタ｜画風マスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master_st', 'マスタ管理｜似顔絵関連マスタ', '画風マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT bw_brushwork_cd FROM m_brushwork WHERE bw_name='$bw_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(bw_brushwork_cd), 0) + 1) FROM m_brushwork";
		$sql = "INSERT INTO m_brushwork (bw_brushwork_cd, bw_name, bw_explain)"
			. " VALUES (($sub), '$bw_name', '$bw_explain')";
		db_exec($sql);
		$msg = '画風名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した画風名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT bw_brushwork_cd FROM m_brushwork WHERE bw_name='$bw_name' AND bw_brushwork_cd<>$bw_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_brushwork SET" .
					" bw_name=" . sql_char($bw_name) .
					", bw_explain=" . sql_char($bw_explain) .
					" WHERE bw_brushwork_cd=$bw_cd";
		db_exec($sql);
		$msg = '画風マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '画風名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "SELECT nd_brushwork FROM t_nigaoe_data WHERE nd_brushwork=$bw_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "DELETE FROM m_brushwork WHERE bw_brushwork_cd=$bw_cd";
		db_exec($sql);
		$msg = '画風名を削除しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '画風情報は使用されているため、削除できません。';
		$ret = 'history.back()';
	}
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
