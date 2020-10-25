<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_hitobito_letter_episode.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_hitobito_letter_episodeObject extends HNbValueObject
{
	protected $pkey = 'hle_seq_no';
	protected $table = 't_hitobito_letter_episode';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['hle_seq_no'] = 0 ;
				$this->attribute['hle_letter_id'] = 0 ;
				$this->attribute['hle_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['hle_nickname'] = "" ;
				$this->attribute['hle_episode'] = "" ;
			}
}

class t_hitobito_letter_episodeObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'hle_seq_no';
	protected $table = 't_hitobito_letter_episode';
	protected $class = 't_hitobito_letter_episodeObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>