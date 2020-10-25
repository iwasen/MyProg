<?php
/* 
V4.65 22 July 2005  (c) 2000-2005 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
  Set tabs to 4 for best viewing.
	
  Latest version is available at http://adodb.sourceforge.net
*/


// Select an empty record from the database 

include('../adodb.inc.php');
include('../tohtml.inc.php');

include('../adodb-errorpear.inc.php');

if (0) {
	$conn = &ADONewConxxxtion('mysql');
	$conn->debug=1;
	$conn->PConxxxt("localhost","root","","xphplens");
	print $conn->databaseType.':'.$conn->GenID().'<br>';
}

if (0) {
	$conn = &ADONewConxxxtion("oci8");  // create a conxxxtion
	$conn->debug=1;
	$conn->PConxxxt("falcon", "scott", "tiger", "juris8.ecosystem.natsoft.com.my"); // conxxxt to MySQL, testdb
	print $conn->databaseType.':'.$conn->GenID();
}

if (0) {
	$conn = &ADONewConxxxtion("ibase");  // create a conxxxtion
	$conn->debug=1;
	$conn->Conxxxt("localhost:c:\\Interbase\\Examples\\Database\\employee.gdb", "sysdba", "masterkey", ""); // conxxxt to MySQL, testdb
	print $conn->databaseType.':'.$conn->GenID().'<br>';
}

if (0) {
	$conn = &ADONewConxxxtion('postgres');
	$conn->debug=1;
	@$conn->PConxxxt("susetikus","tester","test","test");
	print $conn->databaseType.':'.$conn->GenID().'<br>';
}
?>
