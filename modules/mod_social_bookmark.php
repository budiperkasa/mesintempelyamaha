<?php

?>

<?
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $mosConfig_MetaKeys, $mosConfig_MetaDesc;
$align 			= $params->get('align', 'center');
$padding 		= $params->get('padding', '0');
$ani 			= $params->get('ani', '1');
$alt 			= $params->get('alt', "Add to:");
$whatlink		= $params->get('whatlink', "http://en.wikipedia.org/wiki/Social_Bookmarking");
$tags 			= str_replace("\n","", $mosConfig_MetaKeys);
$tags 			= trim($tags);
$tags_space		= str_replace(',', ' ', $tags);
$tags_semi 		= str_replace(',', ';', $tags);
$tags_space 	= str_replace('  ', ' ', $tags_space);
$description 	= $mosConfig_MetaDesc;
$style 			= $params->get('style', "1");

$wong 			= $params->get('wong', 1);
$wongl			= $params->get('wongl', "");

$webnews 		= $params->get('webnews', 1);
$webnewsl 		= $params->get('webnewsl', "");

$iciode 		= $params->get('iciode', 1);
$iciodel		= $params->get('iciodel', "");

$oneview 		= $params->get('oneview', 1);
$oneviewl 		= $params->get('oneviewl', "");

$yigg 			= $params->get('yigg', 1);
$yiggl 			= $params->get('yiggl', "");

$newsider 		= $params->get('newsider', 1);
$newsiderl 		= $params->get('newsiderl', "");

$seekxl 		= $params->get('seekxl', 1);
$seekxll 		= $params->get('seekxll', "");

$newskick 		= $params->get('newskick', 1);
$newskickl 		= $params->get('newskickl', "");

$favit 			= $params->get('favit', 1);
$favitl			= $params->get('favitl', "");

$kledy 			= $params->get('kledy', 1);
$kledyl			= $params->get('kledyl', "");

$sbdk 		    = $params->get('sbdk', 1);
$sbdkl 		    = $params->get('sbdkl', "");

$boni			= $params->get('boni', 1);
$bonil			= $params->get('bonil', "");

$power			= $params->get('power', 1);
$powerl			= $params->get('powerl', "");

$bookmarkscc	= $params->get('bookmarkscc', 1);
$bookmarksccl	= $params->get('bookmarksccl', "");

$favoriten		= $params->get('favoriten', 1);
$favoritenl		= $params->get('favoritenl', "");

$sbdk 			= $params->get('sbdk', 1);
$sbdkl 			= $params->get('sbdkl', "");

$linksilo 		= $params->get('linksilo', 1);
$linksilol 		= $params->get('linksilol', "");

$readster 		= $params->get('readster', 1);
$readsterl 		= $params->get('readsterl', "");

$linkarena 		= $params->get('linkarena', 1);
$linkarenal		= $params->get('linkarenal', "");

$digg 			= $params->get('digg', 1);
$diggl 			= $params->get('diggl', "");

$icio 			= $params->get('icio', 1);
$iciol 			= $params->get('iciol', "");

$reddit 		= $params->get('reddit', 1);
$redditl 		= $params->get('redditl', "");

$jumptags 		= $params->get('jumptags', 1);
$jumptagsl 		= $params->get('jumptagsl', "");

$upchuckr 		= $params->get('upchuckr', 1);
$upchuckrl 		= $params->get('upchuckrl', "");

$simpy 			= $params->get('simpy', 1);
$simpyl			= $params->get('simpyl', "");

$stumbleupon 	= $params->get('stumbleupon', 1);
$stumbleuponl 	= $params->get('stumbleuponl', "");

$slashdot 		= $params->get('slashdot', 1);
$slashdotl 		= $params->get('slashdotl', "");

$netscape 		= $params->get('netscape', 1);
$netscapel 		= $params->get('netscapel', "");

$furl 			= $params->get('furl', 1);
$furll 			= $params->get('furll', "");

$yahoo 			= $params->get('yahoo', 1);
$yahool			= $params->get('yahool', "");

$blogmarks 		= $params->get('blogmarks', 1);
$blogmarksl 	= $params->get('blogmarksl', "");

$diigo 			= $params->get('diigo', 1);
$diigol 		= $params->get('diigol', "");

$technorati 	= $params->get('technorati', 1);
$technoratil 	= $params->get('technoratil', "");

$newsvine 		= $params->get('newsvine', 1);
$newsvinel 		= $params->get('newsvinel', "");

$blinkbits		= $params->get('blinkbits', 1);
$blinkbitsl		= $params->get('blinkbitsl', "");

$magnolia 		= $params->get('magnolia', 1);
$magnolial 		= $params->get('magnolial', "");

