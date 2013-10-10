<?php

	// no direct access
	defined( '_VALID_MOS' ) or die( 'Restricted access' );
	
	//Search the web
	$web = intval($params->get('web'));
	
	//Width of the module
	$width = intval($params->get('width'));
		
	//Google AJAX Search API Key
	$key = $params->get('key');
	
	//URL of the site (example: amazon.com, google.com)
	$url = $params->get('url');
	
	$tokens = parsing($url);
	
	function parsing($string)
	{
		$i=0;
		$nextToken = strtok($string,",");
	
		while($nextToken!==false) 
		{
  	 	    $tokens[$i] = $nextToken;
  	 	    $i++;
  	 	    $nextToken = strtok(" ");
   		}
   		return $tokens;
	}
	
	$sites_str = '"'. implode( '", "', $tokens ) .'"';
	
	?>
	
	<link href="http://www.google.com/uds/css/gsearch.css" type="text/css" rel="stylesheet"/>
    <script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo $key; ?>" type="text/javascript"></script>
    
    <style type="text/css">
    .gsc-control {
 	 width: <?php echo $width;?>px;
	}
	</style>
    
    <script language="Javascript" type="text/javascript">
    //<![CDATA[
  
    function OnLoad() {

      // Create a search control
      var searchControl = new GSearchControl();
      
	  var sites = new Array(<?=$sites_str?>);

	  for(i=0;i<sites.length;i++)
	  {
      	// site restricted web search with custom label
      	// and class suffix
      	var siteSearch = new GwebSearch();
      	siteSearch.setUserDefinedLabel(sites[i]);
      	siteSearch.setUserDefinedClassSuffix("siteSearch");
      	siteSearch.setSiteRestriction(sites[i]);
      	searchControl.addSearcher(siteSearch);
	  }
	
      if(<?=$web;?>==1)
      {
      	// standard, unrestricted web search
      	searchControl.addSearcher(new GwebSearch());
	  }
	  
      // tell the searcher to draw itself and tell it where to attach
      searchControl.draw(document.getElementById("searchcontrol"));   
      }
      
      // arrange for myOnLoad to get called
 	 registerLoadHandler(OnLoad);

 	 function registerLoadHandler(handler) {
 	   var node = window;
 	   if (node.addEventListener) {
 	     node.addEventListener("load", handler, false);
 	   } else if (node.attachEvent) {
 	     node.attachEvent("onload", handler);
 	   } else {
 	     node['onload'] = handler;
 	   }
 	 }
      
    //]]>
    </script> 
      <div id="searchcontrol"/>
