<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");

// ���å����λ
session_start();

session_destroy();

// �����åեȥåץڡ�����
redirect(http_url('index.php'));
?>