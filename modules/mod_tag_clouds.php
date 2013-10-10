<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$minCountValue = $params->get('minCountValue', "2");
$minWordLen = $params->get('minWordLen', "3");
$maxWordLen = $params->get('maxWordLen', "25");
$minFontSize = $params->get('minFontSize', "30");
$exclude_words = $params->get('exclude_words', "");
$blockid = $params->get('BlockID', "");
$exclude_words = str_replace (chr(10), "", $exclude_words); 
$exclude_words = str_replace (chr(13), "", $exclude_words);
$contentSelect = $params->get('contentSelect', "1");
$contentParse = $params->get('contentParse', "1");
$tagsef = $params->get('tagsef', "1");
$maxWordCount = max(1, $params->get('maxWordCount', "30"));
$chooseItemStyle = $params->get('chooseItems', "1");

if ($blockid != "") 
	{
	$blockid = str_replace(' ', '', $blockid);
	$blockid = explode (',', $blockid);

	foreach($blockid as $i)
		{
		$blockidstr .= "and `id` != ".$i." ";
		}
	}
global $mosConfig_dbprefix,$mosConfig_live_site;
function exclude($exclude_words)
	{
         $exclude_words = clean_tagstring($exclude_words);
         $exclude_words = explode (' ', $exclude_words);
	return $exclude_words;
	}

function add_text($contentSelect,$contentParse,$frontid,$mosConfig_dbprefix,$blockidstr)
   {
	if ($contentSelect == "1") {
		if ($contentParse == "1") {
		
			if ($blockidstr != "") {
			$query = "SELECT `fulltext` FROM ".$mosConfig_dbprefix."content where `state` = 1 ".$blockidstr;
			}
			else {
			$query = "SELECT `fulltext` FROM ".$mosConfig_dbprefix."content where `state` = 1";
			}
		}
		if ($contentParse == "2") {
			if ($_GET[id] != "") {
			$query = "SELECT `fulltext` FROM ".$mosConfig_dbprefix."content where `state` = 1 and `id` = ".$_GET[id];
			}
			else {
			$query = "SELECT `fulltext` FROM ".$mosConfig_dbprefix."content where ".$frontid;
			}
		}
	}
	if ($contentSelect == "2") {
		if ($contentParse == "1") {
			if ($blockidstr != "") {
			$query = "SELECT `introtext` FROM ".$mosConfig_dbprefix."content where `state` = 1 ".$blockidstr;
			}
			else {
			$query = "SELECT `introtext` FROM ".$mosConfig_dbprefix."content where `state` = 1";
			}
		}
		if ($contentParse == "2") {
			if ($_GET[id] != "") {
				$query = "SELECT `introtext` FROM ".$mosConfig_dbprefix."content where `state` = 1 and `id` = ".$_GET[id];
			}
			else {
				$query = "SELECT `introtext` FROM ".$mosConfig_dbprefix."content where ".$frontid;
			}
		}
	}
 	$result = mysql_query($query) OR die(mysql_error());
        
	if ($contentSelect == "1") {
	while($row = mysql_fetch_assoc($result)) {
		$tags_string .=  $row['fulltext']." ";
	}
	}
	if ($contentSelect == "2") {
	while($row = mysql_fetch_assoc($result)) {
		$tags_string .=  $row['introtext']." ";
	}
	}
	return $tags_string;
	}

   function clean_tagstring($string='')
   {
      $expr = '/<.*>(.*)<\/.*>/i';
	$string = str_replace($expr, ' $1 ', $string);
	  
	  
	$string = strip_tags  ($string); 

      $string = str_replace ("\r\n", " ", $string);  
      $string = str_replace ("\r"  , " ", $string);  
      $string = str_replace ("\n"  , " ", $string);  
      $string = str_replace ("\t"  , " ", $string);  
      $string = str_replace ("\0"  , "" , $string);  
      $string = str_replace ("\x0B", "" , $string);  
	$string = str_replace('&szlig;', '�', $string);
	$string = str_replace('&nbsp;', ' ', $string);
	$string = str_replace('&ndash;', '�', $string);
	$string = str_replace('&uuml;', '�', $string);
	$string = str_replace('&Uuml;', '�', $string);
	$string = str_replace('&auml;', '�', $string);
	$string = str_replace('&Auml;', '�', $string);
	$string = str_replace('&ouml;', '�', $string);
	$string = str_replace('&Ouml;', '�', $string);
	$string = str_replace('.', ' ', $string);
	$string = str_replace(':', ' ', $string);
	$string = str_replace(',', ' ', $string);
	$string = str_replace(';', ' ', $string);
	$string = str_replace('-', ' ', $string);
	$string = str_replace('+', ' ', $string);
	$string = str_replace('!', ' ', $string);
	$string = str_replace('\"', ' ', $string);
	$string = str_replace('$', ' ', $string);
	$string = str_replace('%', ' ', $string);
	$string = str_replace('�', ' ', $string);
	$string = str_replace('&', ' ', $string);
	$string = str_replace('/', ' ', $string);
	$string = str_replace('(', ' ', $string);
	$string = str_replace(')', ' ', $string);
	$string = str_replace('=', ' ', $string);
	$string = str_replace('!', ' ', $string);
	$string = str_replace('?', ' ', $string);
	$string = str_replace('"', ' ', $string);
	$string = str_replace('@', ' ', $string);
	$string = str_replace('#', ' ', $string);
	$string = str_replace('_', ' ', $string);
	$string = str_replace('<', ' ', $string);
	$string = str_replace('>', ' ', $string);
	$string = str_replace('  ', ' ', $string);
	$string = str_replace('*', ' ', $string); 
	$string = str_replace('\'', ' ', $string); 
	$string = str_replace('�', ' ', $string); 
	$string = str_replace('`', ' ', $string);
	$string = str_replace('^', ' ', $string);
	$string = str_replace('[', ' ', $string);
	$string = str_replace(']', ' ', $string);
	$string = str_replace('}', ' ', $string);
	$string = str_replace('{', ' ', $string);
	$string = str_replace('�', ' ', $string);
	$string = str_replace('�', ' ', $string);
	$string = str_replace('  ', ' ', $string);
      $string = strtolower ($string);

      $string = trim ($string);

      return $string;
   }

   function build_tags_array($tagclouds,$exclude_words,$minCountValue,$minWordLen,$maxWordLen)
   {
      if (empty ($tagclouds))
         return array ();
      else
      {
         $array_content = explode (' ', $tagclouds);
         $array_content = array_count_values ($array_content);
         $zb = 1;
		 foreach ($array_content as $word => $count)
         {
            if ($count > $minCountValue
                && strlen ($word) > $minWordLen
                && strlen ($word) < $maxWordLen
                && (!in_array ($word, $exclude_words))
               )
            {
			$zb++;
            $tags[$word] = $count;
            }
            unset ($count, $array_content[$word]);
         }
		 if ($zb > 2) {
		 ksort ($tags);
		 }
      }
	  return $tags;
   }

   function build_output($tags,$minFontSize,$tagsef,$mosConfig_live_site)
   {
      if (!is_array ($tags) || empty ($tags))
         $output = '';
      else
      {
	$maxSize = max ($tags);
      $multiplier = 300 / $maxSize;
      foreach ($tags as $word => $count)
         {
            $fontSize = $multiplier * $count;
            $fontSize = max ($fontSize, $minFontSize) + mt_rand (-10 , 10);
			$fontSize = (int)$fontSize;
			$word2 = rawurlencode($word);
	if ($tagsef == "1") {
	$output .= '<a title="'.$word.'" href="'.$mosConfig_live_site.'/index.php?searchword='.$word2.'&option=com_search" style="font-size:'.$fontSize.'%;">'.$word.'</a> ';
	}
	if ($tagsef == "2") {
	$output .= '<a title="'.$word.'" href="'.$mosConfig_live_site.'/tags/'.$word2.'/" style="font-size:'.$fontSize.'%;">'.$word.'</a> ';
	}
	}
      }
	return  $output;
   }
   function add_module_details()
   {
      $this->output  = " ";
   }
 
 
