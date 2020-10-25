<?php
/**
 * form�����ٱ�
 * 
 * @author Ryuji
 * @version $Id: HNbFormUtility.class.php,v 1.1 2006/06/09 07:16:19 ryu Exp $ 
 */


class HNbFormUtility 
{
	public function getYearOptions($start_year, $end_year, $first_option = '')
	{
		$ret = array();
		if($first_option){
			if(is_array($first_option)){
				$ret = $first_option;
			}else{
				$ret[0] = $first_option;
			}
		}
		if($start_year < $end_year){
			// ASC
			for($year = $start_year; $year <= $end_year; $year ++){
				$ret[$year] = sprintf("%4d��%s��%s", $year, self::getNengo($year), self::getEto($year));
			}
		}else{
			// DESC
			for($year = $start_year; $year >= $end_year; $year --){
				$ret[$year] = sprintf("%4d��%s��%s", $year, self::getNengo($year), self::getEto($year));
			}
		}
		return $ret;
		
	}
	private function getNengo($year)
	{
		$ret = '';
		if($year < 1913){
			//����
			$ret = '����';
			$ret .= $year - 1867;
		}elseif($year < 1927){
			//����
			$ret = '����';
			$ret .= $year - 1911;
		}elseif($year < 1990){
			//����
			$ret = '����';
			$ret .= $year - 1925;
		}else{
			//ʿ��
			$ret = 'ʿ��';
			$ret .= $year - 1988;
		}
		return $ret;
	}
	private function getEto($year)
	{
		$eto_list = array('��','��', '��', '��', 'ä', '̦', '��', '̤', '��', '��','��','��');
		$eto_number = (($year-1900)%12);
		$eto = $eto_list[$eto_number];
		return $eto;
	}
		
}
?>