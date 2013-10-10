<?php
defined('_VALID_MOS') or die('Restricted access');
global $mosConfig_offset, $mosConfig_live_site, $mainframe, $mosConfig_lang, $mosConfig_absolute_path;
$basePath = dirname(__file__);
$function_path = $basePath . '/mod_cd_latestnews/utils/php/functions.php';
$lang_path = $basePath . '/mod_cd_latestnews/languages/' . $mosConfig_lang .
    '.php';
$lang_path_default = $basePath . '/mod_cd_latestnews/languages/english.php';

if (file_exists($function_path))
{
    require_once ($function_path);
} else
{
    die('<h3>Core Design Latest news module: I can\'t load a function file.</h3>');
}
if (file_exists($lang_path))
{
    require_once ($lang_path);
} else
{
    require_once ($lang_path_default);
}
// end

$type = intval($params->get('type', 1));
$count = intval($params->get('count', 5));
$catid = trim($params->get('catid'));
$secid = trim($params->get('secid'));
$show_front = $params->get('show_front', 1);

$now = _CURRENT_SERVER_TIME;
$access = !$mainframe->getCfg('shownoauth');
$nullDate = $database->getNullDate();

$cd_latestnews_html_content_width = $params->def('cd_latestnews_html_content_width',
    '400px');

$cd_latestnews_set_title = $params->def('cd_latestnews_set_title', 'title');
$cd_latestnews_titlelimit = $params->def('cd_latestnews_titlelimit', '0');

$outlineType = $params->def('outlineType', 'rounded-white');
$align = $params->def('align', 'auto');
$anchor = $params->def('anchor', 'auto');
$dimmingOpacity = $params->def('dimmingOpacity', '0');

$cd_poweredby = $params->get('cd_poweredby', 1);
switch ($type)
{
    case 2:
        //Static Content only
        $query = "SELECT a.id, a.title, a.introtext" . "\n FROM #__content AS a" . "\n WHERE ( a.state = 1 AND a.sectionid = 0 )" .
            "\n AND ( a.publish_up = " . $database->Quote($nullDate) .
            " OR a.publish_up <= " . $database->Quote($now) . " )" . "\n AND ( a.publish_down = " .
            $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) .
            " )" . ($access ? "\n AND a.access <= " . (int)$my->gid : '') . "\n ORDER BY a.created DESC";
        $database->setQuery($query, 0, $count);
        $rows = $database->loadObjectList();
        break;

    case 3:
        //Both
        $whereCatid = '';
        if ($catid)
        {
            $catids = explode(',', $catid);
            mosArrayToInts($catids);
            $whereCatid = "\n AND ( a.catid=" . implode(" OR a.catid=", $catids) . " )";
        }
        $whereSecid = '';
        if ($secid)
        {
            $secids = explode(',', $secid);
            mosArrayToInts($secids);
            $whereSecid = "\n AND ( a.sectionid=" . implode(" OR a.sectionid=", $secids) .
                " )";
        }
        $query = "SELECT
a.id, a.title, a.title_alias, a.sectionid, a.catid, cc.access AS
cat_access, s.access AS sec_access, cc.published AS cat_state,
s.published AS sec_state, a.introtext" .
            "\n FROM #__content AS a" . "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id" .
            "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid" . "\n LEFT JOIN #__sections AS s ON s.id = a.sectionid" .
            "\n WHERE a.state = 1" . "\n AND ( a.publish_up = " . $database->Quote($nullDate) .
            " OR a.publish_up <= " . $database->Quote($now) . " )" . "\n AND ( a.publish_down = " .
            $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) .
            " )" . ($access ? "\n AND a.access <= " . (int)$my->gid : '') . $whereCatid . $whereSecid . ($show_front ==
            '0' ? "\n AND f.content_id IS NULL" : '') . "\n ORDER BY a.created DESC";
        $database->setQuery($query, 0, $count);
        $temp = $database->loadObjectList();

        $rows = array();
        if (count($temp))
        {
            foreach ($temp as $row)
            {
                if (($row->cat_state == 1 || $row->cat_state == '') && ($row->sec_state == 1 ||
                    $row->sec_state == '') && ($row->cat_access <= $my->gid || $row->cat_access ==
                    '' || !$access) && ($row->sec_access <= $my->gid || $row->sec_access == '' || !
                    $access))
                {
                    $rows[] = $row;
                }
            }
        }
        unset($temp);
        break;

    case 1:
    default:
        //Content Items only
        $whereCatid = '';
        if ($catid)
        {
            $catids = explode(',', $catid);
            mosArrayToInts($catids);
            $whereCatid = "\n AND ( a.catid=" . implode(" OR a.catid=", $catids) . " )";
        }
        $whereSecid = '';
        if ($secid)
        {
            $secids = explode(',', $secid);
            mosArrayToInts($secids);
            $whereSecid = "\n AND ( a.sectionid=" . implode(" OR a.sectionid=", $secids) .
                " )";
        }
        $query = "SELECT a.id, a.title, a.title_alias, a.sectionid, a.catid, a.introtext" .
            "\n FROM #__content AS a" . "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id" .
            "\n INNER JOIN #__categories AS cc ON cc.id = a.catid" . "\n INNER JOIN #__sections AS s ON s.id = a.sectionid" .
            "\n WHERE ( a.state = 1 AND a.sectionid > 0 )" . "\n AND ( a.publish_up = " . $database->
            Quote($nullDate) . " OR a.publish_up <= " . $database->Quote($now) . " )" . "\n AND ( a.publish_down = " .
            $database->Quote($nullDate) . " OR a.publish_down >= " . $database->Quote($now) .
            " )" . ($access ? "\n AND a.access <= " . (int)$my->gid . " AND cc.access <= " .
            (int)$my->gid . " AND s.access <= " . (int)$my->gid : '') . $whereCatid . $whereSecid . ($show_front ==
            '0' ? "\n AND f.content_id IS NULL" : '') . "\n AND s.published = 1" . "\n AND cc.published = 1" .
            "\n ORDER BY a.created DESC";
        $database->setQuery($query, 0, $count);
        $rows = $database->loadObjectList();
        break;
}


