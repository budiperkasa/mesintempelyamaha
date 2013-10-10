<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

set_time_limit(999999);
ini_set("max_execution_time","false");

// ensure user has access to this function
/*if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jcrawler' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}*/

// require the html view class
jimport( 'joomla.application.helper' );
jimport('joomla.filesystem.file');

require_once( JApplicationHelper::getPath( 'admin_html', 'com_jcrawler' ) ); 
//require_once( JApplicationHelper::getPath( 'toolbar_html', 'com_jcrawler' ) );

$task = JRequest::getVar( 'task', '' );

switch ($task) {
	case 'submit':
		submit($option);
		break;
	case 'notify':
		notify($option);
		break;
	
	default:
		HTML_jcrawler::showForm($option);
		break;
}


$stack = array();
$disallow_file = array();


function submit($option) {
	global $stack;
	// handle xml file creation
	
	// get values from gui of script
	$website = JRequest::getVar( 'http_host', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$page_root = JRequest::getVar( 'document_root', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$sitemap_file = $page_root . JRequest::getVar( 'sitemap_url', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$sitemap_url =  $website . JRequest::getVar( 'sitemap_url', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$priority = JRequest::getVar( 'priority', '1.0', 'POST', 'DOUBLE', JREQUEST_ALLOWHTML );
	$forbidden_types = JRequest::getVar( 'fobidden_types', 'none', 'POST', 'ARRAY', JREQUEST_ALLOWHTML );
	$exclude_names = JRequest::getVar( 'exclude_names', 'none', 'POST', 'ARRAY', JREQUEST_ALLOWHTML );
	$freq = JRequest::getVar( 'freq', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	$robots = JRequest::getVar( 'robots', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	
	($priority >= 1)?$priority="1.0":null;
	
	//$website = $_POST['http_host']; 
	//$page_root = $_REQUEST[document_root]; 
	//$sitemap_file = $page_root . $_REQUEST[sitemap_url];
	//$sitemap_url = $website . $_REQUEST[sitemap_url];
	
	//$urlarray=getUrl($website);
	$stack = array();
	$file = genSitemap($priority, getlinks($website,$forbidden_types,2,$exclude_names),$freq,$website);
	writeXML($file,$sitemap_file,$option, $sitemap_url);
	if ($robots==1) modifyrobots($sitemap_url,$page_root);
}

function notify($option) {
	global $mainframe;

	$url = JRequest::getVar( 'url', 'none', 'POST', 'ARRAY', JREQUEST_ALLOWHTML );
	
	if ($url[0]!="none"){
		foreach ($url as $key) {
			if (JFile::read(urldecode($key))!=false){
				$mainframe->enqueueMessage( "Submission to ".parse_url($key, PHP_URL_HOST)." succeed " );
			} else {
				$errors[] = JError::getErrors();
				foreach ($errors as $error) {
					$mainframe->enqueueMessage($error->message,error);
				}
			
			}
		}
	}
	
	$mainframe->redirect('index2.php?option='.$option);
	
}

	function modifyrobots ($sitemap_url,$page_root){
		global $mainframe;
		if (substr($page_root,-1)!="/") $page_root=$page_root."/";
		$robots=JFile::read( $page_root.'robots.txt' );
		//$pos=stripos("Sitemap:",$robots);
		$pos=preg_match("/Sitemap:/", $robots);
		//print_r($robots);
		//if (strpos('\nSitemap:',$robots)===false){
	
		
		if ($pos==0){	
			if (JFile::write( $page_root.'robots.txt',$robots."\n# BEGIN JCAWLER-XML-SITEMAP-COMPONENT\nSitemap: ".$sitemap_url."\n# END JCAWLER-XML-SITEMAP-COMPONENT" )!=false) {
				$mainframe->enqueueMessage( "robots.txt modified" );
			} else {
				$errors[] = JError::getErrors();
				foreach ($errors as $error) {
					$mainframe->enqueueMessage($error->message,error);
				}
			}
		} else {
			$mainframe->enqueueMessage( "robots.txt contains already sitemap location" );
		}
	}


	function writeXML ($file, $location, $option, $sitemap_url) {
		global $mainframe;
		
			// Write $somecontent to our opened file.
		$buffer .= pack("CCC",0xef,0xbb,0xbf);
		$buffer .= utf8_encode($file);
		if (JFile::write( $location, $buffer )){
		
			//print "Success, wrote the XML to file $location";
			$mainframe->enqueueMessage( "Success, wrote the XML to file $location" );
			HTML_jcrawler::showNotifyForm($option, $sitemap_url);
			
		} else {
			$errors[] = JError::getErrors();
			foreach ($errors as $error) {
				//echo $error->message;
				$mainframe->enqueueMessage($error->message,error);
			}
			
		}
		//$mainframe->redirect('index2.php?option='.$option.'&task=notify', "Success, wrote the XML to file $location" );
		return;
	}
	

function getlinks($url,$forbidden_types,$level=2,$exclude_names) {
	global $stack;
	is_array($url)?$arrurl=$url:$arrurl[]=$url;
	(count($arrurl)<31)?$z=1:$z=count($arrurl);
	
		
	$tmparr_last=array_merge($stack,getUrl(connect($arrurl,$z),$forbidden_types,$exclude_names));
	
	for ($u=0;$u<$level;$u++){
		
		$tmparr=getUrl(connect($tmparr_last,$z),$forbidden_types,$exclude_names);
		$tmparr_last=$tmparr;
		$stack=array_merge($stack,array_diff($tmparr,$stack));
	}
	
	$stack=array_unique($stack);
	array_unshift($stack,$url); //add $url to the url array
	return $stack;
}



function genSitemap($priority, $urls, $freq, $document_root){
	
	$xml_string = '<?xml version=\'1.0\' encoding=\'UTF-8\'?><?xml-stylesheet type="text/xsl" href="'.$document_root.'/administrator/components/com_jcrawler/sitemap.xsl"?>
	<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
	foreach ($urls as $loc){
	/* urf-8 encoding */
	$loc=htmlspecialchars($loc,ENT_QUOTES,'UTF-8');
	
	$modified_at = date('Y-m-d\Th:i:s\Z');
	$xml_string .= "
	<url>
	   <loc>$loc</loc>
	   <lastmod>$modified_at</lastmod>
	   <priority>$priority</priority>
	   <changefreq>$freq</changefreq>
	</url>";
	}
	
	$xml_string .= "
	</urlset>";
	
	return $xml_string;
}

// misc functions

function toArray($str, $delim = "\n") {
	$res = array();	
	$res = explode($delim, $str);
	
	for($i = 0; $i < count($res); $i++) {
		$res[$i] = trim($res[$i]);
	}
	
	return $res;
}
/* returns a string of all entries of array with delim */
function arrToString($array, $delim = "\n") {
  $res = "";
  if (is_array($array)) {
	for ($i = 0; $i < count($array); $i++) {
	  $res .= $array[$i];
	  if ($i < (count($array)-1)) $res .= $delim;
	}
   }
   return $res;
}

/* simple compare function: equals */
function ar_contains($key, $array) {
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $val) {
	  	if ($key == $val) {
			return true;
		}
    }
  }
  return false;
}

/* better compare function: contains */
function fl_contains($key, $array) {
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $val) {
	  $pos = strpos($key, $val);
	  if ($pos === FALSE) continue;
	  return true;
    }
  }

  return false;
}

/* this function changes a substring($old_offset) of each array element to $offset */
function changeOffset($array, $old_offset, $offset) {
  $res = array();
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $val) {
      $res[] = str_replace($old_offset, $offset, $val);
    }
  }
  return $res;
}

