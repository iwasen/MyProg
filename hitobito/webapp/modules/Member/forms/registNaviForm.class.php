<?php
/**
 * @package	Member
 * @author Ryuji
 * @version $Id: registNaviForm.class.php,v 1.5 2006/06/06 15:56:03 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
 

class MemberModifyProfileForm extends HNbSemiAutoActionForm
{
	public
			$member_email,
			$member_password,
			
			$member_navi_name1,
			$member_navi_name2,
			$member_navi_name1_kana,
			$member_navi_name2_kana,
			$member_navi_sex,
			$member_navi_birth_year,
			$member_navi_birth_month,
			$member_navi_birth_day,
			$member_navi_tel,
			$member_navi_zip1,
			$member_navi_zip2,
			$member_navi_address1,
			$member_navi_address2,
			$member_navi_address3,
			$member_navi_shokugyo,

    $member_navi_forward_id,
    $member_navi_forward_flag,
    
			$naviId
			;
    /**
     * 文字列パラメータキーの配列を返す
     */
    protected function getStringParameterKeys(){
		$strParamsKey = array(
			'member_navi_name1',
			'member_navi_name2',
			'member_navi_name1_kana',
			'member_navi_name2_kana',
			'member_navi_tel',
			'member_navi_zip1',
			'member_navi_zip2',
			'member_navi_address2',
			'member_navi_address3'
		);
        if(!$this->naviId){
            $strParamsKey[] = 'member_navi_forward_id';
        }
		return $strParamsKey;
        
    }
    /**
     * 整数パラメータキーの配列を返す
     */
    protected function getIntegerParameterKeys(){
		$intParamsKey = array(
			'member_navi_sex',
			'member_navi_birth_year',
			'member_navi_birth_month',
			'member_navi_birth_day',
			'member_navi_address1',
			'member_navi_shokugyo',
		);
        if($this->naviId){
			$intParamsKey[] ='member_navi_forward_flag';
        }
		return $intParamsKey;
    }
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
     */
    protected function getRequireParameterKeys(){
		$require_params = array(
			'member_navi_name1' =>  HNb::tr('名前が未入力です。'),
			'member_navi_name2' =>  HNb::tr('名前が未入力です。'),
			'member_navi_name1_kana' =>  HNb::tr('フリガナが未入力です。'),
			'member_navi_name2_kana' =>  HNb::tr('フリガナが未入力です。'),
			'member_navi_tel' =>  HNb::tr('電話番号が未入力です。'),
			'member_navi_zip1' =>  HNb::tr('郵便番号が未入力です。'),
			'member_navi_zip2' =>  HNb::tr('郵便番号が未入力です。'),
			'member_navi_address2' =>  HNb::tr('住所が未入力です。'),
			'member_navi_sex' =>  HNb::tr('性別が未選択です。'),
			'member_navi_birth_year' =>  HNb::tr('誕生日が未入力です。'),
			'member_navi_birth_month' =>  HNb::tr('誕生日が未入力です。'),
			'member_navi_birth_day' =>  HNb::tr('誕生日が未入力です。'),
			'member_navi_address1' =>  HNb::tr('都道府県が未選択です。'),
			'member_navi_shokugyo' => HNb::tr('職業が未選択です。'),

		);
        if(!$this->naviId){
            $require_params['member_navi_forward_id'] =  HNb::tr('ナビアドレスが未入力です。');
        }
		return $require_params;
    }
			
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$validator = new HNbValidator();
		if($this->naviId > 0){
            // 編集時は転送フラグを1 or 0にする。
            $this->member_navi_forward_flag = ($this->member_navi_forward_flag) ? 1 : 0;
        }else{
            // ナビアドレス妥当性検査
            if(!$validator->validateEmail($this->member_navi_forward_id.'@navi.hitobito.net')){
                $this->addError(HNb::tr('ナビアドレスが有効ではありません。'));
            }else{
                //重複チェック
            }
        }
		
	}
	public function load($master, $requset)
	{
		$this->naviId = $master->getAttribute('nav_navi_id');
		$this->member_navi_name1 = $master->getAttribute('nav_name1');
		$this->member_navi_name2 = $master->getAttribute('nav_name2');
		$this->member_navi_name1_kana = $master->getAttribute('nav_name1_kana');
		$this->member_navi_name2_kana = $master->getAttribute('nav_name2_kana');
		$this->member_navi_sex = $master->getAttribute('nav_sex_cd');
		list($this->member_navi_birth_year, $this->member_navi_birth_month, $this->member_navi_birth_day)
			= explode('-', $master->getAttribute('nav_birthday'));
		$this->member_navi_zip1 = substr($master->getAttribute('nav_zip_cd'), 0 ,3);
		$this->member_navi_zip2 = substr($master->getAttribute('nav_zip_cd'), 3 ,4);
		$this->member_navi_address1 = $master->getAttribute('nav_address1');
		$this->member_navi_address2 = $master->getAttribute('nav_address2');
		$this->member_navi_address3 = $master->getAttribute('nav_address3');
		$this->member_navi_shokugyo = $master->getAttribute('nav_shokugyo_id');
		$this->member_navi_tel = $master->getAttribute('nav_jitaku_tel');
		$this->member_navi_forward_flag = $master->getAttribute('nav_forward_flag');
	}
    
	public function update($master, $request)
	{
		
		$master->setAttribute('nav_name1', $this->member_navi_name1);
		$master->setAttribute('nav_name2', $this->member_navi_name2);
		$master->setAttribute('nav_name1_kana', $this->member_navi_name1_kana);
		$master->setAttribute('nav_name2_kana', $this->member_navi_name2_kana);
		$master->setAttribute('nav_sex_cd', $this->member_navi_sex);
		$master->setAttribute('nav_birthday', $this->member_navi_birth_year.'-'.
												$this->member_navi_birth_month.'-'.
												$this->member_navi_birth_day);
		$master->setAttribute('nav_zip_cd', $this->member_navi_zip1.$this->member_navi_zip2);
		$master->setAttribute('nav_address1', $this->member_navi_address1);
		$master->setAttribute('nav_address2', $this->member_navi_address2);
		$master->setAttribute('nav_address3', $this->member_navi_address3);
		$master->setAttribute('nav_shokugyo_id', $this->member_navi_shokugyo);
		$master->setAttribute('nav_jitaku_tel', $this->member_navi_tel);
		
		if($this->naviId){
			$master->setAttribute('nav_forward_flag', $this->member_navi_forward_flag);
		}else{
			$master->setAttribute('nav_forward_id', $this->member_navi_forward_id);
		}
	}	
}
/**
  * ナビページ申請アクションフォーム
  */
