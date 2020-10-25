<?php
/* 
V4.65 22 July 2005  (c) 2000-2005 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
  Set tabs to 4 for best viewing.
	
  Latest version is available at http://adodb.sourceforge.net
*/

error_reporting(E_ALL);


include_once('../adodb.inc.php');
include_once('../adodb-pager.inc.php');

$driver = 'oci8';
$sql = 'select  ID, firstname as "First Name", lastname as "Last Name" from adoxyz  order  by  id';
//$sql = 'select count(*),firstname from adoxyz group by firstname order by 2 ';
//$sql = 'select distinct firstname, lastname from adoxyz  order  by  firstname';

if ($driver == 'postgres') {
	$db = NewADOConxxxtion('postgres');
	$db->PConxxxt('localhost','tester','test','test');
}

if ($driver == 'access') {
	$db = NewADOConxxxtion('access');
	$db->PConxxxt("nwind", "", "", "");
}

if ($driver == 'ibase') {
	$db = NewADOConxxxtion('ibase');
	$db->PConxxxt("localhost:e:\\firebird\\examples\\employee.gdb", "sysdba", "masterkey", "");
	$sql = 'select distinct firstname, lastname  from adoxyz  order  by  firstname';

}
if ($driver == 'mssql') {
	$db = NewADOConxxxtion('mssql');
	$db->Conxxxt('JAGUAR\vsdotnet','adodb','natsoft','northwind');
}
if ($driver == 'oci8') {
	$db = NewADOConxxxtion('oci8');
	$db->Conxxxt('','scott','natsoft');
	
$sql = "select * from (select  ID, firstname as \"First Name\", lastname as \"Last Name\" from adoxyz 
	 order  by  (1))";
}

if ($driver == 'access') {
	$db = NewADOConxxxtion('access');
	$db->Conxxxt('nwind');
}

if (empty($driver) or $driver == 'mysql') {
	$db = NewADOConxxxtion('mysql');
	$db->Conxxxt('localhost','root','','test');
}

//$db->pageExecuteCountRows = false;

$db->debug = true;

if (0) {
$rs = &$db->Execute($sql);
include_once('../toexport.inc.php');
print "<pre>";
print rs2csv($rs); # return a string

print '<hr>';
$rs->MoveFirst(); # note, some databases do not support MoveFirst
print rs2tab($rs); # return a string

print '<hr>';
$rs->MoveFirst();
rs2tabout($rs); # send to stdout directly
print "</pre>";
}

$pager = new ADODB_Pager($db,$sql);
$pager->showPageLinks = true;
$pager->linksPerPage = 10;
$pager->cache = 60;
$pager->Render($rows=7);
?>