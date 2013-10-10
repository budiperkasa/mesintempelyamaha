<?php
/**
 * @package xmap
 * @author: Guillermo Vargas, http://joomla.vargas.co.cr
 * translated by: Riverson Rios (riverson@ufc.br)
*/

defined( '_VALID_MOS' ) or die( 'O acesso direto a esta página não foi autorizado' );

if( !defined( 'JOOMAP_LANG' )) {
    define ('JOOMAP_LANG', 1 );
    // -- General ------------------------------------------------------------------
    define("_XMAP_CFG_COM_TITLE", "Configuração do Xmap");
    define("_XMAP_CFG_OPTIONS", "Opções de configuração");
    define("_XMAP_CFG_TITLE", "Título");
    define("_XMAP_CFG_CSS_CLASSNAME", "Nome da clsase CSS");
    define("_XMAP_CFG_EXPAND_CATEGORIES","Expandir o conteúdo das categorias");
    define("_XMAP_CFG_EXPAND_SECTIONS","Expandir o conteúdo das seções");
    define("_XMAP_CFG_SHOW_MENU_TITLES", "Mostrar os títulos dos menus");
    define("_XMAP_CFG_NUMBER_COLUMNS", "Número de colunas");
    define('_XMAP_EX_LINK', 'Marcar links externos');
    define('_XMAP_CFG_CLICK_HERE', 'Clique aqui');
    define('_XMAP_EXCLUDE_MENU',			'Excluir IDs do menu');
    define('_XMAP_TAB_DISPLAY',			'Mostrar');
    define('_XMAP_TAB_MENUS',				'Menus');
    define('_XMAP_CFG_WRITEABLE',			'Não protegido contra escrita');
    define('_XMAP_CFG_UNWRITEABLE',		'Protegido contra escrita');
    define('_XMAP_MSG_MAKE_UNWRITEABLE',	'Após salvar, marcar como [ <span style="color: red;">protegido contra escrita</span> ]');
    define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION', 'Anular a proteção contra escrita ao salvar');
    define('_XMAP_CFG_INCLUDE_LINK',		'Links invisíveis ao autor');

    // -- Tips ---------------------------------------------------------------------
    define('_XMAP_EXCLUDE_MENU_TIP',		'Especificar os IDs do menu que não deseja incluir no mapa do site.<br /><strong>NOTA</strong><br />Separe os IDs por vírgulas!');

    // -- Menus --------------------------------------------------------------------
    define("_XMAP_CFG_SET_ORDER", "Selecionar a ordem na qual serão mostrados os menus");
    define("_XMAP_CFG_MENU_SHOW", "Mostrar");
    define("_XMAP_CFG_MENU_REORDER", "Reordenar");
    define("_XMAP_CFG_MENU_ORDER", "Ordenar");
    define("_XMAP_CFG_MENU_NAME", "Nome do Menu");
    define("_XMAP_CFG_DISABLE", "Clique para desativar");
    define("_XMAP_CFG_ENABLE", "Clique para ativar");
    define('_XMAP_SHOW','Mostrar');
    define('_XMAP_NO_SHOW','Não mostrar');

    // -- Toolbar ------------------------------------------------------------------
    define("_XMAP_TOOLBAR_SAVE", "Salvar");
    define("_XMAP_TOOLBAR_CANCEL", "Cancelar");

    // -- Errors -------------------------------------------------------------------
    define('_XMAP_ERR_NO_LANG','Língua [ %s ] não encontrada. Usarei a língua default: inglês<br />'); // %s = $GLOBALS['mosConfig_lang']
    define('_XMAP_ERR_CONF_SAVE',         '<h2>Erro ao salvar a configuração.</h2>');
    define('_XMAP_ERR_NO_CREATE',         'ERRO: Não foi possível criar a tabela de opções');
    define('_XMAP_ERR_NO_DEFAULT_SET',    'ERRO: Não foi possível inserir as opções default');
    define('_XMAP_ERR_NO_PREV_BU',        'ATENÇÃO: Não foi possível apagar a cópia de segurança anterior');
    define('_XMAP_ERR_NO_BACKUP',         'ERRO: Não foi possível criar a cópia de segurança');
    define('_XMAP_ERR_NO_DROP_DB',        'ERRO: Não foi possível apagar a tabela de opções');

    // -- Config -------------------------------------------------------------------
    define('_XMAP_MSG_SET_RESTORED',      'Opções restauradas<br />');
    define('_XMAP_MSG_SET_BACKEDUP',      'Opções salvas<br />');
    define('_XMAP_MSG_SET_DB_CREATED',    'Tabela de opções criada<br />');
    define('_XMAP_MSG_SET_DEF_INSERT',    'Opções default inseridas');
    define('_XMAP_MSG_SET_DB_DROPPED',    'Tabela de opções salva!');
	
    // -- CSS ----------------------------------------------------------------------
    define('_XMAP_CSS',					'CSS do Xmap');
    define('_XMAP_CSS_EDIT',				'Editar modelo'); // Edit template
	
    // -- Sitemap ------------------------------------------------------------------
    define('_XMAP_SHOW_AS_EXTERN_ALT','O link se abre em uma nova janela');
    define('_XMAP_PREVIEW','Previsualização');
    define('_XMAP_SITEMAP_NAME','Mapa do site');

    // -- Added for Xmap 
    define('_XMAP_CFG_MENU_SHOW_HTML',			'Mostrar o site');
    define('_XMAP_CFG_MENU_SHOW_XML',		'Mostrar Mapa do Site em XML');
    define('_XMAP_CFG_MENU_PRIORITY',		'Prioridade');
    define('_XMAP_CFG_MENU_CHANGEFREQ',		'Freqüência de Atualização');
    define('_XMAP_CFG_CHANGEFREQ_ALWAYS',		'Sempre');
    define('_XMAP_CFG_CHANGEFREQ_HOURLY',		'Horária');
    define('_XMAP_CFG_CHANGEFREQ_DAILY',		'Diária');
    define('_XMAP_CFG_CHANGEFREQ_WEEKLY',		'Semanal');
    define('_XMAP_CFG_CHANGEFREQ_MONTHLY',		'Mensal');
    define('_XMAP_CFG_CHANGEFREQ_YEARLY',		'Anual');
    define('_XMAP_CFG_CHANGEFREQ_NEVER',		'Nunca');

    define('_XMAP_TIT_SETTINGS_OF',			'Preferências para %s');
    define('_XMAP_TAB_SITEMAPS',			'Mapas do Site');
    define('_XMAP_MSG_NO_SITEMAPS',			'Nenhum Mapa do Site criado ainda');
    define('_XMAP_MSG_NO_SITEMAP',			'Este mapa não se encontra disponível');
    define('_XMAP_MSG_LOADING_SETTINGS',		'Carregando as preferências...');
    define('_XMAP_MSG_ERROR_LOADING_SITEMAP',		'ERRO: Não foi possível carregar o mapa indicado');
    define('_XMAP_MSG_ERROR_SAVE_PROPERTY',		'ERRO: Não foi possível salvar a propriedade para o sitemap');
    define('_XMAP_MSG_ERROR_CLEAN_CACHE',		'ERRO: Não foi possível limpar o cache do sitemap');
    define('_XMAP_MSG_CACHE_CLEANED',			'O cache foi limpo com sucesso!');
    define('_XMAP_CHARSET',				'ISO-8859-1');
    define('_XMAP_SITEMAP_ID',                          'ID do Mapa do Site');
    define('_XMAP_ADD_SITEMAP',				'Adicionar Mapa do Site');
    define('_XMAP_NAME_NEW_SITEMAP',			'Novo Mapa do Site');
    define('_XMAP_DELETE_SITEMAP',			'Apagar');
    define('_XMAP_SETTINGS_SITEMAP',			'Preferências');
    define('_XMAP_COPY_SITEMAP',			'Duplicar');
    define('_XMAP_SITEMAP_SET_DEFAULT',			'Marcar como Default');
    define('_XMAP_EDIT_MENU',				'Opções');
    define('_XMAP_DELETE_MENU',				'Sair');
    define('_XMAP_CLEAR_CACHE',				'Limpar Cache');
    define('_XMAP_MOVEUP_MENU',				'Para cima');
    define('_XMAP_MOVEDOWN_MENU',			'Para baixo');
    define('_XMAP_ADD_MENU',				'Adicionar Menus');
    define('_XMAP_COPY_OF',				'Cópia de %s');
    define('_XMAP_INFO_LAST_VISIT',				'Última Visita');
    define('_XMAP_INFO_COUNT_VIEWS',				'Número de Visitas');
    define('_XMAP_INFO_TOTAL_LINKS',				'Quantidade de links');
    define('_XMAP_CFG_URLS',            'URLs do Mapa do Site');
    define('_XMAP_XML_LINK_TIP',		'Copie o link e o envie a buscadores como Google e Yahoo');
    define('_XMAP_CFG_XML_MAP',		'Mapa do Site em XML');

    define('_XMAP_CFG_HTML_MAP',	'Mapa do Site em HTML');
    define('_XMAP_XML_LINK',			'Googlelink');
    define('_XMAP_CFG_XML_MAP_TIP',	'Arquivo XML gerado para o Mapa do Site.');
    define('_XMAP_HTML_LINK_TIP',       'Esta é a URL do Mapa do Site. Use-a para criar itens em seus menus.'); 
    define('_XMAP_ADD', 'Salvar');
    define('_XMAP_CANCEL', 'Cancelar');
    define('_XMAP_LOADING', 'Carregando...');
    define('_XMAP_CACHE', 'Cache');
    define('_XMAP_USE_CACHE', 'Utilizar Cache');
    define('_XMAP_CACHE_LIFE_TIME', 'Tempo de Vida do Cache');
    define('_XMAP_NEVER_VISITED', 'Nunca');
    define('_XMAP_MSG_SET_DB_DROPPED','As tabelas do Xmap foram salvas!');
}