function connect ($url,$z=5){

	if (function_exists('curl_init') and function_exists('curl_multi_init')) {
		for ($i=0;$i<count($url);$i=$i+$z){
			
			for ($v=0;$v<$z;$v++){ 
				$ch[$i+$v] = curl_init($url[$i+$v]);
				
			}
			$mh = curl_multi_init();
			
			for ($v=0;$v<$z;$v++){ 
				curl_multi_add_handle($mh,$ch[$i+$v]);
			}
			
		
			// use output buffering instead of returntransfer -itmaybebuggy 
			ob_start(); 
			$running=null;
			//execute the handles
			do {
				curl_multi_exec($mh,$running);
			} while ($running > 0);

			for ($v=0;$v<$z;$v++){ 
				curl_multi_remove_handle($ch[$i+$v]);
			}
			curl_multi_close($mh);
			
			$buffer.= ob_get_contents(); 
			ob_end_clean();
		}
	} elseif (function_exists('fopen')){
		foreach ($url as $key) {	
			$handle = fopen ($key, "r");
			while (!feof($handle)) {
				$line = fgets($handle);
				$buffer.=$line;
			}
			fclose($handle);
		}
	} elseif (function_exists(file_get_contents)) {
		foreach ($url as $key) {
			$buffer.=file_get_contents($key);
		}
	}
	
	return $buffer;

}

/* this walks recursivly through all directories starting at page_root and
   adds all files that fits the filter criterias */
// taken from Lasse Dalegaard, http://php.net/opendir
function getUrl($buffer,$forbidden_types,$forbidden_names) {
	global $_POST, $stack;
	$website = JRequest::getVar( 'http_host', 'none', 'POST', 'STRING', JREQUEST_ALLOWHTML );
	
       // Create an array for all files found
       $tmp = Array();
	array_push($forbidden_types,".javascript",".close()","javascript:");
	
	$forbidden_strings=array("print=1","format=pdf","option=com_mailto");
	foreach ($forbidden_names as $name) {
		($name!="") ? ($forbidden_strings[]=$name):null;
	}
		
	
    if(substr($website,-1)=="/") $website=substr($website,-1);
    $epos=0; $i=0;
	$pattern="<a href";
	
	$suchmuster='<a href=("|\')(.*?)("|\')>';
	preg_match_all($suchmuster, $buffer, $treffer);
	//print_r($treffer);
	
	 $tmparray=array();
	
	foreach ($treffer[2] as $key) {
		if (strpos($key,$website)===false){ 
			if (substr($key,0,4)!="http" && substr($key,0,5)!="https"){
				if(substr($key,0,1)!="/"){
					$key="/".$key;
				}
				if (in_array(substr(strtolower($key),strrpos($key,".")),$forbidden_types)===false){
					if (strpos($key,"javascript:")===false){
						$key=str_replace("&amp;","&",$key);
						$key=$website.$key;
					}
				}
			}
		}

		
		if(!in_array($key,$tmparray) && !in_array($key,$stack) && $key!=$website."/" && strpos($key,$website)!==false 
			&& fl_contains($key, $forbidden_strings)===false){
			$tmparray[]=$key;
			//$stack[]=$key;
		}
	}
	
	return $tmparray;
}

?>