$smarking 		= $params->get('smarking', 1);
$smarkingl 		= $params->get('smarkingl', "");

$netvouz 		= $params->get('netvouz', 1);
$netvouzl 		= $params->get('netvouzl', "");

$folkd 			= $params->get('folkd', 1);
$folkdl			= $params->get('folkdl', "");

$spurl 			= $params->get('spurll', 1);
$spurll 		= $params->get('spurll', "");

$google 		= $params->get('google', 1);
$googlel 		= $params->get('googlel', "");

$blinklist 		= $params->get('blinklist', 1);
$blinklistl 	= $params->get('blinklistl', "");

$linktype 		= $params->get('linktype', 1);
$what 			= $params->get('what', 1);
?>
<div style="padding:<?echo $padding;?>px;" align="<?echo $align;?>">
<?
if ($style == "1")
{
$style = "_trans";
}
else {
$style = "";
}

if ($ani == "1")
		{	
		if ($wong == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/bookmarks/wong".$style."_ani.gif',";
		}
		
		if ($webnews == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/webnews".$style."_ani.gif',";
		}
			
		if ($icio == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/icio".$style."_ani.gif',";
		}
		
		if ($sbdk == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/sbdk".$style."_ani.gif',";
		}
		
		if ($boni == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/boni".$style."_ani.gif',";
		}

		if ($power == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/power".$style."_ani.gif',";
		}

		if ($favoriten == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/favoriten".$style."_ani.gif',";
		}

		if ($bookmarkscc == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/bookmarkscc".$style."_ani.gif',";
		}			

		if ($oneview == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/oneview".$style."_ani.gif',";
		}
		
		if ($folkd == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/folkd".$style."_ani.gif',";
		}
		
		if ($newsider == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/newsider".$style."_ani.gif',";
		}
		
		if ($seekxl == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/seekxl".$style."_ani.gif',";
		}
		
		if ($newskick == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/newskick".$style."_ani.gif',";
		}
		
		if ($kledy == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/kledy".$style."_ani.gif',";
		}
				
		if ($favit == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/favit".$style."_ani.gif',";
		}
		
		if ($sbdk == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/sbdk".$style."_ani.gif',";
		}
		
		if ($linksilo == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/linksilo".$style."_ani.gif',";
		}
		
		if ($readster == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/readster".$style."_ani.gif',";
		}
		
		if ($yigg == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/yigg".$style."_ani.gif',";
		}
		
		if ($linkarena == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/linkarena".$style."_ani.gif',";
		}
	
		if ($digg == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/digg".$style."_ani.gif',";
		}
		
		if ($del == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/del".$style."_ani.gif',";
		}
		
		if ($reddit == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/reddit".$style."_ani.gif',";
		}
		
		if ($jumptags == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/jumptags".$style."_ani.gif',";
		}
		
		if ($upchuckr == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/upchuckr".$style."_ani.gif',";
		}
		
		if ($simpy == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/simpy".$style."_ani.gif',";
		}
		
		if ($stumbleupon == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/stumbleupon".$style."_ani.gif',";
		}
		
		if ($slashdot == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/slashdot".$style."_ani.gif',";
		}
		
		if ($netscape == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/netscape".$style."_ani.gif',";
		}
		
		if ($furl == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/furl".$style."_ani.gif',";
		}
		
		if ($yahoo == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/yahoo".$style."_ani.gif',";
		}
		
		if ($spurl == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/spurl".$style."_ani.gif',";
		}
		
		if ($google == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/google".$style."_ani.gif',";
		}
		
		if ($blinklist == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/blinklist".$style."_ani.gif',";
		}
			
		if ($blogmarks == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/blogmarks".$style."_ani.gif',";
		}
		
		if ($diigo == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/diigo".$style."_ani.gif',";
		}
		
		if ($technorati == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/technorati".$style."_ani.gif',";
		}
		
		if ($newsvine == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/newsvine".$style."_ani.gif',";
		}
		
		if ($blinkbits == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/blinkbits".$style."_ani.gif',";
		}
		
		if ($magnolia == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/ma.gnolia".$style."_ani.gif',";
		}
		
		if ($smarking == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/smarking".$style."_ani.gif',";
		}
		
		if ($netvouz == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/netvouz".$style."_ani.gif',";
		}
		
		if ($what == "1")
		{
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/what".$style."_ani.gif',";
		}
	
		$Social_Load .= "'".$mosConfig_live_site."/modules/mod_social_bookmark/load.gif'";
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function Social_Load() { 
var d=document; if(d.images){ if(!d.Social) d.Social=new Array();
var i,j=d.Social.length,a=Social_Load.arguments; for(i=0; i<a.length; i++)
if (a[i].indexOf("#")!=0){ d.Social[j]=new Image; d.Social[j++].src=a[i];}}
}
Social_Load(<? echo $Social_Load;?>)
function schnipp() { 
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function schnupp(n, d) { 
  var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=schnupp(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
  }
function schnapp() { 
  var i,j=0,x,a=schnapp.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
  if ((x=schnupp(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
  }
  //-->
</script>
	<?	}

switch ( $wong ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.mister-wong<? if ($wongl != "") { echo $wongl; } else { echo ".de"; } ?>/"  onclick="window.open('http://www.mister-wong<? if ($wongl != "") { echo $wongl; } else { echo ".de"; } ?>/index.php?action=addurl&amp;bm_url='+encodeURIComponent(location.href)+'&amp;bm_notice=<? echo $description;?>&amp;bm_description='+encodeURIComponent(document.title)+'&amp;bm_tags=<?echo $tags_space;?>');return false;" title="<? echo $alt;?> Mr. Wong"<?  if ($ani == "1") { ?> onmouseover="schnapp('wong','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/wong<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/wong".$style.".gif";?>" alt="<? echo $alt;?> Mr. Wong" name="wong" border="0" id="wong"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $webnews) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.webnews<? if ($webnewsl != "") { echo $webnewsl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.webnews<? if ($webnewsl != "") { echo $webnewsl; } else { echo ".de"; } ?>/einstellen?url='+encodeURIComponent(document.location)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<? echo $description;?>');return false;" title="<? echo $alt;?> Webnews"<?  if ($ani == "1") { ?> onmouseover="schnapp('Webnews','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/webnews<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/webnews".$style.".gif";?>" alt="<? echo $alt;?> Webnews" name="Webnews" border="0" id="Webnews"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $iciode) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.icio<? if ($iciol != "") { echo $iciol; } else { echo ".de"; } ?>/" onclick="window.open('http://www.icio<? if ($iciol != "") { echo $iciol; } else { echo ".de"; } ?>/add.php?url='+encodeURIComponent(location.href));return false;" title="<? echo $alt;?> Icio"<?  if ($ani == "1") { ?> onmouseover="schnapp('Icio','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/icio<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/icio".$style.".gif";?>" alt="<? echo $alt;?> Icio" name="Icio" border="0" id="Icio"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $oneview) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.oneview<? if ($oneviewl != "") { echo $oneviewl; } else { echo ".de"; } ?>/"  onclick="window.open('http://www.oneview<? if ($oneviewl != "") { echo $oneviewl; } else { echo ".de"; } ?>/quickadd/neu/addBookmark.jsf?URL='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<? echo $alt;?> Oneview"<?  if ($ani == "1") { ?> onmouseover="schnapp('Oneview','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/oneview<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/oneview".$style.".gif";?>" alt="<? echo $alt;?> Oneview" name="Oneview" border="0" id="Oneview"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $kledy ) {
	case 1:
		?>
<a rel="nofollow" style="text-decoration:none;" href="http://www.kledy<? if ($kledyl != "") { echo $kledyl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.kledy<? if ($kledyl != "") { echo $kledyl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));return false;" title="<? echo $alt;?> Kledy.de Social Bookmarking"<?  if ($ani == "1") { ?> onmouseover="schnapp('Kledy','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/kledy<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/kledy".$style.".gif";?>" alt="<? echo $alt;?> Kledy.de Social Bookmarking" name="Kledy" border="0" id="Kledy"/></a>
		<?
		break;
	case 2:
		break;
}





switch ( $favit ) {
	case 1:
		?>
<a rel="nofollow" style="text-decoration:none;" href="http://www.favit<? if ($favitl != "") { echo $favitl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.favit<? if ($favitl != "") { echo $favitl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));return false;" title="<? echo $alt;?> FAV!T Social Bookmarking"<?  if ($ani == "1") { ?> onmouseover="schnapp('Favit','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/favit<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/favit".$style.".gif";?>" alt="<? echo $alt;?>  FAV!T Social Bookmarking" name="Favit" border="0" id="Favit"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $favoriten) {
	case 1:
		?>
	    <a rel="nofollow" style="text-decoration:none;" href="http://www.favoriten<? if ($favoritenl != "") { echo $favoritenl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.favoriten<? if ($favoritenl != "") { echo $favoritenl; } else { echo ".de"; } ?>/url-hinzufuegen.html?bm_url='+encodeURIComponent(location.href)+'&amp;bm_title='+encodeURIComponent(document.title));return false;" title="<? echo $alt;?> Favoriten.de"<?  if ($ani == "1") { ?> onmouseover="schnapp('Favoriten','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/favoriten<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/favoriten".$style.".gif";?>" alt="<? echo $alt;?> Favoriten.de" name="Favoriten" border="0" id="Favoriten"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $seekxl ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://social-bookmarking.seekxl<? if ($seekxll != "") { echo $seekxll; } else { echo ".de"; } ?>/" onclick="window.open('http://social-bookmarking.seekxl<? if ($seekxll != "") { echo $seekxll; } else { echo ".de"; } ?>/?add_url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<? echo $alt;?> Seekxl"<?  if ($ani == "1") { ?> onmouseover="schnapp('Seekxl','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/seekxl<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/seekxl".$style.".gif";?>" alt="<? echo $alt;?> Seekxl" name="Seekxl" border="0" id="Seekxl"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $sbdk ) {
	case 1:
		?>
<a rel="nofollow" style="text-decoration:none;" href="http://www.social-bookmarking<? if ($sbdkl != "") { echo $sbdkl; } else { echo ".dk"; } ?>/" onclick="window.open('http://www.social-bookmarking<? if ($sbdkl != "") { echo $sbdkl; } else { echo ".dk"; } ?>/bookmarks/?action=add&amp;title='+encodeURIComponent(document.title)+'&amp;address='+(document.location.href));return false;" title="<? echo $alt;?> Social Bookmark Portal"<?  if ($ani == "1") { ?> onmouseover="schnapp('SocialBookmarkPortal','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/sbdk<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/sbdk".$style.".gif";?>" alt="<? echo $alt; ?> Social Bookmark Portal" name="SocialBookmarkPortal" border="0" id="SocialBookmarkPortal"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $boni ) {
	case 1:
		?>
	    <a rel="nofollow" style="text-decoration:none;" href="http://www.bonitrust<? if ($bonil != "") { echo $bonil; } else { echo ".de"; } ?>/" onclick="window.open('http://www.bonitrust<? if ($bonil != "") { echo $bonil; } else { echo ".de"; } ?>/account/bookmark/?bookmark_url='+unescape(location.href));return false;" title="<? echo $alt;?> BoniTrust"<?  if ($ani == "1") { ?> onmouseover="schnapp('BoniTrust','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/boni<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/boni".$style.".gif";?>" alt="<? echo $alt;?> BoniTrust" name="BoniTrust" border="0" id="BoniTrust"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $power ) {
	case 1:
		?>
	    <a rel="nofollow" style="text-decoration:none;" href="http://www.power-oldie<? if ($powerl != "") { echo $powerl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.power-oldie<? if ($powerl != "") { echo $powerl; } else { echo ".com"; } ?>/')" title="<? echo $alt;?> Power-Oldie"<?  if ($ani == "1") { ?> onmouseover="schnapp('PowerOldie','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/power<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/power".$style.".gif";?>" alt="<? echo $alt;?> Power-Oldie" name="PowerOldie" border="0" id="PowerOldie"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $bookmarkscc ) {
	case 1:
		?>
	    <a rel="nofollow" style="text-decoration:none;" href="http://www.bookmarks<? if ($powerl != "") { echo $powerl; } else { echo ".cc"; } ?>/" onclick="window.open('http://www.bookmarks<? if ($powerl != "") { echo $powerl; } else { echo ".cc"; } ?>/bookmarken.php?action=neu&amp;url='+(document.location.href)+'&amp;title='+(document.title));return false;" title="<? echo $alt;?> Bookmarks.cc"<?  if ($ani == "1") { ?> onmouseover="schnapp('Bookmarkscc','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/bookmarkscc<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/bookmarkscc".$style.".gif";?>" alt="<? echo $alt;?> Bookmarks.cc" name="Bookmarkscc" border="0" id="Bookmarkscc"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $newskick ) {
	case 1:
		?>
	    <a rel="nofollow" style="text-decoration:none;" href="http://www.newskick<? if ($newskickl != "") { echo $newskickl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.newskick<? if ($newskickl != "") { echo $newskickl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));return false;" title="<? echo $alt;?> Newskick"<?  if ($ani == "1") { ?> onmouseover="schnapp('Newskick','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/newskick<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/newskick".$style.".gif";?>" alt="<? echo $alt;?> Newskick" name="Newskick" border="0" id="Newskick"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $newsider ) {
	case 1:
		?>
	<a rel="nofollow" style="text-decoration:none;" href="http://www.newsider<? if ($newsiderl != "") { echo $newsiderl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.newsider<? if ($newsiderl != "") { echo $newsiderl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));return false;" title="<? echo $alt;?>  Newsider"<?  if ($ani == "1") { ?> onmouseover="schnapp('Newsider','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/newsider<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/newsider".$style.".gif";?>" alt="<? echo $alt;?> Newsider" name="Newsider" border="0" id="Newsider"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $linksilo ) {
	case 1:
		?>
			<a rel="nofollow" style="text-decoration:none;" href="http://www.linksilo<? if ($linksilol != "") { echo $linksilol; } else { echo ".de"; } ?>/" onclick="window.open('http://www.linksilo<? if ($linksilol != "") { echo $linksilol; } else { echo ".de"; } ?>/index.php?area=bookmarks&amp;func=bookmark_new&amp;addurl='+encodeURIComponent(location.href)+'&amp;addtitle='+encodeURIComponent(document.title));return false;" title="<? echo $alt;?> Linksilo"<?  if ($ani == "1") { ?> onmouseover="schnapp('Linksilo','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/linksilo<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/linksilo".$style.".gif";?>" alt="<? echo $alt;?> Linksilo" name="Linksilo" border="0" id="Linksilo"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $readster ) {
	case 1:
		?>
			<a rel="nofollow" style="text-decoration:none;" href="http://www.readster<? if ($readsterl != "") { echo $readsterl; } else { echo ".de"; } ?>/" onclick="window.open('http://www.readster<? if ($readsterl != "") { echo $readsterl; } else { echo ".de"; } ?>/submit/?url='+encodeURIComponent(document.location)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<? echo $alt;?> Readster"<?  if ($ani == "1") { ?> onmouseover="schnapp('Readster','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/readster<? echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<? echo $mosConfig_live_site."/modules/mod_social_bookmark/readster".$style.".gif";?>" alt="<? echo $alt;?> Readster" name="Readster" border="0" id="Readster"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $yigg ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://yigg<? if ($yiggl != "") { echo $yiggl; } else { echo ".de"; } ?>/" onclick="window.open('http://yigg<? if ($yiggl != "") { echo $yiggl; } else { echo ".de"; } ?>/neu?exturl='+encodeURIComponent(location.href));return false" title="<?echo $alt;?> Yigg"<?  if ($ani == "1") { ?> onmouseover="schnapp('Yigg','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/yigg<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/yigg".$style.".gif";?>" alt="<?echo $alt;?> Yigg" name="Yigg" border="0" id="Yigg"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $linkarena ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.linkarena<? if ($linkarenal != "") { echo $linkarenal; } else { echo ".com"; } ?>/" onclick="window.open('http://linkarena<? if ($linkarenal != "") { echo $linkarenal; } else { echo ".com"; } ?>/bookmarks/addlink/?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<?echo $description;?>&amp;tags=<?echo $tags_space;?>');return false;" title="<?echo $alt;?> Linkarena"<?  if ($ani == "1") { ?> onmouseover="schnapp('Linkarena','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/linkarena<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/linkarena".$style.".gif";?>" alt="<?echo $alt;?> Linkarena"  name="Linkarena" border="0" id="Linkarena"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $digg ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://digg<? if ($diggl != "") { echo $diggl; } else { echo ".com"; } ?>/" onclick="window.open('http://digg<? if ($diggl != "") { echo $diggl; } else { echo ".com"; } ?>/submit?phase=2&amp;url='+encodeURIComponent(location.href)+'&amp;bodytext=<?echo $description;?>&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Digg"<?  if ($ani == "1") { ?> onmouseover="schnapp('Digg','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/digg<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/digg".$style.".gif";?>" alt="<?echo $alt;?> Digg" name="Digg" border="0" id="Digg"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $icio ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://del.icio<? if ($iciol != "") { echo $iciol; } else { echo ".us"; } ?>/" onclick="window.open('http://del.icio<? if ($iciol != "") { echo $iciol; } else { echo ".us"; } ?>/post?v=2&amp;url='+encodeURIComponent(location.href)+'&amp;notes=<?echo $description;?>&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Del.icio.us"<?  if ($ani == "1") { ?> onmouseover="schnapp('Delicious','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/del<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/del".$style.".gif";?>" alt="<?echo $alt;?> Del.icoi.us" name="Delicious" border="0" id="Delicious"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $reddit ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://reddit<? if ($redditl != "") { echo $redditl; } else { echo ".com"; } ?>/" onclick="window.open('http://reddit<? if ($redditl != "") { echo $redditl; } else { echo ".com"; } ?>/submit?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Reddit"<?  if ($ani == "1") { ?> onmouseover="schnapp('Reddit','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/reddit<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/reddit".$style.".gif";?>" alt="<?echo $alt;?> Reddit" name="Reddit" border="0" id="Reddit"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $jumptags ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.jumptags<? if ($jumptagsl != "") { echo $jumptagsl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.jumptags<? if ($jumptagsl != "") { echo $jumptagsl; } else { echo ".com"; } ?>/add/?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Jjumptags.com"<?  if ($ani == "1") { ?> onmouseover="schnapp('Jumptags','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/jumptags<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/jumptags".$style.".gif";?>" alt="<?echo $alt;?> Jumptags" name="Jumptags" border="0" id="Jumptags"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $upchuckr ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.upchuckr<? if ($upchuckrl != "") { echo $upchuckrl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.upchuckr<? if ($upchuckrl != "") { echo $upchuckrl; } else { echo ".com"; } ?>/bookmarks.php/?action=add&amp;address='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Upchuckr"<?  if ($ani == "1") { ?> onmouseover="schnapp('Upchuckr','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/upchuckr<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/upchuckr".$style.".gif";?>" alt="<?echo $alt;?> Upchuckr" name="Upchuckr" border="0" id="Upchuckr"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $simpy ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.simpy<? if ($simpyl != "") { echo $simpyl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.simpy<? if ($simpyl != "") { echo $simpyl; } else { echo ".com"; } ?>/simpy/LinkAdd.do?title='+encodeURIComponent(document.title)+'&amp;tags=<?echo $tags;?>&amp;note=<?echo $description;?>&amp;href='+encodeURIComponent(location.href));return false;" title="<?echo $alt;?> Simpy"<?  if ($ani == "1") { ?> onmouseover="schnapp('Simpy','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/simpy<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/simpy".$style.".gif";?>" alt="<?echo $alt;?> Simpy" name="Simpy" border="0" id="Simpy"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $stumbleupon ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.stumbleupon<? if ($stumbleuponl != "") { echo $stumbleuponl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.stumbleupon<? if ($stumbleuponl != "") { echo $stumbleuponl; } else { echo ".com"; } ?>/submit?url='+encodeURIComponent(location.href)+'&amp;newcomment=<?echo $description;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> StumbleUpon"<?  if ($ani == "1") { ?> onmouseover="schnapp('StumbleUpon','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/stumbleupon<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/stumbleupon".$style.".gif";?>" alt="<?echo $alt;?> StumbleUpon" name="StumbleUpon" border="0" id="StumbleUpon"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $slashdot ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://slashdot<? if ($slashdotl != "") { echo $slashdotl; } else { echo ".org"; } ?>/" onclick="window.open('http://slashdot<? if ($slashdotl != "") { echo $slashdotl; } else { echo ".org"; } ?>/bookmark.pl?url='+encodeURIComponent(location.href)+'&amp;tags=<?echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Slashdot"<?  if ($ani == "1") { ?> onmouseover="schnapp('Slashdot','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/slashdot<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/slashdot".$style.".gif";?>" alt="<?echo $alt;?> Slashdot" name="Slashdot" border="0" id="Slashdot"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $netscape ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.netscape<? if ($netscapel != "") { echo $netscapel; } else { echo ".com"; } ?>/" onclick="window.open('http://www.netscape<? if ($netscapel != "") { echo $netscapel; } else { echo ".com"; } ?>/submit/?U='+encodeURIComponent(location.href)+'&amp;storyText=<?echo $description;?>&amp;storyTags=<?echo $tags;?>&amp;T='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Netscape"<?  if ($ani == "1") { ?> onmouseover="schnapp('Netscape','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/netscape<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/netscape".$style.".gif";?>" alt="<?echo $alt;?> Netscape" name="Netscape" border="0" id="Netscape"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $furl ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.furl<? if ($furll != "") { echo $furll; } else { echo ".net"; } ?>/" onclick="window.open('http://www.furl<? if ($furll != "") { echo $furll; } else { echo ".net"; } ?>/storeIt.jsp?u='+encodeURIComponent(location.href)+'&amp;keywords=<?echo $tags;?>&amp;t='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Furl"<?  if ($ani == "1") { ?> onmouseover="schnapp('Furl','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/furl<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/furl".$style.".gif";?>" alt="<?echo $alt;?> Furl" name="Furl" border="0" id="Furl"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $yahoo ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.yahoo<? if ($yahool != "") { echo $yahool; } else { echo ".com"; } ?>/" onclick="window.open('http://myweb2.search.yahoo<? if ($yahool != "") { echo $yahool; } else { echo ".com"; } ?>/myresults/bookmarklet?t='+encodeURIComponent(document.title)+'&amp;d=<?echo $description;?>&amp;tag=<?echo $tags?>&amp;u='+encodeURIComponent(location.href));return false;" title="<?echo $alt;?> Yahoo"<?  if ($ani == "1") { ?> onmouseover="schnapp('Yahoo','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/yahoo<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/yahoo".$style.".gif";?>" alt="<?echo $alt;?> Yahoo" name="Yahoo" border="0" id="Yahoo"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $blogmarks ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://blogmarks<? if ($blogmarksl != "") { echo $blogmarksl; } else { echo ".net"; } ?>/" onclick="window.open('http://blogmarks<? if ($blogmarksl != "") { echo $blogmarksl; } else { echo ".net"; } ?>/my/new.php?mini=1&amp;simple=1&amp;url='+encodeURIComponent(location.href)+'&amp;content=<?echo $description;?>&amp;public-tags=<?echo $tags?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Blogmarks"<?  if ($ani == "1") { ?> onmouseover="schnapp('Blogmarks','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/blogmarks<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/blogmarks".$style.".gif";?>" alt="<?echo $alt;?> Blogmarks" name="Blogmarks" border="0" id="Blogmarks"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $diigo ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.diigo<? if ($diigol != "") { echo $diigol; } else { echo ".com"; } ?>/" onclick="window.open('http://www.diigo<? if ($diigol != "") { echo $diigol; } else { echo ".com"; } ?>/post?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;tag=<?echo $tags?>&amp;comments=<?echo $description;?>'); return false;" title="<?echo $alt;?> Diigo"<?  if ($ani == "1") { ?> onmouseover="schnapp('Diigo','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/diigo<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/diigo".$style.".gif";?>" alt="<?echo $alt;?> Diigo" name="Diigo" border="0" id="Diigo"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $technorati ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.technorati<? if ($technoratil != "") { echo $technoratil; } else { echo ".com"; } ?>/" onclick="window.open('http://technorati<? if ($technoratil != "") { echo $technoratil; } else { echo ".com"; } ?>/faves?add='+encodeURIComponent(location.href)+'&amp;tag=<?echo $tags_space?>');return false;" title="<?echo $alt;?> Technorati"<?  if ($ani == "1") { ?> onmouseover="schnapp('Technorati','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/technorati<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/technorati".$style.".gif";?>" alt="<?echo $alt;?> Technorati" name="Technorati" border="0" id="Technorati"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $newsvine ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.newsvine<? if ($newsvinel != "") { echo $newsvinel; } else { echo ".com"; } ?>/" onclick="window.open('http://www.newsvine<? if ($newsvinel != "") { echo $newsvinel; } else { echo ".com"; } ?>/_wine/save?popoff=1&amp;u='+encodeURIComponent(location.href)+'&amp;tags=<?echo $tags?>&amp;blurb='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Newsvine"<?  if ($ani == "1") { ?> onmouseover="schnapp('Newsvine','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/newsvine<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/newsvine".$style.".gif";?>" alt="<?echo $alt;?> Newsvine" name="Newsvine" border="0" id="Newsvine"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $blinkbits ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinkbits<? if ($blinkbitsl != "") { echo $blinkbitsl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.blinkbits<? if ($blinkbitsl != "") { echo $blinkbitsl; } else { echo ".com"; } ?>/bookmarklets/save.php?v=1&amp;title='+encodeURIComponent(document.title)+'&amp;source_url='+encodeURIComponent(location.href)+'&amp;source_image_url=&amp;rss_feed_url=&amp;rss_feed_url=&amp;rss2member=&amp;body=<?echo $description;?>');return false;" title="<?echo $alt;?> Blinkbits"<?  if ($ani == "1") { ?> onmouseover="schnapp('Blinkbits','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/blinkbits<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/blinkbits".$style.".gif";?>" alt="<?echo $alt;?> Blinkbits" name="Blinkbits" border="0" id="Blinkbits"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $magnolia ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://ma.gnolia<? if ($magnolial != "") { echo $magnolial; } else { echo ".com"; } ?>/" onclick="window.open('http://ma.gnolia<? if ($magnolial != "") { echo $magnolial; } else { echo ".com"; } ?>/bookmarklet/add?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>');return false;" title="<?echo $alt;?> Ma.Gnolia"<?  if ($ani == "1") { ?> onmouseover="schnapp('MaGnolia','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/ma.gnolia<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/ma.gnolia".$style.".gif";?>" alt="<?echo $alt;?> Ma.Gnolia" name="MaGnolia" border="0" id="MaGnolia"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $smarking ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://smarking<? if ($smarkingl != "") { echo $smarkingl; } else { echo ".com"; } ?>/" onclick="window.open('http://smarking<? if ($smarkingl != "") { echo $smarkingl; } else { echo ".com"; } ?>/editbookmark/?url='+ location.href +'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>');return false;" title="<?echo $alt;?> Smarking"<?  if ($ani == "1") { ?> onmouseover="schnapp('Smarking','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/smarking<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/smarking".$style.".gif";?>" alt="<?echo $alt;?> Smarking" name="Smarking" border="0" id="Smarking"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $netvouz ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.netvouz<? if ($netvouzl != "") { echo $netvouzl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.netvouz<? if ($netvouzl != "") { echo $netvouzl; } else { echo ".com"; } ?>/action/submitBookmark?url='+encodeURIComponent(location.href)+'&amp;description=<?echo $description;?>&amp;tags=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;popup=yes');return false;" title="<?echo $alt;?> Netvouz"<?  if ($ani == "1") { ?> onmouseover="schnapp('Netvouz','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/netvouz<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/netvouz".$style.".gif";?>" alt="<?echo $alt;?> Netvouz" name="Netvouz" border="0" id="Netvouz"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $folkd ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.folkd<? if ($folkdl != "") { echo $folkdl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.folkd<? if ($folkdl != "") { echo $folkdl; } else { echo ".com"; } ?>/page/submit.html?step2_sent=1&amp;url='+encodeURIComponent(location.href)+'&amp;check=page&amp;add_title='+encodeURIComponent(document.title)+'&amp;add_description=<?echo $description;?>&amp;add_tags_show=&amp;add_tags=<?echo $tags_semi;?>&amp;add_state=public');return false;" title="<?echo $alt;?> Folkd"<?  if ($ani == "1") { ?> onmouseover="schnapp('Folkd','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/folkd<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/folkd".$style.".gif";?>" alt="<?echo $alt;?> Folkd" name="Folkd" border="0" id="Folkd"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $spurl ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.spurl<? if ($spurll != "") { echo $spurll; } else { echo ".net"; } ?>/" onclick="window.open('http://www.spurl<? if ($spurll != "") { echo $spurll; } else { echo ".net"; } ?>/spurl.php?v=3&amp;tags=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(document.location.href));return false;" title="<?echo $alt;?> Spurl"<?  if ($ani == "1") { ?> onmouseover="schnapp('Spurl','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/spurl<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/spurl".$style.".gif";?>" alt="<?echo $alt;?> Spurl" name="Spurl" border="0" id="Spurl"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $google ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.google<? if ($googlel != "") { echo $googlel; } else { echo ".com"; } ?>/" onclick="window.open('http://www.google<? if ($googlel != "") { echo $googlel; } else { echo ".com"; } ?>/bookmarks/mark?op=add&amp;hl=de&amp;bkmk='+encodeURIComponent(location.href)+'&amp;annotation=<?echo $description;?>&amp;labels=<?echo $tags;?>&amp;title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Google"<?  if ($ani == "1") { ?> onmouseover="schnapp('Google','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/google<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/google".$style.".gif";?>" alt="<?echo $alt;?> Google" name="Google" border="0" id="Google"/></a>
		<?
		break;
	case 2:
		break;
}


switch ( $blinklist ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinklist<? if ($blinklistl != "") { echo $blinklistl; } else { echo ".com"; } ?>/" onclick="window.open('http://www.blinklist<? if ($blinklistl != "") { echo $blinklistl; } else { echo ".com"; } ?>/index.php?Action=Blink/addblink.php&amp;Description=<?echo $description;?>&amp;Tag=<?echo $tags;?>&amp;Url='+encodeURIComponent(location.href)+'&amp;Title='+encodeURIComponent(document.title));return false;" title="<?echo $alt;?> Blinklist"<?  if ($ani == "1") { ?> onmouseover="schnapp('Blinklist','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/blinklist<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/blinklist".$style.".gif";?>" alt="<?echo $alt;?> Blinklist" name="Blinklist" border="0" id="Blinklist"/></a>
		<?
		break;
	case 2:
		break;
}

switch ( $linktype ) {
	case 1:
		$target= "_Blank";
		break;
	case 2:
		$target= "_Self";
		break;
}

switch ( $what ) {
	case 1:
		?>
		<a rel="nofollow" style="text-decoration:none;" href="<? echo $whatlink; ?>" target="<? echo $target; ?>" title="Information"<?  if ($ani == "1") { ?> onmouseover="schnapp('Information','','<? echo $mosConfig_live_site;?>/modules/mod_social_bookmark/what<?echo $style;?>_ani.gif',1)" onmouseout="schnipp()" <? }?>><img style="padding-bottom:1px;" src="<?echo $mosConfig_live_site."/modules/mod_social_bookmark/what".$style.".gif";?>" alt="Information" name="Information" border="0" id="Information"/></a>
		<?
		break;
	case 2:
		break;
}

?><br/>
</div>