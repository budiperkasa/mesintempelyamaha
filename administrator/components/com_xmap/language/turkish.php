<?php 
/* @package Xmap
 * @author Guillermo Vargas, http://joomla.vargas.co.cr
 * Turkish translation by http://www.turkiye-destani.com  
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if( !defined( 'JOOMAP_LANG' )) {
    define('JOOMAP_LANG', 1 );
    // -- General ------------------------------------------------------------------
    define('_XMAP_CFG_COM_TITLE',			'Xmap Ayarlar');
    define('_XMAP_CFG_OPTIONS',			'Görüntüleme Ayarlarý');
    define('_XMAP_CFG_CSS_CLASSNAME',		'CSS Class Adý');
    define('_XMAP_CFG_EXPAND_CATEGORIES',	'Ýçerik Kategorilerini Geniþlet');
    define('_XMAP_CFG_EXPAND_SECTIONS',	'Ýçerik Bölümlerini Geniþlet');
    define('_XMAP_CFG_SHOW_MENU_TITLES',	'Menü Baþlýklarýný Göster');
    define('_XMAP_CFG_NUMBER_COLUMNS',	'Kolon Sayýsý');
    define('_XMAP_EX_LINK',				'Dýþ Baðlantýyý Ýþaretle');
    define('_XMAP_CFG_CLICK_HERE', 		'Buraya Týklayým');
    define('_XMAP_CFG_GOOGLE_MAP',		'Google Site Haritasi');
    define('_XMAP_EXCLUDE_MENU',			'Dýþlanacak Menü ID leri');
    define('_XMAP_TAB_DISPLAY',			'Görüntüleme');
    define('_XMAP_TAB_MENUS',				'Menüler');
    define('_XMAP_CFG_WRITEABLE',			'Yazýlabilir');
    define('_XMAP_CFG_UNWRITEABLE',		'Yazýlamaz');
    define('_XMAP_MSG_MAKE_UNWRITEABLE',	'Kaydettikten sonra yazýlamaz yap');
    define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION', 'Kayýt ederken yazýlabilme iznini deðiþtir');
    define('_XMAP_GOOGLE_LINK',			'Google Baðlantýsý');
    define('_XMAP_CFG_INCLUDE_LINK',		'Yazara görünmez baðlantý');

    // -- Tips ---------------------------------------------------------------------
    define('_XMAP_EXCLUDE_MENU_TIP',		'Eklemek istemediðiniz menü ID lerini belirtiniz.<br /><strong>NOT</strong><br />ID leri virgul ile ayýrýnýz!');

    // -- Menus --------------------------------------------------------------------
    define('_XMAP_CFG_SET_ORDER',			'Menü Görüntüleme Sýrasýný Ayarla');
    define('_XMAP_CFG_MENU_SHOW',			'Göster');
    define('_XMAP_CFG_MENU_REORDER',		'Yeniden Sýrala');
    define('_XMAP_CFG_MENU_ORDER',		'Sýrala');
    define('_XMAP_CFG_MENU_NAME',			'Menü Ýsmi');
    define('_XMAP_CFG_DISABLE',			'Kapatmamak için týklayýnýz.');
    define('_XMAP_CFG_ENABLE',			'Açmak için týklayýnýz');
    define('_XMAP_SHOW',					'Göster');
    define('_XMAP_NO_SHOW',				'Gösterme');

    // -- Toolbar ------------------------------------------------------------------
    define('_XMAP_TOOLBAR_SAVE', 			'Kaydet');
    define('_XMAP_TOOLBAR_CANCEL', 		'Ýptal');

    // -- Errors -------------------------------------------------------------------
    define('_XMAP_ERR_NO_LANG',			'[ %s ] dil dosyasý bulunamadý, varsayýlan dil: Ýngilizce<br />');
    define('_XMAP_ERR_CONF_SAVE',         'HATA: Ayarlar kayýt edilemedi.');
    define('_XMAP_ERR_NO_CREATE',         'HATA: Ayarlar tablosu yaratýlamadý');
    define('_XMAP_ERR_NO_DEFAULT_SET',    'HATA: Varsayýlan ayarlar yüklenemedi');
    define('_XMAP_ERR_NO_PREV_BU',        'UYARI: Önceki yedekleme silinemedi');
    define('_XMAP_ERR_NO_BACKUP',         'HATA: Yedekleme oluþturulamadý');
    define('_XMAP_ERR_NO_DROP_DB',        'HATA: Ayarlar tablosu boþaltýlamadý');
    define('_XMAP_ERR_NO_SETTINGS',		'HATA: Veritabanýndaký ayarlar yüklenemedi: <a href="%s">Ayarlar tablosu yarat</a>');

    // -- Config -------------------------------------------------------------------
    define('_XMAP_MSG_SET_RESTORED',      'Settings restored');
    define('_XMAP_MSG_SET_BACKEDUP',      'Ayarlar kaydedildi');
    define('_XMAP_MSG_SET_DB_CREATED',    'Ayarlar tablosu yaratýldý');
    define('_XMAP_MSG_SET_DEF_INSERT',    'Varsayýlan ayarlar yüklendi');
    define('_XMAP_MSG_SET_DB_DROPPED','Xmap\'s tables have been saved!');
	
    // -- CSS ----------------------------------------------------------------------
    define('_XMAP_CSS',					'Xmap CSS');
    define('_XMAP_CSS_EDIT',				'Tema düzenle'); // Edit template
	
    // -- Sitemap (Frontend) -------------------------------------------------------
    define('_XMAP_SHOW_AS_EXTERN_ALT',	'Baðlantýyý yeni pencerede aç');
	
    // -- Added for Xmap 
    define('_XMAP_CFG_MENU_SHOW_HTML',		'Sitede goster');
    define('_XMAP_CFG_MENU_SHOW_XML',		'XML Site Haritasinda göster');
    define('_XMAP_CFG_MENU_PRIORITY',		'Onem');
    define('_XMAP_CFG_MENU_CHANGEFREQ',		'Deðiþme Sýklýðý');
    define('_XMAP_CFG_CHANGEFREQ_ALWAYS',		'Herzaman');
    define('_XMAP_CFG_CHANGEFREQ_HOURLY',		'Saatlik');
    define('_XMAP_CFG_CHANGEFREQ_DAILY',		'Günlük');
    define('_XMAP_CFG_CHANGEFREQ_WEEKLY',		'Haftalýk');
    define('_XMAP_CFG_CHANGEFREQ_MONTHLY',		'Aylýk');
    define('_XMAP_CFG_CHANGEFREQ_YEARLY',		'Yýllýk');
    define('_XMAP_CFG_CHANGEFREQ_NEVER',		'Hiçbir Zaman');

    define('_XMAP_TIT_SETTINGS_OF',			'%s için seçimler');
    define('_XMAP_TAB_SITEMAPS',			'Site Haritalarý');
    define('_XMAP_MSG_NO_SITEMAPS',			'Yaratýlmýþ Site Haritasý Yok');
    define('_XMAP_MSG_NO_SITEMAP',			'Bu Site Haritasý hazýr deðil');
    define('_XMAP_MSG_LOADING_SETTINGS',		'Yükleme Seçimleri...');
    define('_XMAP_MSG_ERROR_LOADING_SITEMAP',		'Hata. Site Haritasýný yükleyemiyor.');
    define('_XMAP_MSG_ERROR_SAVE_PROPERTY',		'Hata. Site Haritasi kayýt edilemiyor.');
    define('_XMAP_MSG_ERROR_CLEAN_CACHE',		'Hata. Site Haritasý cache silinemiyor');
    define('_XMAP_ERROR_DELETE_DEFAULT',		'Varsayýlan Site Haritasý silinemiyor!');
    define('_XMAP_MSG_CACHE_CLEANED',			'Cache temizlendi!');
    define('_XMAP_CHARSET',				'ISO-8859-1');
    define('_XMAP_SITEMAP_ID',				'Site Haritasý ID');
    define('_XMAP_ADD_SITEMAP',				'Site Haritasý Ekle');
    define('_XMAP_NAME_NEW_SITEMAP',			'Yeni Site Haritasý');
    define('_XMAP_DELETE_SITEMAP',			'Sil');
    define('_XMAP_SETTINGS_SITEMAP',			'Ayarlar');
    define('_XMAP_COPY_SITEMAP',			'Kopyala');
    define('_XMAP_SITEMAP_SET_DEFAULT',			'Varsayýlan Olarak Ata');
    define('_XMAP_EDIT_MENU',				'Seçenekler');
    define('_XMAP_DELETE_MENU',				'Sil');
    define('_XMAP_CLEAR_CACHE',				'Cache temizle');
    define('_XMAP_MOVEUP_MENU',		'Yukarý');
    define('_XMAP_MOVEDOWN_MENU',	'Aþaðý');
    define('_XMAP_ADD_MENU',		'Menü ekle');
    define('_XMAP_COPY_OF',		'%s\nin kopyasý');
    define('_XMAP_INFO_LAST_VISIT',	'En son ziyaret');
    define('_XMAP_INFO_COUNT_VIEWS',	'Ziyaret sayýsý');
    define('_XMAP_INFO_TOTAL_LINKS',	'Baðlantý sayýsý');
    define('_XMAP_CFG_URLS',		'Site Haritasýnýn URL\'si');
    define('_XMAP_XML_LINK_TIP',	'Baðlantýyý kopyala ve Google ve Yahoo\'ya gönder');
    define('_XMAP_HTML_LINK_TIP',	'Bu Site Haritasýnýn URL\'si. Menü eklemek için kullanabilirsiniz.');
    define('_XMAP_CFG_XML_MAP',		'XML Site Haritasý');
    define('_XMAP_CFG_HTML_MAP',	'HTML Site Haritasý');
    define('_XMAP_XML_LINK',		'Google baðlantýsý');
    define('_XMAP_CFG_XML_MAP_TIP',	'Arama motorlarý için yaratýlan XML dosyasý');
    define('_XMAP_ADD', 'Kaydet');
    define('_XMAP_CANCEL', 'Ýptal');
    define('_XMAP_LOADING', 'Yükleniyor...');
    define('_XMAP_CACHE', 'Cache');
    define('_XMAP_USE_CACHE', 'Cache Kullan');
    define('_XMAP_CACHE_LIFE_TIME', 'Cache Ömrü');
    define('_XMAP_NEVER_VISITED', 'Hiçbir Zaman');
}