$frontquery = "SELECT * FROM `".$mosConfig_dbprefix."content_frontpage`,`".$mosConfig_dbprefix."content` WHERE `".$mosConfig_dbprefix."content_frontpage`.`content_id` = `".$mosConfig_dbprefix."content`.`id` and `".$mosConfig_dbprefix."content`.`state` = 1";

$frontresult = mysql_query($frontquery) OR die();
$frontid = "`id` = ";
while($row = mysql_fetch_assoc($frontresult)) {
	 $frontid .=   $row['id']." or `id` = ";
	}
$frontid .="99999999";

$exclude_words = exclude($exclude_words);
$tagclouds = add_text($contentSelect,$contentParse,$frontid,$mosConfig_dbprefix,$blockidstr);
$tagclouds = clean_tagstring($tagclouds);
$tags = build_tags_array($tagclouds,$exclude_words,$minCountValue,$minWordLen,$maxWordLen);

$maxWordCount = min($maxWordCount, count($tags));
switch($chooseItemStyle) {
	case "2"://alphabetisch (bei A beginnend)
		reset($tags);
		while(list($word, $count) = each($tags)) {
			if ($i==$maxWordCount) {
				break;
			}
			$i++;
			$t2[$word] = $count;
		}
	break;
	case "3"://alphabetisch (bei Z beginnend, rückwärts)
		end($tags);
		while ($count = current($tags)) {
			if ($i==$maxWordCount) {
				break;
			}
			$i++;
			$t2[key($tags)] = $count;
			prev($tags);
		}
	break;
	case "4"://größte Häufigkeit
		arsort ($tags);
		while(list($word, $count) = each($tags)) {
			if ($i==$maxWordCount) {
				break;
			}
			$i++;
			$t2[$word] = $count;
		}
	break;
	case "5"://geringste Häufigkeit
		asort ($tags);
		while(list($word, $count) = each($tags)) {
			if ($i==$maxWordCount) {
				break;
			}
			$i++;
			$t2[$word] = $count;
		}
	break;
	default:// Zufallsauswahl
		$temp = array_keys($tags);
		shuffle($temp);
		for($i=0;$i<$maxWordCount;$i++) {
			$t2[$temp[$i]] = $tags[$temp[$i]];
		}
	break;
}
$tags = $t2;
unset($t2);
unset($temp);


$output = build_output($tags,$minFontSize,$tagsef,$mosConfig_live_site);

echo $output."<p><a href=\"http://www.google.co.id\" title=\"Tag Cloud\" target=\"_Blank\"><img src=\"".$mosConfig_live_site."/modules/tag.clouds.gif\" alt=\"DMI\" border=\"0\"/></a></p>";
?>
