<?php
/**
 * HNbのメンバー
 */

 class HNbMember
 {
	 public $member_id = 0;
	 public $mail = '';
	 public $pass = '';
	 public $regist_date = 0;
	 public $update_date = 0;
	 //public $resign_date = 0;
	 
	 
 }
 
/**
 * 退会メンバー
 */
 class HNbResignMember extends HNbMember
 {
	 public $resign_date = 0;
	 
 }
?>