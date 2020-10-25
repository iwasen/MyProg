<?
/******************************************************
' System :きかせて・netマーケターページ
' Content:共通関数
' note:		階層分けによる、ディレクト構成変更のため追加
' author:	wsaito
'******************************************************/

if (!isset($_SERVER['HTTPS'])) header("Location: https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

//-------------------------------------------------------
// 現パスからmarketerディレクトリへの、相対パスの取得
//-------------------------------------------------------
function getPathToMarketer(){
	//初期処理
	$TOP_DIR_NAME = 'marketer';
	
	
	//現在のパスを取得	
	$file_path = $_SERVER['SCRIPT_FILENAME'];
	
	// '/'ごとに、文字列を配列に格納
	$a = split("/", $file_path);

	$top_path = "";
	
	$start = count($a)-2;	

	//相対パスの作成
	for ($i=$start; $i >= 0; $i--){
		if ($a[$i] == $TOP_DIR_NAME)
			break;
		$top_path = $top_path . "../";
	}

	return $top_path;
}

?>
