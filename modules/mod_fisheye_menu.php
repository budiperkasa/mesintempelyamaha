<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$fmenu_align 		= $params->get( 'fmenu_align' );
$fmenu_itemwidth 		= $params->get( 'fmenu_itemwidth' );
$fmenu_itemmaxwidth 		= $params->get( 'fmenu_itemmaxwidth' );
$fmenu_proximity 		= $params->get( 'fmenu_proximity' );
$txtcolor		= $params->get( 'txtcolor' );
$fontsize		= $params->get( 'fontsize' );

$menu_status1 	= $params->get( 'menu_status1' );
$menu_img1 		= $params->get( 'menu_img1' );
$menu_url1 		= $params->get( 'menu_url1' );
$menu_txt1 		= $params->get( 'menu_txt1' );

$menu_status2 	= $params->get( 'menu_status2' );
$menu_img2		= $params->get( 'menu_img2' );
$menu_url2 		= $params->get( 'menu_url2' );
$menu_txt2 		= $params->get( 'menu_txt2' );

$menu_status3 	= $params->get( 'menu_status3' );
$menu_img3		= $params->get( 'menu_img3' );
$menu_url3 		= $params->get( 'menu_url3' );
$menu_txt3 		= $params->get( 'menu_txt3' );

$menu_status4 	= $params->get( 'menu_status4' );
$menu_img4		= $params->get( 'menu_img4' );
$menu_url4 		= $params->get( 'menu_url4' );
$menu_txt4 		= $params->get( 'menu_txt4' );

$menu_status5 	= $params->get( 'menu_status5' );
$menu_img5		= $params->get( 'menu_img5' );
$menu_url5 		= $params->get( 'menu_url5' );
$menu_txt5 		= $params->get( 'menu_txt5' );

$menu_status6 	= $params->get( 'menu_status6' );
$menu_img6		= $params->get( 'menu_img6' );
$menu_url6 		= $params->get( 'menu_url6' );
$menu_txt6 		= $params->get( 'menu_txt6' );

$menu_status7 	= $params->get( 'menu_status7' );
$menu_img7		= $params->get( 'menu_img7' );
$menu_url7 		= $params->get( 'menu_url7' );
$menu_txt7 		= $params->get( 'menu_txt7' );


?>
<!-- FishEye Menu by http://www.templateplazza.com -->
<div id="fisheye" class="fisheye">
<link rel="stylesheet" href="modules/mac_menu/menu.css" type="text/css" />
<script type="text/javascript" src="modules/mac_menu/jquery.js"></script>
<script type="text/javascript" src="modules/mac_menu/fisheye.js"></script>
<script type="text/javascript" src="modules/mac_menu/iutil.js"></script>
<script>jQuery.noConflict();</script>
		<div  class="fisheyeContainter">
		<?php if ($menu_status1==1) { ?>
			<a href="<?php echo $menu_url1 ?>" class="fisheyeItem">
				<img alt="" style="behavior: url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img1 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt1 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status2==1) { ?>
			<a href="<?php echo $menu_url2 ?>" class="fisheyeItem">
				<img alt="" style="behavior: url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img2 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt2 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status3==1) { ?>
			<a href="<?php echo $menu_url3 ?>" class="fisheyeItem">
				<img alt="" style="behavior: url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img3 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt3 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status4==1) { ?>
			<a href="<?php echo $menu_url4 ?>" class="fisheyeItem">
				<img alt="" style="behavior: url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img4 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt4 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status5==1) { ?>
			<a href="<?php echo $menu_url5 ?>" class="fisheyeItem">
				<img alt="" style="behavior: url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img5 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt5 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status6==1) { ?>
			<a href="<?php echo $menu_url6 ?>" class="fisheyeItem">
				<img alt="" style="behavior:url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img6 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt6 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($menu_status7==1) { ?>
			<a href="<?php echo $menu_url7 ?>" class="fisheyeItem">
				<img alt="" style="behavior:url('modules/mac_menu/png.htc');" src="modules/mac_menu/images/<?php echo $menu_img7 ?>" width="30"/>
				<span style="display: none;color:<?php echo $txtcolor ?>;font-size:<?php echo $fontsize ?>;"><?php echo $menu_txt7 ?></span>
			</a>
			<?php } else { ?>
			<?php } ?>
			
			<script type="text/javascript">
			jQuery(document).ready(
				function()
				{
					jQuery('#fisheye').Fisheye(
						{
						maxWidth: <?php echo $fmenu_itemmaxwidth ?>,
						items: 'a',
						itemsText: 'span',
						container: '.fisheyeContainter',
						itemWidth: <?php echo $fmenu_itemwidth ?>,
						proximity: <?php echo $fmenu_proximity ?>,
						alignment : 'left',
						halign : '<?php echo $fmenu_align ?>'
						}
					)
				}
			);

		</script>
		</div>
</div>

