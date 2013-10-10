<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
include_once($mosConfig_absolute_path . "/templates/" . $cur_template . '/lib/php/yt_settings.php');
include_once($mosConfig_absolute_path . "/templates/" . $cur_template . '/lib/php/yt_splitmenu.php');
include_once($mosConfig_absolute_path . "/templates/" . $cur_template . '/lib/php/yt_styleswitcher.php');
include_once($mosConfig_absolute_path . "/templates/" . $cur_template . '/lib/php/yt_snap.php');

/*** template parameters ***/
$template_parameters = array(
	/* color variation */
	"color"               => "default",      /* default (white) | black */
	/* item color variation */
	"item1"               => "red",          /* default (black) | red | blue | green | yellow | lilac */
	"item2"               => "blue",         /* default (black) | red | blue | green | yellow | lilac */
	"item3"               => "lilac",        /* default (black) | red | blue | green | yellow | lilac */
	"item4"               => "yellow",       /* default (black) | red | blue | green | yellow | lilac */
	"item5"               => "blue",        /* default (black) | red | blue | green | yellow | lilac */
	"item6"               => "green",      /* default (black) | red | blue | green | yellow | lilac */
	"item7"               => "blue",      /* default (black) | red | blue | green | yellow | lilac */
	"item8"               => "red",      /* default (black) | red | blue | green | yellow | lilac */
	"item9"               => "default",      /* default (black) | red | blue | green | yellow | lilac */
	"item10"              => "default",      /* default (black) | red | blue | green | yellow | lilac */
	/* layout */
	"dogear"              => true,           /* true | false */
	"date"    	          => true,           /* true | false */
	"styleswitcherFont"   => true,           /* true | false */
	"styleswitcherWidth"  => true,           /* true | false */
	"layout"              => "left",         /* left | right */ 
	/* features */
	"lightbox"            => true,           /* true | false */
	"reflection"          => true,           /* true | false */
	"snap"                => false,          /* true | false */
	/* style switcher */
	"fontDefault"         => "font-medium",  /* font-small | font-medium | font-large */
	"widthDefault"        => "width-wide",   /* width-thin | width-wide | width-fluid */
	"widthThinPx"         => 960,            /* template width for style "width-thin", (pixels) */
	"widthWidePx"         => 980,            /* template width for style "width-wide", (pixels) */
	"widthFluidPx"        => 0.8,            /* template width for style "width-fluid", (0.9 means 90%) */
	/* top panel */
	"toppanel"            => true,           /* true | false */
	"heightToppanel"      => 320,            /* height of the sliding toppanel, (pixels) */
	/* text */
	"textToppanel"        => "Top Panel",    /* text label for the toppanel */
	/* javascript */
	"loadJavascript"      => true            /* true | false, set this to enable/disable all the templates javascripts */
);

// initialize settings, styleswitcher, splitmenu
$ytSettings      = new YtSettings($template_parameters);
$ytStyleSwitcher = new YtStyleSwitcher($template_parameters);
$ytMainMenu      = new YtSplitMenu("mainmenu", "mainmenu");
$ytTopMenu       = new YtSplitMenu("topmenu", "topnmenu");
$ytOtherMenu     = new YtSplitMenu("othermenu", "othermenu");
$ytUserMenu     = new YtSplitMenu("usermenu", "usermenu");

// set css-class for topbox
$topmodules = 0;
if(mosCountModules('user1')) $topmodules += 1;
if(mosCountModules('user2')) $topmodules += 1;
if(mosCountModules('user3')) $topmodules += 1;
switch ($topmodules) {
	case 1:
		$topboxwidth = "width100";
		break;
	case 2:
		$topboxwidth = "width50";
		break;
	case 3:
		$topboxwidth = "width33";
		break;
	default:
		$topboxwidth = "";
}

// set css-class for maintopbox
$maintopmodules = 0;
if(mosCountModules('user4')) $maintopmodules += 1;
if(mosCountModules('user5')) $maintopmodules += 1;
switch ($maintopmodules) {
	case 1:
		$maintopboxwidth = "width100";
		break;
	case 2:
		$maintopboxwidth = "width50";
		break;
	default:
		$maintopboxwidth = "";
}

// set css-class for contenttopbox
$contenttopmodules = 0;
if(mosCountModules('advert1')) $contenttopmodules += 1;
if(mosCountModules('advert2')) $contenttopmodules += 1;
switch ($contenttopmodules) {
	case 1:
		$contenttopboxwidth = "width100";
		break;
	case 2:
		$contenttopboxwidth = "width50";
		break;
	default:
		$contenttopboxwidth = "";
}

