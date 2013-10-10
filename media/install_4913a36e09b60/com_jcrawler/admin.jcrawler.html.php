<?php


defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_jcrawler {


	function showForm($option) {
	/* display start page */
		$document_root = $_SERVER[DOCUMENT_ROOT];
		$http_host =  'http://' . $_SERVER[HTTP_HOST];
		$script = $_SERVER[SCRIPT_NAME];
		//$sitemap_url = dirname($_SERVER[SCRIPT_NAME]) . "sitemap.xml";  
		$sitemap_url = "/sitemap.xml";  
		
		$fobidden_types=array(".jpg",".jpeg",".gif",".png");
		
		$str_fobidden_types = arrToString($fobidden_types);
		$priority = 0.5;
		
		?>
	<form action="index2.php" method="post" name="Jcrawler">
	<fieldset style="padding: 10px; width:680px; border:2px solid #000099;">
	<legend style="color:#000099;"><b>Adapt this to your site</b></legend>
	<table border="0" cellpadding="5" cellspacing="0" width="650">
	  <tr>
		<td width="250" valign="top"><label for="idocument_root" accesskey="D">Document root</label><br />
			<small>path on server</small></td>
		<td width="240">
			<input class="required" type="Text" name="document_root" id="idocument_root" align="LEFT" size="50" value="<? echo $document_root ?>"/>
		</td>
	  </tr>	
	  <tr>
		<td width="250" valign="top"><label for="ihttp_host" accesskey="H">HTTP host (readonly)</label><br />
			<small>the url of your website</small></td>
		<td width="240">
			<input class="required" type="Text" name="http_host" id="ihttp_host" align="LEFT" size="50" readonly="readonly" value="<? echo $http_host ?>"/>
		</td>
	  </tr>
	  <tr>
		<td width="250" valign="top"><label for="fobidden_types" accesskey="F">Forbidden file types</label><br />
			<small>files containing this file type will not be added to site index; use line break to separate entries</small></td>
		<td width="240">
			<textarea name="fobidden_types" cols="40" rows="10" id="fobidden_types"><? echo $str_fobidden_types ?></textarea>
		</td>
	  </tr>
        <tr>
		<td width="250" valign="top"><label for="exclude_names" accesskey="e">Exclude list</label><br />
			<small>URLs containing this string will not be added to site index; use line break to separate entries</small></td>
		<td width="340">
			<textarea name="exclude_names" cols="60" rows="10" id="exclude_names"><? echo $str_exclude_names ?></textarea>
		</td>
	  </tr>
	  <tr>
		<td width="250" valign="top"><label for="isitemap_file" accesskey="S">Sitemap url</label><br />
			<small>Where to store sitemap file - relative to your document root; this must exist, be <font color="red"><strong>writetable</strong></font> and accessible for the google bot!</small></td>
		<td width="240">
			<input type="Text" name="sitemap_url" id="isitemap_url" align="LEFT" size="50" value="<? echo $sitemap_url ?>"/>
		</td>
	  </tr>	
	  <tr>
		<td width="250" valign="top"><label for="ipriority" accesskey="P">Priority</label><br />
			<small>from 0.0 to 1.0, e.g. 0.5</small></td>
		<td width="240">
			<input type="Text" name="priority" id="ipriority" align="LEFT" size="50" value="<? echo $priority ?>"/>
		</td>
	  </tr>	
        <tr>
		<td width="250" valign="top"><label for="ifreq" accesskey="F">Change frequency</label><br />
			<small>How frequently the page is likely to change</small></td>
		<td width="240">
			<select name="freq">
            	<option value="always">Always</option>
                <option value="hourly">Hourly</option>
                <option value="daily" selected="selected">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="never">Never</option>
			</select>
		</td>
	  </tr>
        <tr>
		<td width="250" valign="top"><label for="irobots" accesskey="R">Modify <a href="<? echo $http_host ?>/robots.txt">robots.txt</a></label><br />
			<small>file in joomla root, which contains the sitemap location. robots.txt must be <font color="red"><strong>writetable</strong></font>  </small></td>
		<td width="240">
			<input type="checkbox" name="robots" id="irobots" align="LEFT" value="1"/>
		</td>
	  </tr>	
	  <tr>
		<td>&nbsp;</td>
		<td><input type="Submit" value="Start" name="submit"> <small>Please be patient, this can take a while</small></td>
	  </tr>		
	</table>
	</fieldset>
    
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="submit" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="client" value="<?php echo $client; ?>" />
		</form>
        <div align="center"><p>Copyright 2008 pixelschieber.ch. 
		<a target="_blank" href="http://www.pixelschiber.ch/jcrawler">pixelschieber.ch</a></p></div>

	<? }    

function showNotifyForm($option, $sitemap_url) {  ?>
	        <div style="position:absolute; left:400px; width:200px; float:left; clear:right;">
<fieldset style="padding: 10; width:200px; border-color:#000099; border-width:2px; border-style:solid; ">
            	<legend style="color:#000099;">Options</legend>
        		<ul><li><a href="http://www.validome.org/google/validate?url=<? echo $sitemap_url ?>&googleTyp=SITEMAP" target="_blank">Vadidate my sitemap</a></li>
					<li><a href="<? echo $sitemap_url ?>" target="_blank">View my sitemap</a></li></ul>
       	  </fieldset>
        </div>
	<div style="height:200px; width:300px; float:left;">
<form action="index2.php" method="post" name="Jcrawler" enctype="multipart/form-data">
	<fieldset style="padding: 10; width:300px; border-color:#000099; border-width:2px; border-style:solid; ">
	<legend style="color:#000099;"><b>Submit sitemap to</b></legend>
	<ul>
<li><input type="checkbox" name="url[]" checked="checked" value="http://www.google.com/webmasters/sitemaps/ping?sitemap=<? echo urlencode($sitemap_url) ?>" /> Google</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://webmaster.live.com/ping.aspx?siteMap=<? echo urlencode($sitemap_url) ?>" /> MSN</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://submissions.ask.com/ping?sitemap=<? echo urlencode($sitemap_url) ?>" /> Ask.com</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://api.moreover.com/ping?u=<? echo urlencode($sitemap_url) ?>" /> Moreover</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=SitemapWriter&url=<? echo urlencode($sitemap_url) ?>" /> Yahoo <br /></li>
                                                
         <br /><input type="Submit" value="Submit" name="submit"></ul>	</fieldset>
	
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="notify" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="client" value="<?php echo $client; ?>" />
		</form>
	</div>
         <div align="center" style="clear:both;">Copyright 2008 pixelschieber.ch. 
		<a target="_blank" href="http://www.pixelschiber.ch/jcrawler">pixelschieber.ch</a></div>

<? }    

	

/*} elseif ($_REQUEST[submit] == "Start") {
	// handle xml file creation
	
	// get values from gui of script
	$website = $_POST['http_host']; 
	$page_root = $_REQUEST[document_root]; 
	$sitemap_file = $page_root . $_REQUEST[sitemap_url];
	$sitemap_url = $website . $_REQUEST[sitemap_url];
	
	//$urlarray=getUrl($website);
	
	$file = genSitemap(getlinks($website));
	writeXML($file,$sitemap_file);
}*/


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

} // end class
?>