class MemberRegistNaviPageForm extends MemberModifyProfileForm
{

	public
			$member_navipage_category,
			$member_navipage_title,
			$member_navipage_description
			;
    /**
     * 文字列パラメータキーの配列を返す
     */
    protected function getStringParameterKeys(){
		$strParamsKey = array(
			'member_navipage_title',
			'member_navipage_description',
		);
        $ret = array_merge($strParamsKey, parent::getStringParameterKeys());
		return $ret;
    }
    /**
     * 整数パラメータキーの配列を返す
     */
    protected function getIntegerParameterKeys(){
		$intParamsKey = array(
			'member_navipage_category',
		);
        $ret = array_merge($intParamsKey, parent::getIntegerParameterKeys());
		return $ret;
    }
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
     */
    protected function getRequireParameterKeys(){
		$require_params = array(
			'member_navipage_title' => HNb::tr('タイトルが未入力です。'), 
			'member_navipage_description' => HNb::tr('概要が未入力です。'),
			'member_navipage_category' =>  HNb::tr('応募カテゴリが未選択です。'),
		);
        $ret = array_merge($require_params, parent::getRequireParameterKeys());
		return $ret;
    }

	public function update($master, $request)
	{
		parent::update($master, $request);
		$master->setAttribute('nvp_channel_id', $this->member_navipage_category);
		$master->setAttribute('nvp_title', $this->member_navipage_title);
		$master->setAttribute('nvp_outline', $this->member_navipage_description);
	}
}


?>