// set css-class for contentbottombox
$contentbottommodules = 0;
if(mosCountModules('advert3')) $contentbottommodules += 1;
if(mosCountModules('advert4')) $contentbottommodules += 1;
switch ($contentbottommodules) {
	case 1:
		$contentbottomboxwidth = "width100";
		break;
	case 2:
		$contentbottomboxwidth = "width50";
		break;
	default:
		$contentbottomboxwidth = "";
}

// set css-class for mainbottombox
$mainbottommodules = 0;
if(mosCountModules('user6')) $mainbottommodules += 1;
if(mosCountModules('user7')) $mainbottommodules += 1;
switch ($mainbottommodules) {
	case 1:
		$mainbottomboxwidth = "width100";
		break;
	case 2:
		$mainbottomboxwidth = "width50";
		break;
	default:
		$mainbottomboxwidth = "";
}

// set css-class for bottombox
$bottommodules = 0;
if(mosCountModules('user8')) $bottommodules += 1;
if(mosCountModules('bottom')) $bottommodules += 1;
if(mosCountModules('user9')) $bottommodules += 1;
switch ($bottommodules) {
	case 1:
		$bottomboxwidth = "width100";
		break;
	case 2:
		$bottomboxwidth = "width50";
		break;
	case 3:
		$bottomboxwidth = "width33";
		break;
	default:
		$bottomboxwidth = "";
}

// set css-class for topbox seperators
$topbox12seperator = "";
$topbox23seperator = "";
if (mosCountModules('user1') && (mosCountModules('user2') || mosCountModules('user3'))) {
	$topbox12seperator = "topboxseperator";
}
if (mosCountModules('user2') && mosCountModules('user3')) {
	$topbox23seperator = "topboxseperator";
}

// set css-class for maintopbox seperators
$maintopbox12seperator = "";
if (mosCountModules('user4') && mosCountModules('user5')) {
	$maintopbox12seperator = "maintopboxseperator";
}

// set css-class for mainbottombox seperators
$mainbottombox12seperator = "";
if (mosCountModules('user6') && mosCountModules('user7')) {
	$mainbottombox12seperator = "mainbottomboxseperator";
}

// set css-class for contenttopbox seperators
$contenttopbox12seperator = "";
if (mosCountModules('advert1') && mosCountModules('advert2')) {
	$contenttopbox12seperator = "contenttopboxseperator";
}

// set css-class for contentbottombox seperators
$contentbottombox12seperator = "";
if (mosCountModules('advert3') && mosCountModules('advert4')) {
	$contentbottombox12seperator = "contentbottomboxseperator";
}

// set css-class for bottombox seperators
$bottombox12seperator = "";
$bottombox23seperator = "";
if (mosCountModules('user8') && (mosCountModules('bottom') || mosCountModules('user9'))) {
	$bottombox12seperator = "bottomboxseperator";
}
if (mosCountModules('bottom') && mosCountModules('user9')) {
	$bottombox23seperator = "bottomboxseperator";
}

// set css-class for layoutstyle
if(mosCountModules('left') || ($ytMainMenu->getMenu(2, -1) != "") || ($ytOtherMenu->getMenu(1, -1) != "") || ($ytUserMenu->getMenu(1, -1) != "")) {
	if($ytSettings->get('layout') == "left") {
		$layoutstyle = "layoutleft";
	} else {
		$layoutstyle = "layoutright";
	}
} else {
	$layoutstyle = "withoutleft";
}

// set css-class for rightbackground
if(mosCountModules('right')) {
	$rightbackground = "withright";
} else {
	$rightbackground = "withoutright";
}

// set color (depend on item)
$itemcolor = "";
if ($ytMainMenu->getActiveMenuItemNumber(1) != "-1") {
	if ($ytSettings->get('item' . $ytMainMenu->getActiveMenuItemNumber(1)) != "default") {
		$itemcolor = $ytSettings->get('item' . $ytMainMenu->getActiveMenuItemNumber(1));
	}
}

