<?php
/**
 * 
 * @package HNb
 * @author Ryuji
 * @version $Id: HNbTrackBack.class.php,v 1.3 2005/12/26 23:15:33 ryu Exp $
 */
error_reporting(0);
require_once MO_WEBAPP_DIR .'/lib/snoopy/Snoopy.class.php';
error_reporting(MO_ERROR_REPORTING);
class HNbTrackBack {
	public
     $title,
     $excerpt,
     $url,
     $blog_name;
    
    private $internalEncoding = 'EUC-JP';
    
    /**
     * 対象URLからトラックバック送信先URLを探す
     * 見つからなかったら$urlをそのまま返す。
     */
    public function searchTrackBackUrl($url){
        $snoopy = new Snoopy;
        $snoopy->fetch($url);
        $content = $snoopy->results;
        $chrset = mb_detect_encoding($content, "auto");
        $content = mb_convert_encoding($content, "EUC-JP", $chrset);
        if ( preg_match('/(<rdf:RDF.*?<\/rdf:RDF>)/ism', $content, $array) )
        {    
            $rdf = trim($array[1]);
        } 

        if (preg_match('/trackback:ping="([^"]+)"/', $rdf, $array))
        {
            $ret = trim($array[1]);
            return $ret;
        }
        return $url;        
        
    }
    
    /**
     * トラックバック発射
     * 
     * 事前に $title, $excerpt, $url, $blog_nameをセットすること
     * @param string $url
     * @return boolian 成功したらTRUE;
     */
    public function sendTrackBack($url){
    	$validator = new HNbValidator();
    	if(!$validator->validateUrl($url)){
    		return FALSE;
    	}
    	
        $snoopy = new Snoopy;
        $vars = array("title" => mb_convert_encoding($this->title, "UTF-8", $this->internalEncoding),
                        "excerpt" => mb_convert_encoding($this->excerpt, "UTF-8", $this->internalEncoding),
                        "url" => $this->url,
                        "blog_name" => mb_convert_encoding($this->blog_name, "UTF-8", $this->internalEncoding));
        $snoopy->submit($url, $vars);
        $result = $snoopy->results;
        if(preg_match("/<error>0<\/error>/", $result)){
            return TRUE;
        }else{
            return FALSE;
        }
        
    }
}

?>