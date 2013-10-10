<?php
/**
 * @package Xmap
 * @author Guillermo Vargas, http://joomla.vargas.co.cr/
 * @Translated by Čolović Vladan, http://www.cvladan.com
*/

defined( '_VALID_MOS' ) or die( 'Pristup ovoj lokaciji nije dozvoljen' );

if( !defined( 'JOOMAP_LANG' ))
{
	define('JOOMAP_LANG', 1 );
	// -- General ------------------------------------------------------------------
	define('_XMAP_CFG_COM_TITLE',				'Xmap podešavanja');
	define('_XMAP_CFG_OPTIONS',					'Opcije prikaza');
	define('_XMAP_CFG_CSS_CLASSNAME',			'CSS ime class-e');
	define('_XMAP_CFG_EXPAND_CATEGORIES',		'Prikaži kategorije');
	define('_XMAP_CFG_EXPAND_SECTIONS',			'Prikaži sekcije');
	define('_XMAP_CFG_SHOW_MENU_TITLES',		'Prikaži imena menija');
	define('_XMAP_CFG_NUMBER_COLUMNS',			'Broj kolona');
	define('_XMAP_EX_LINK',						'Obeleži spoljne veze van domena');
	define('_XMAP_CFG_CLICK_HERE',				'Klikni ovdje');
	define('_XMAP_CFG_GOOGLE_MAP',				'Google mapa');
	define('_XMAP_EXCLUDE_MENU',				'Izuzmi ID-je menija');
	define('_XMAP_TAB_DISPLAY',					'Prikaži');
	define('_XMAP_TAB_MENUS',					'Meniji');
	define('_XMAP_CFG_WRITEABLE',				'Dozvoljen upis');
	define('_XMAP_CFG_UNWRITEABLE',				'Nije dozvoljeno upisivanje');
	define('_XMAP_MSG_MAKE_UNWRITEABLE',			'Onemogući upis nakon snimanja');
	define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION',	'Zaobiđi zaštitu od snimanja');
	define('_XMAP_GOOGLE_LINK',						'Google link');
	define('_XMAP_CFG_INCLUDE_LINK',				'Prikaži sakriveni link ka autoru');

	// -- Tips ---------------------------------------------------------------------
	define('_XMAP_EXCLUDE_MENU_TIP',		'Navedi ID stavki menija koji ne treba da se nalaze u mapi.<br /><strong>NAPOMENA:</strong> ID brojeve razdvoj zarezima!');

	// -- Menus --------------------------------------------------------------------
	define('_XMAP_CFG_SET_ORDER',		'Postavi redosled prikazivanja menija');
	define('_XMAP_CFG_MENU_SHOW',		'Prikaži');
	define('_XMAP_CFG_MENU_REORDER',	'Novi redosled');
	define('_XMAP_CFG_MENU_ORDER',		'Redosled');
	define('_XMAP_CFG_MENU_NAME',		'Ime menija');
	define('_XMAP_CFG_DISABLE',			'Isključi');
	define('_XMAP_CFG_ENABLE',			'Uključi');
	define('_XMAP_SHOW',				'Prikaži');
	define('_XMAP_NO_SHOW',				'Sakrij');

	// -- Toolbar ------------------------------------------------------------------
	define('_XMAP_TOOLBAR_SAVE',		'Sačuvaj');
	define('_XMAP_TOOLBAR_CANCEL',		'Otkaži');

	// -- Errors -------------------------------------------------------------------
	define('_XMAP_ERR_NO_LANG',			'Jezička datoteka [ %s ] nije pronađena, pa se koristi podrazumevani jezik: english<br />');
	define('_XMAP_ERR_CONF_SAVE',		'GREŠKA: Snimanje konfiguracije nije uspelo');
	define('_XMAP_ERR_NO_CREATE',		'GREŠKA: Nemoguće je napraviti tabelu sa podešavanjima');
	define('_XMAP_ERR_NO_DEFAULT_SET',	'GREŠKA: Nemoguće je ubaciti podrazumevane postavke');
	define('_XMAP_ERR_NO_PREV_BU',		'UPOZORENJE: Nije moguće ukloniti prethodnu sigurnosnu kopiju');
	define('_XMAP_ERR_NO_BACKUP',		'GREŠKA: Nije moguće napraviti sigurnosnu kopiju');
	define('_XMAP_ERR_NO_DROP_DB',		'GREŠKA: Nije moguće ukloniti tabelu sa podešavanjima');
	define('_XMAP_ERR_NO_SETTINGS',		'GREŠKA: Nije moguće učitati podešavanja iz baze podataka: <a href="%s">Napravi novu tabelu podešavanja</a>');

	// -- Config -------------------------------------------------------------------
	define('_XMAP_MSG_SET_RESTORED',	'Podešavanja su povraćena u početno stanje');
	define('_XMAP_MSG_SET_BACKEDUP',	'Podešavanja snimljena');
	define('_XMAP_MSG_SET_DB_CREATED',	'Tabela podešavanja je kreirana');
	define('_XMAP_MSG_SET_DEF_INSERT',	'Ubačena su podrazumevana podešavanja');
	define('_XMAP_MSG_SET_DB_DROPPED',	'Tabela sa podešavanjima je obrisana!');

	// -- CSS ----------------------------------------------------------------------
	define('_XMAP_CSS',					'Xmap CSS');
	define('_XMAP_CSS_EDIT',			'Izmeni template'); // Edit template

	// -- Sitemap (Frontend) -------------------------------------------------------
	define('_XMAP_SHOW_AS_EXTERN_ALT',		'Otvaraj linkove u novom prozoru');

	// -- Added for Xmap
	define('_XMAP_CFG_MENU_SHOW_HTML',		'Prikaži u HTML strani');
	define('_XMAP_CFG_MENU_SHOW_XML',		'Prikaži u XML sitemapu');
	define('_XMAP_CFG_MENU_PRIORITY',		'Prioritet');
	define('_XMAP_CFG_MENU_CHANGEFREQ',		'Promeni učestalost');
	define('_XMAP_CFG_CHANGEFREQ_ALWAYS',	'Uvek');
	define('_XMAP_CFG_CHANGEFREQ_HOURLY',	'Svakoga časa');
	define('_XMAP_CFG_CHANGEFREQ_DAILY',	'Dnevno');
	define('_XMAP_CFG_CHANGEFREQ_WEEKLY',	'Sedmično');
	define('_XMAP_CFG_CHANGEFREQ_MONTHLY',	'Mesečno');
	define('_XMAP_CFG_CHANGEFREQ_YEARLY',	'Godišnje');
	define('_XMAP_CFG_CHANGEFREQ_NEVER',	'Nikad');

	define('_XMAP_TIT_SETTINGS_OF',				'Podešavanja za %s');
	define('_XMAP_TAB_SITEMAPS',				'Sitemap-e');
	define('_XMAP_MSG_NO_SITEMAPS',				'Nema kreiranih mapa');
	define('_XMAP_MSG_NO_SITEMAP',				'Mapa je nedostupna');
	define('_XMAP_MSG_LOADING_SETTINGS',		'Učitavanje podešavanja...');
	define('_XMAP_MSG_ERROR_LOADING_SITEMAP',	'Greška: Nije moguće učitati mapu');
	define('_XMAP_MSG_ERROR_SAVE_PROPERTY',		'Greška: Nije moguće snimiti mapu');
	define('_XMAP_MSG_ERROR_CLEAN_CACHE',		'Greška: Nije moguće obrisati cache');
	define('_XMAP_ERROR_DELETE_DEFAULT',		'Greška: Nije moguće izbrisati podrazumevanu mapu!');
	define('_XMAP_MSG_CACHE_CLEANED',			'Cache je obrisan');
	define('_XMAP_CHARSET',						'utf-8');
	define('_XMAP_SITEMAP_ID',					'ID oznaka mape');
	define('_XMAP_ADD_SITEMAP',					'Dodaj mapu');
	define('_XMAP_NAME_NEW_SITEMAP',			'Nova mapa');
	define('_XMAP_DELETE_SITEMAP',				'Obriši');
	define('_XMAP_SETTINGS_SITEMAP',			'Podešavanja');
	define('_XMAP_COPY_SITEMAP',				'Kopiraj');
	define('_XMAP_SITEMAP_SET_DEFAULT',			'Podrazumevana');
	define('_XMAP_EDIT_MENU',					'Izmeni');
	define('_XMAP_DELETE_MENU',					'Obriši');
	define('_XMAP_CLEAR_CACHE',					'Očisti cache');
	define('_XMAP_MOVEUP_MENU',					'Gore');
	define('_XMAP_MOVEDOWN_MENU',	   			'Dole');
	define('_XMAP_ADD_MENU',			   		'Dodaj menije');
	define('_XMAP_COPY_OF',						'Kopija od %s');
	define('_XMAP_INFO_LAST_VISIT',	   			'Poslednja poseta');
	define('_XMAP_INFO_COUNT_VIEWS',	 		'Broj poseta');
	define('_XMAP_INFO_TOTAL_LINKS',	  		'Broj linkova');
	define('_XMAP_CFG_URLS',			   		'URL-ovi mape');
	define('_XMAP_XML_LINK_TIP',				'Kopiraj link i postavi ga na Google i Yahoo');
	define('_XMAP_HTML_LINK_TIP',				'Ovo je URL mape, možete ga koristiti prilikom kreiranja stavki u menijima');
	define('_XMAP_CFG_XML_MAP',					'XML mapa sajta');
	define('_XMAP_CFG_HTML_MAP',				'HTML mapa sajta');
	define('_XMAP_XML_LINK',					'Googlelink');
	define('_XMAP_CFG_XML_MAP_TIP',				'XML datoteka je generisana za pretraživače');
	define('_XMAP_ADD',							'Snimi');
	define('_XMAP_CANCEL',						'Odustani');
	define('_XMAP_LOADING',						'Učitavam...');
	define('_XMAP_CACHE',						'Cache');
	define('_XMAP_USE_CACHE',					'Koristi cache');
	define('_XMAP_CACHE_LIFE_TIME',				'Cache trajanje');
	define('_XMAP_NEVER_VISITED',				'Nikad');
}