// needed to reduce queries used by getItemid for Content Items
if (($type == 1) || ($type == 3))
{
    $bs = $mainframe->getBlogSectionCount();
    $bc = $mainframe->getBlogCategoryCount();
    $gbs = $mainframe->getGlobalBlogSectionCount();
}

// Output


?>

<ul class="latestnews<?php echo $moduleclass_sfx; ?>">
  <?php
foreach ($rows as $row)
{
    // get Itemid
    switch ($type)
    {
        case 2:
            $query = "SELECT id" . "\n FROM #__menu" . "\n WHERE type = 'content_typed'" . "\n AND componentid = " . (int)
                $row->id;
            $database->setQuery($query);
            $Itemid = $database->loadResult();
            break;

        case 3:
            if ($row->sectionid)
            {
                $Itemid = $mainframe->getItemid($row->id, 0, 0, $bs, $bc, $gbs);
            } else
            {
                $query = "SELECT id" . "\n FROM #__menu" . "\n WHERE type = 'content_typed'" . "\n AND componentid = " . (int)
                    $row->id;
                $database->setQuery($query);
                $Itemid = $database->loadResult();
            }
            break;

        case 1:
        default:
            $Itemid = $mainframe->getItemid($row->id, 0, 0, $bs, $bc, $gbs);
            break;
    }
    // Blank itemid checker for SEF
    if ($Itemid == null)
    {
        $Itemid = '';
    } else
    {
        $Itemid = '&amp;Itemid=' . $Itemid;
    }

    $link = sefRelToAbs('index.php?option=com_content&amp;task=view&amp;id=' . $row->
        id . $Itemid);

    // set title

        switch ($cd_latestnews_set_title)
    {
        case 'title':
            $title = $row->title;
            break;
        case 'title_alias':
            $title = $row->title_alias;
                break;
        default:
            $title = $row->title;
            break;
    }
    // end

?>
  <li class="latestnews<?php echo $moduleclass_sfx; ?>"> <div><a href="<?php echo $link; ?>" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html-latestnews-<?php echo
    $row->id; ?>', wrapperClassName: 'mod_cd_latestnews', outlineType: '<?php echo $outlineType; ?>', align: '<?php echo $align; ?>', anchor: '<?php echo $anchor; ?>', dimmingOpacity: '<?php echo
$dimmingOpacity; ?>', slideshowGroup: 'mod_cd_latestnews' } )" class="highslide" title="<?php echo
    $title . ' - ' . _CD_LATESTNEWS_PREVIEW; ?>"><?php echo
    CdLatestNewsCharacterLimiter($title, $cd_latestnews_titlelimit); ?> </a></div>
    
    
    <div class="highslide-html-content" id="highslide-html-latestnews-<?php echo
    $row->id; ?>" style="width: <?php echo $cd_latestnews_html_content_width; ?>">
      <div class="highslide-html-content-header">
        <div class="highslide-move" title="<?php echo _CD_LATESTNEWS_TITLE_MOVE; ?>"> <a href="<?php echo $link; ?>" onclick="return hs.close(this)" class="control" title="<?php echo
    _CD_LATESTNEWS_CLOSELABEL; ?>"><?php echo _CD_LATESTNEWS_CLOSELABEL; ?></a> </div>
      </div>
      <div class="highslide-body" style="padding: 0 10px 10px 10px; color: #555">
      <?php echo $row->introtext; ?>
        <div style="text-align: right"> <a href="<?php echo $link; ?>" title="<?php echo
    $title . ' - ' . _READ_MORE; ?>" class="latestnews<?php echo $moduleclass_sfx; ?>"> <?php echo _READ_MORE; ?></a> </div>
      </div>
      <?php
    
    $licence_file = $mosConfig_absolute_path .
        '/mambots/system/cd_scriptegrator/utils/php/licence.php';
    if (File_Exists($licence_file))
        require $licence_file;
?>
    </div>
  </li>
  <?php
}
?>
</ul>
