<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:��˥����ץ�ե����뽸�׽���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/profile.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// �ץ�ե����뽸�׽���
monitor_profile();

batch_log(0, FILE_NAME, "��˥����ץ�ե������������ޤ�����");
?>