// needed to seperate the ISO number from the language file constant _ISO
$iso = explode( '=', _ISO );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo _LANGUAGE; ?>" xml:lang="<?php echo _LANGUAGE; ?>">
<head>
<?php mosShowHead(); ?>
<?php
if ( $my->id ) {
	initEditor();
}
?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<?php if($ytSettings->get('color') != "default") { ?>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/<?php echo $ytSettings->get('color'); ?>/<?php echo $ytSettings->get('color'); ?>-template_css.css" rel="stylesheet" type="text/css" />
<?php } ?>
<?php if($ytSettings->get('lightbox')) { ?>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/lightbox/css/slimbox.css" rel="stylesheet" type="text/css" />
<?php } ?>
<!--[if lte IE 7]>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/iehacks.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 7]>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/ie7hacks.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/ie6hacks.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php if($ytSettings->get('color') != "default") { ?>
<!--[if IE 6]>
<link href="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/css/<?php echo $ytSettings->get('color'); ?>/<?php echo $ytSettings->get('color'); ?>-ie6hacks.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php } ?>

<?php if($ytSettings->get('loadJavascript')) { ?>
<script language="javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/mootools/mootools.js" type="text/javascript"></script>
<?php if($ytSettings->get('reflection')) { ?>
<script language="javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/reflection/reflection.js" type="text/javascript"></script>
<?php } ?>
<?php if($ytSettings->get('lightbox')) { ?>
<script language="javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/lightbox/slimbox.js" type="text/javascript"></script>
<?php } ?>
<script language="javascript" type="text/javascript"><?php $ytSettings->showJavaScript(); ?></script>
<script language="javascript" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/yt_tools.js" type="text/javascript"></script>
<!--[if lt IE 7]>
<script language="javascript" defer src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/lib/js/yt_ie6fix.js" type="text/javascript"></script>
<![endif]-->
<?php
	if($ytSettings->get('snap')) {
		$js_snap = new YtSnap();
		$js_snap->enableSnap();
	}
?>
<?php } ?>
<noscript><div style="padding:2px; background-color:#cc0066; font-height:bold; color:white; border:1px #cc0099 solid;">Javascript must be enabled in your browser to use this page.<br />Please enable Javascript under your Tools menu in your browser.<br />Once javascript is enabled <a href="index.php">Click here</a> to go back to MESIN TEMPEL YAMAHA - Dealer Yamaha Outboard Motor Indonesia</div></noscript></head>

<body id="page" class="<?php echo $ytStyleSwitcher->getCurrentStyle(); ?> <?php echo $itemcolor; ?>">

	<?php if($ytSettings->get('dogear')) { ?>
	<div id="dogear">
		<a href="<?php echo $mosConfig_live_site;?>" target="_self" title=".::OUTBOARD MOTOR YAMAHA::."><img class="correct-png" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/images/yoo_dogear.png" alt=".::OUTBOARD MOTOR YAMAHA::." /></a>
	</div>
	<?php } ?>

	<?php if($ytSettings->get('toppanel') && mosCountModules('cpanel')) { ?>
	<div id="toppanel-container">
			
		<div id="toppanel-wrapper">
			<div id="toppanel">
				<div class="panel">
						
					<div class="close">
						close
					</div>
							
					<div class="cpanel">
						<?php mosLoadModules('cpanel', -2); ?>
					</div>
									
				</div>
			</div>
		</div>
							
		<div class="trigger">
			<div class="trigger-l"><img class="correct-png" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/images/toppanel_trigger_l.png" alt="Top Panel" /></div>
			<div class="trigger-m"><?php echo $ytSettings->get('textToppanel'); ?></div>
			<div class="trigger-r"><img class="correct-png" src="<?php echo $mosConfig_live_site;?>/templates/<?php echo $cur_template; ?>/images/toppanel_trigger_r.png" alt="Top Panel" /></div>
		</div>
					
	</div>
	<?php } ?>

	<div id="page-header">
		<div class="wrapper floatholder">
	
			<div id="header">
	
				 <a href="<?php echo $mosConfig_live_site;?>"><img id="logo" class="correct-png" src="<?php echo $mosConfig_live_site;?>/images/stories/logo_yamaha_out_2007_md.gif" alt="Home" title="Home" /></a> 
				
				<?php if($ytSettings->get('styleswitcherFont') || $ytSettings->get('styleswitcherWidth')) { ?>
				<div id="styleswitcher">
					<?php if($ytSettings->get('styleswitcherWidth')) { ?>
					<a id="switchwidthfluid" href="javascript:void(0)" title="Fluid width"></a>
					<a id="switchwidthwide" href="javascript:void(0)" title="Wide width"></a>
					<a id="switchwidththin" href="javascript:void(0)" title="Thin width"></a>
					<?php } ?>
					<?php if($ytSettings->get('styleswitcherFont')) { ?>
					<span class="spacer"></span>
					<a id="switchfontlarge" href="javascript:void(0)" title="Increase font size"></a>
					<a id="switchfontmedium" href="javascript:void(0)" title="Default font size"></a>
					<a id="switchfontsmall" href="javascript:void(0)" title="Decrease font size"></a>
					<?php } ?>
				</div>
				<?php } ?>
	
				<div id="topmenu">
				
					<?php if($ytSettings->get('date')) { ?>
					<div id="date">
						<?php echo (strftime(_DATE_FORMAT_LC, time() + ($mosConfig_offset * 60 * 60))); ?>
					</div>
					<?php } ?>
					
					<?php 
						$ytTopMenu->showMenu(1, 1);
					 ?>
					
				</div>
	
				<?php if(mosCountModules('inset')) { ?>
				<div id="inset">
					<?php mosLoadModules('inset', -1); ?>
				</div>
				<?php } ?>
	
				<?php if(mosCountModules('top')) { ?>
				<div id="topmodule">
					<?php mosLoadModules('top', -1); ?>
				</div>
				<?php } ?>
										
				<div id="menu">
					
					<div class="overlay"></div>
					
					<?php 
						$ytMainMenu->showMenu(1, 1);
					 ?>
				</div>
							
			</div>
		
		</div>
	</div>
	
	<div id="page-body">
		<div class="wrapper floatholder">
		
			<?php if(mosCountModules('user1') || mosCountModules('user2') || mosCountModules('user3')) { ?>
			<div id="top">
				<div class="floatbox ie_fix_floats">
				
					<?php if(mosCountModules('user1')) { ?>
					<div class="topbox <?php echo $topboxwidth; ?> <?php echo $topbox12seperator; ?> float-left">
						<?php mosLoadModules('user1', -2); ?>
					</div>
					<?php } ?>
												
					<?php if(mosCountModules('user2')) { ?>
					<div class="topbox <?php echo $topboxwidth; ?> <?php echo $topbox23seperator; ?> float-left">
						<?php mosLoadModules('user2', -2); ?>
					</div>
					<?php } ?>
													
					<?php if(mosCountModules('user3')) { ?>
					<div class="topbox <?php echo $topboxwidth; ?> float-left">
						<?php mosLoadModules('user3', -2); ?>
					</div>
					<?php } ?>
				
				</div>
			</div>
			<?php } ?>
						
			<div id="middle">
				<div class="background <?php echo $layoutstyle; ?>">
				
					<?php if(mosCountModules('left') || ($ytMainMenu->getMenu(2, -1) != "") || ($ytOtherMenu->getMenu(1, -1) != "") || ($ytUserMenu->getMenu(1, -1) != "")) { ?>
					<div id="left">
						<div id="left_container" class="clearingfix">
		
							<?php if($ytMainMenu->getMenu(2, -1) != "") { ?>
							<div id="submenu">
								<?php
								$ytMainMenu->setAccordionStyleForLevel(2);
								$ytMainMenu->setListitemBackgroundImage(true);							
								$ytMainMenu->showMenu(2, -1);
								$ytMainMenu->setAccordionStyleForLevel(false);
								$ytMainMenu->setListitemBackgroundImage(false);					
								?>
							</div>
							<?php } ?>
							
							<?php if(($ytOtherMenu->getMenu(1, -1) != "") || ($ytUserMenu->getMenu(1, -1) != "")) { ?>
							<div id="submenu-static">
								<?php
								$ytOtherMenu->setAccordionStyleForLevel(1);
								$ytOtherMenu->setListitemBackgroundImage(true);							
								$ytOtherMenu->showMenu(1, -1);
								$ytOtherMenu->setAccordionStyleForLevel(false);
								$ytOtherMenu->setListitemBackgroundImage(false);					
								?>
								
								<?php
								$ytUserMenu->setAccordionStyleForLevel(1);
								$ytUserMenu->setListitemBackgroundImage(true);							
								$ytUserMenu->showMenu(1, -1);
								$ytUserMenu->setAccordionStyleForLevel(false);
								$ytUserMenu->setListitemBackgroundImage(false);					
								?>
							</div>
							<?php } ?>
				
							<div class="container">
								<?php mosLoadModules('left', -2); ?>
							</div>
	
						</div>
					</div>
					<?php } ?>
				
					<div id="main">
						<div id="main_container" class="clearingfix">
		
							<?php if(mosCountModules('user4') || mosCountModules('user5')) { ?>
							<div id="maintop" class="floatbox">
				
								<?php if(mosCountModules('user4')) { ?>
								<div class="maintopbox <?php echo $maintopboxwidth; ?> <?php echo $maintopbox12seperator; ?> float-left">
									<?php mosLoadModules('user4', -2); ?>
								</div>
								<?php } ?>
				
								<?php if(mosCountModules('user5')) { ?>
								<div class="maintopbox <?php echo $maintopboxwidth; ?> float-left">
									<?php mosLoadModules('user5', -2); ?>
								</div>
								<?php } ?>
				
							</div>
							<?php } ?>
							
							<div id="mainmiddle" class="floatbox <?php echo $rightbackground; ?>">
				
								<?php if(mosCountModules('right')) { ?>
								<div id="right">
									<div id="right_container" class="clearingfix">
										<?php mosLoadModules('right', -2); ?>
									</div>
								</div>
								<?php } ?>
				
								<div id="content">
									<div id="content_container" class="clearingfix">
	
										<?php if(mosCountModules('advert1') || mosCountModules('advert2')) { ?>
										<div id="contenttop" class="floatbox">
							
											<?php if(mosCountModules('advert1')) { ?>
											<div class="contenttopbox left <?php echo $contenttopboxwidth; ?> <?php echo $contenttopbox12seperator; ?> float-left">
												<?php mosLoadModules('advert1', -2); ?>
											</div>
											<?php } ?>
							
											<?php if(mosCountModules('advert2')) { ?>
											<div class="contenttopbox right <?php echo $contenttopboxwidth; ?> float-left">
												<?php mosLoadModules('advert2', -2); ?>
											</div>
											<?php } ?>
							
										</div>
										<?php } ?>
	
										<div id="breadcrumb">
											<?php mosPathWay(); ?>
										</div>
				
										<div class="floatbox">
											<div id="jxajaxloaderdisplay"></div><div id="jxajaxmaindisplay" class="jqxmainbody"><?php mosMainBody(); ?></div>
										</div>
													
										<?php if(mosCountModules('advert3') || mosCountModules('advert4')) { ?>
										<div id="contentbottom" class="floatbox">
							
											<?php if(mosCountModules('advert3')) { ?>
											<div class="contentbottombox left <?php echo $contentbottomboxwidth; ?> <?php echo $contentbottombox12seperator; ?> float-left">
												<?php mosLoadModules('advert3', -2); ?>
											</div>
											<?php } ?>
							
											<?php if(mosCountModules('advert4')) { ?>
											<div class="contentbottombox right <?php echo $contentbottomboxwidth; ?> float-left">
												<?php mosLoadModules('advert4', -2); ?>
											</div>
											<?php } ?>
							
										</div>
										<?php } ?>
	
									</div>
								</div>
				
							</div>
					
							<?php if(mosCountModules('user6') || mosCountModules('user7')) { ?>
							<div id="mainbottom" class="floatbox">
				
								<?php if(mosCountModules('user6')) { ?>
								<div class="mainbottombox <?php echo $mainbottomboxwidth; ?> <?php echo $mainbottombox12seperator; ?> float-left">
									<?php mosLoadModules('user6', -2); ?>
								</div>
								<?php } ?>
			
								<?php if(mosCountModules('user7')) { ?>
								<div class="mainbottombox <?php echo $mainbottomboxwidth; ?> float-left">
									<?php mosLoadModules('user7', -2); ?>
								</div>
								<?php } ?>
				
							</div>
							<?php } ?>
	
						</div>
					</div>
		
				</div>
			</div>
	
		</div>
	</div>	
		
	<div id="page-footer">
		<div class="wrapper floatholder">
		
			<?php if(mosCountModules('user8') || mosCountModules('bottom') || mosCountModules('user9')) { ?>
			<div id="bottom">
				<div class="floatbox ie_fix_floats">
				
				<?php if(mosCountModules('user8')) { ?>
				<div class="bottombox <?php echo $bottomboxwidth; ?> <?php echo $bottombox12seperator; ?> float-left">
					<?php mosLoadModules('user8', -2); ?>
				</div>
				<?php } ?>
																		
				<?php if(mosCountModules('bottom')) { ?>
				<div class="bottombox <?php echo $bottomboxwidth; ?> <?php echo $bottombox23seperator; ?> float-left">
					<?php mosLoadModules('bottom', -2); ?>
				</div>
				<?php } ?>
																		
				<?php if(mosCountModules('user9')) { ?>
				<div class="bottombox <?php echo $bottomboxwidth; ?> float-left">
					<?php mosLoadModules('user9', -2); ?>
				</div>
				<?php } ?>
				
				</div>
			</div>
			<?php } ?>
						
			<div id="footer">
			<?php if(mosCountModules('footer')) { ?>
				<?php mosLoadModules('footer', -1); ?>
			<?php } ?>
			</div>
				
			<?php // mosLoadModules( 'debug', -1 );?>
	
		</div>
	</div>
        <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4238623-20");
pageTracker._trackPageview();
} catch(err) {}</script>
      

</body>
</html>