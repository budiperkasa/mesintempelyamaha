<?php
/**
* @version 2.0 RokSlideshow - based on Slideshow 2.0 plugin for Textpattern
* @package RocketWerx
* @copyright Copyright (C) 2007 RocketWerx. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* 
* This module is based on the fantastic Slideshow RC2 script by Aeron Glemann
* http://www.electricprism.com/aeron/slideshow 
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );



$showmode 	= $params->get( 'showmode', 0 );
$imagePath 	= cleanDir($params->get( 'imagePath', 'images/stories/fruit' ));
$showCaption 	= $params->get( 'showCaption', 1 );
$width = $params->get( 'width', 430 );
$height = $params->get( 'height', 200 );
$altTag = $params->get( 'altTag', 'RokSlideshow - http://www.rocketwerx.com' );
$imageDuration = $params->get( 'imageDuration', 9000 );
$transDuration = $params->get( 'transDuration', 2000);
$transType = $params->get( 'transType', 'combo');
$transition = $params->get( 'transition', 'bounceOut');
$pan = $params->get( 'pan', 10);
$zoom = $params->get( 'zoom', 10);
$jslib = $params->get( 'jslib', 1);
$sortCriteria = $params->get( 'sortCriteria', 0);
$sortOrder = $params->get( 'sortOrder', 'asc');
$sortOrderManual = $params->get( 'sortOrderManual', '');
$imageStart = $params->get( 'imageStart', 0);
$imageResize = $params->get( 'imageResize', 1);


if (trim($sortOrderManual) != "")
	$images = explode(",", $sortOrderManual);
else
	$images = imageList($imagePath, $sortCriteria, $sortOrder);
	
if (count($images) > 0) {
	$imgcount = 0;

	$firstImage = '';
	$transDetails = '';
	$captionDetails = '';
	$imageArray = array();	
	$captionArray = array();

	foreach($images as $img) {
		if ($imgcount++ == 0) {
			if ($imageStart) 
				$firstImage = $imagePath . $imageStart;
			else
				$firstImage = $imagePath . $img;
		}
		$imageArray[] = "'$img'";
		if ($showCaption) $captionArray[] = "'" . getInfo($imagePath, $img) . "'";
	}

	if ($showCaption) { 
	 $captionDetails = " captions: [" . implode(",", $captionArray) . "],";
	}


	if ($jslib == 1) {
		echo '<script src="modules/rokslideshow/mootools.js" type="text/javascript"></script>' . "\n";
	}
  echo '<script src="modules/rokslideshow/slideshow.rc1.packed.js" type="text/javascript"></script>' . "\n";
  echo '<div id="my_slideshow" class="slideshow">' . "\n";
  echo '<img src="' . $firstImage . '" alt="' . $altTag . '" width="' . $width . '" height="' . $height . '" />' . "\n";
  echo '</div>' . "\n";
	
	if (trim($transType) == 'push' or trim($transType == 'wipe'))
		$transDetails = "type: '$transType', transition: Fx.Transitions.$transition";
	else
		$transDetails = "type: '$transType', pan: '$pan', zoom: '$zoom'";

	echo '<script type="text/javascript">' . "\n";
	echo "  myShow = new Slideshow('my_slideshow', {width: $width, height: $height, hu: '$imagePath', images: [" .  implode(",", $imageArray) . "],". $captionDetails . " duration: [$transDuration, $imageDuration], $transDetails, resize: " . ($imageResize==1?"true":"false") . "});\n";
	echo '</script>';
}

//echo $output;


//helper functions
function imageList ($directory, $sortcriteria, $sortorder) {
    $results = array();
    $handler = opendir($directory);
		$i = 0;
    while ($file = readdir($handler)) {
        if ($file != '.' && $file != '..' && isImage($file)) {
					$results[$i][0] = $file;
					$results[$i][1] = filemtime($directory . "/" .$file);
					$i++;
				}
    }
    closedir($handler);

		//these lines sort the contents of the directory by the date
		// Obtain a list of columns
		
		foreach($results as $res) {
			if ($sortcriteria == 0 ) $sortAux[] = $res[0];
			else $sortAux[] = $res[1];
		}
		
		if ($sortorder == 0) {
			array_multisort($sortAux, SORT_ASC, $results);
		} elseif ($sortorder == 2) {
			srand((float)microtime() * 1000000);
			shuffle($results);
		} else {
			array_multisort($sortAux, SORT_DESC, $results);
		}
		
		foreach($results as $res) {
			$sorted_results[] = $res[0];
		}

    return $sorted_results;
}

function getInfo($imagePath, $file) {
		global $iso_client_lang;

		$langext = "";
		$fileext= ".txt";
		
		if (isset($iso_client_lang) && strlen($iso_client_lang)>1) $langext = "." . $iso_client_lang;

		$file_noext = substr($file, 0, strrpos($file,"."));
		$info = "&nbsp;";

		$infofile = $imagePath . $file_noext . $langext . $fileext;

		if (!file_exists($infofile)) $infofile = $imagePath . $file_noext . $fileext;
		if (file_exists($infofile)) {
			$imginfo = file ($infofile);
			foreach ($imginfo as $line) {
				$info .= addslashes($line);
			}
		}
		return $info;
}

function isImage($file) {
	$imagetypes = array(".jpg", ".jpeg", ".gif", ".png");
	$extension = substr($file,strrpos($file,"."));
	if (in_array($extension, $imagetypes)) return true;
	else return false;
}

function cleanDir($dir) {
	if (substr($dir, -1, 1) == '/')
		return $dir;
	else
		return $dir . "/";
}

?>