<?
/******************************************************
' System :�������ơ�net�ޡ��������ڡ���
' Content:���̴ؿ�
' note:		����ʬ���ˤ�롢�ǥ��쥯�ȹ����ѹ��Τ����ɲ�
' author:	wsaito
'******************************************************/

if (!isset($_SERVER['HTTPS'])) header("Location: https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

//-------------------------------------------------------
// ���ѥ�����marketer�ǥ��쥯�ȥ�ؤΡ����Хѥ��μ���
//-------------------------------------------------------
function getPathToMarketer(){
	//�������
	$TOP_DIR_NAME = 'marketer';
	
	
	//���ߤΥѥ������	
	$file_path = $_SERVER['SCRIPT_FILENAME'];
	
	// '/'���Ȥˡ�ʸ���������˳�Ǽ
	$a = split("/", $file_path);

	$top_path = "";
	
	$start = count($a)-2;	

	//���Хѥ��κ���
	for ($i=$start; $i >= 0; $i--){
		if ($a[$i] == $TOP_DIR_NAME)
			break;
		$top_path = $top_path . "../";
	}

	return $top_path;
}

?>
