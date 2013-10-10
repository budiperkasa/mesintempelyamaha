<?php

// French Language Module for joomlaXplorer (translated by Olivier Pariseau and Alexandre PRIETO)

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Erreur(s)",
	"back"			=> "Page pr&eacute;c&eacute;dente",
	
	// root
	"home"			=> "Le r&eacute;pertoire home n'existe pas, v&eacute;rifiez vos pr&eacute;f&eacute;rences.",
	"abovehome"		=> "Le r&eacute;pertoire courant n'a pas l'air d'&ecirc;tre au-dessus du r&eacute;pertoire home.",
	"targetabovehome"	=> "Le r&eacute;pertoire cible n'a pas l'air d'&ecirc;tre au-dessus du r&eacute;pertoire home.",
	
	// exist
	"direxist"		=> "Ce r&eacute;pertoire n'existe pas.",
	//"filedoesexist"	=> "Ce fichier existe d&eacute;j&agrave;.",
	"fileexist"		=> "Ce fichier n'existe pas.",
	"itemdoesexist"		=> "Cet &eacute;l&eacute;ment existe d&eacute;j&agrave;.",
	"itemexist"		=> "Cet &eacute;l&eacute;ment n'existe pas.",
	"targetexist"		=> "Le r&eacute;pertoire cible n'existe pas.",
	"targetdoesexist"	=> "L'&eacute;l&eacute;ment cible existe d&eacute;j&agrave;.",
	
	// open
	"opendir"		=> "Impossible d'ouvrir le r&eacute;pertoire.",
	"readdir"		=> "Impossible de lire le r&eacute;pertoire.",
	
	// access
	"accessdir"		=> "Vous n'&ecirc;tes pas autoris&eacute; a acc&eacute;der &agrave; ce r&eacute;pertoire.",
	"accessfile"		=> "Vous n'&ecirc;tes pas autoris&eacute; a acc&eacute;der &agrave; ce fichier.",
	"accessitem"		=> "Vous n'&ecirc;tes pas autoris&eacute; a acc&eacute;der &agrave; cet &eacute;l&eacute;ment.",
	"accessfunc"		=> "Vous ne pouvez pas utiliser cette fonction.",
	"accesstarget"		=> "Vous n'&ecirc;tes pas autoris&eacute; a acc&eacute;der au repertoire cible.",
	
	// actions
	"permread"		=> "Lecture des permissions &eacute;chou&eacute;e.",
	"permchange"		=> "Changement des permissions &eacute;chou&eacute;.",
	"openfile"		=> "Ouverture du fichier &eacute;chou&eacute;e.",
	"savefile"		=> "Sauvegarde du fichier &eacute;chou&eacute;e.",
	"createfile"		=> "Cr&eacute;ation du fichier &eacute;chou&eacute;e.",
	"createdir"		=> "Cr&eacute;ation du r&eacute;pertoire &eacute;chou&eacute;e.",
	"uploadfile"		=> "Envoie du fichier &eacute;chou&eacute;.",
	"copyitem"		=> "La copie a &eacute;chou&eacute;e.",
	"moveitem"		=> "Le d&eacute;placement a &eacute;chou&eacute;.",
	"delitem"		=> "La suppression a &eacute;chou&eacute;e.",
	"chpass"		=> "Le changement de mot de passe a &eacute;chou&eacute;.",
	"deluser"		=> "La suppression de l'usager a &eacute;chou&eacute;e.",
	"adduser"		=> "L'ajout de l'usager a &eacute;chou&eacute;e.",
	"saveuser"		=> "La sauvegarde de l'usager a &eacute;chou&eacute;e.",
	"searchnothing"		=> "Vous devez entrez quelquechose &agrave; chercher.",
	
	// misc
	"miscnofunc"		=> "Fonctionalit&eacute; non disponible.",
	"miscfilesize"		=> "La taille du fichier exc&egrave;de la taille maximale autoris&eacute;e.",
	"miscfilepart"		=> "L'envoi du fichier n'a pas &eacute;t&eacute; compl&eacute;t&eacute;.",
	"miscnoname"		=> "Vous devez entrer un nom.",
	"miscselitems"		=> "Vous n'avez s&eacute;lectionn&eacute; aucuns &eacute;l&eacute;ment(s).",
	"miscdelitems"		=> "&ecirc;tes-vous certain de vouloir supprimer ces {0} &eacute;l&eacute;ment(s)?",
	"miscdeluser"		=> "&ecirc;tes-vous certain de vouloir supprimer l'usager {0}?",
	"miscnopassdiff"	=> "Le nouveau mot de passe est indentique au pr&eacute;c&eacute;dent.",
	"miscnopassmatch"	=> "Les mots de passe diff&eacute;rent.",
	"miscfieldmissed"	=> "Un champs requis n'a pas &eacute;t&eacute; rempli.",
	"miscnouserpass"	=> "Nom d'usager ou mot de passe invalide.",
	"miscselfremove"	=> "Vous ne pouvez pas supprimer votre compte.",
	"miscuserexist"		=> "Ce nom d'usager existe d&eacute;j&agrave;.",
	"miscnofinduser"	=> "Usager non trouv&eacute;.",
	"extract_noarchive" => "Ce fichier n'est pas une archive extractible.",
	"extract_unknowntype" => "Type Archive Inconnue",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Changer les permissions",
	"editlink"		=> "Editer",
	"downlink"		=> "T&eacute;l&eacute;charger",
	"uplink"		=> "Dossier parent",
	"homelink"		=> "Racine",
	"reloadlink"		=> "Rafra&icirc;chir",
	"copylink"		=> "Copier",
	"movelink"		=> "D&eacute;placer",
	"dellink"		=> "Supprimer",
	"comprlink"		=> "Archiver",
	"adminlink"		=> "Administration",
	"logoutlink"		=> "D&eacute;connecter",
	"uploadlink"		=> "Envoyer",
	"searchlink"		=> "Rechercher",
	"extractlink"		=> "Extraction de l'archive",
	'chmodlink'		=> 'Changer (chmod) les Droits (R&eacute;pertoire/Fichier(s))', // new mic
	'mossysinfolink'	=> $_VERSION->PRODUCT.' Informations Syst&egrave;me ('.$_VERSION->PRODUCT.', Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Visiter le site de Extplorer (nouvelle fen&ecirc;tre)', // new mic
	
	// list
	"nameheader"		=> "Nom",
	"sizeheader"		=> "Taille",
	"typeheader"		=> "Type",
	"modifheader"		=> "Modifi&eacute;",
	"permheader"		=> "Permissions",
	"actionheader"		=> "Actions",
	"pathheader"		=> "Chemin",
	
	// buttons
	"btncancel"		=> "Annuler",
	"btnsave"		=> "Sauver",
	"btnchange"		=> "Changer",
	"btnreset"		=> "R&eacute;initialiser",
	"btnclose"		=> "Fermer",
	"btncreate"		=> "Cr&eacute;er",
	"btnsearch"		=> "Chercher",
	"btnupload"		=> "Envoyer",
	"btncopy"		=> "Copier",
	"btnmove"		=> "D&eacute;placer",
	"btnlogin"		=> "Connecter",
	"btnlogout"		=> "D&eacute;connecter",
	"btnadd"		=> "Ajouter",
	"btnedit"		=> "&eacute;diter",
	"btnremove"		=> "Supprimer",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'		=> "Renommer",
	'confirm_delete_file' => '&ecirc;tes-vous sûr de vouloir supprimer ce fichier? \\n%s',
	'success_delete_file' => 'Fichier supprim&eacute; avec succ&egrave;s.',
	'success_rename_file' => 'Le r&eacute;pertoire/fichier %s a &eacute;t&eacute; renomm&eacute; %s.',
	
	
	// actions
	"actdir"		=> "R&eacute;pertoire",
	"actperms"		=> "Changer les permissions",
	"actedit"		=> "&eacute;diter le fichier",
	"actsearchresults"	=> "R&eacute;sultats de la recherche",
	"actcopyitems"		=> "Copier le(s) &eacute;l&eacute;ment(s)",
	"actcopyfrom"		=> "Copier de /%s &agrave; /%s ",
	"actmoveitems"		=> "D&eacute;placer le(s) &eacute;l&eacute;ment(s)",
	"actmovefrom"		=> "D&eacute;placer de /%s &agrave; /%s ",
	"actlogin"		=> "Connecter",
	"actloginheader"	=> "Connecter pour utiliser QuiXplorer",
	"actadmin"		=> "Administration",
	"actchpwd"		=> "Changer le mot de passe",
	"actusers"		=> "Usagers",
	"actarchive"		=> "Archiver le(s) &eacute;l&eacute;ment(s)",
	"actupload"		=> "Envoyer le(s) fichier(s)",
	
	// misc
	"miscitems"		=> "El&eacute;ment(s)",
	"miscfree"		=> "Disponible",
	"miscusername"		=> "Usager",
	"miscpassword"		=> "Mot de passe",
	"miscoldpass"		=> "Ancien mot de passe",
	"miscnewpass"		=> "Nouveau mot de passe",
	"miscconfpass"		=> "Confirmer le mot de passe",
	"miscconfnewpass"	=> "Confirmer le nouveau mot de passe",
	"miscchpass"		=> "Changer le mot de passe",
	"mischomedir"		=> "R&eacute;pertoire home",
	"mischomeurl"		=> "Chemin Racine",
	"miscshowhidden"	=> "Voir les &eacute;l&eacute;ments cach&eacute;s",
	"mischidepattern"	=> "Cacher pattern",
	"miscperms"		=> "Permissions",
	"miscuseritems"		=> "(nom, r&eacute;pertoire home, Voir les &eacute;l&eacute;ments cach&eacute;s, permissions, actif)",
	"miscadduser"		=> "Ajouter un usager",
	"miscedituser"		=> "&eacute;diter l'usager '%s'",
	"miscactive"		=> "Actif",
	"misclang"		=> "Langage",
	"miscnoresult"		=> "Aucun r&eacute;sultats.",
	"miscsubdirs"		=> "Rechercher dans les sous-r&eacute;pertoires",
	"miscpermnames"		=> array("Lecture seulement","Modifier","Changer le mot de passe","Modifier & Changer le mot de passe",
					"Administrateur"),
	"miscyesno"		=> array("Oui","Non","O","N"),
	"miscchmod"		=> array("Propri&eacute;taire", "Groupe", "Publique"),
	// from here all new by mic
	'miscowner'			=> 'Propri&eacute;taire',
	'miscownerdesc'		=> '<strong>Description:</strong><br />Propri&eacute;taire (UID) /<br />Groupe (GID)<br />Droits actuels:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> $_VERSION->PRODUCT.' Informations Syst&egrave;me',
	'sisysteminfo'		=> 'Info Syst&egrave;me',
	'sibuilton'			=> 'OS',
	'sidbversion'		=> 'Version Base de Donn&eacute;es (MySQL)',
	'siphpversion'		=> 'Version PHP',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">La version de PHP que vous utilisez n\'est <strong>plus</strong> d\'actualit&eacute;!</span><br />Afin de garantir un fonctionnement maximum de '.$_VERSION->PRODUCT.' et addons,<br />Vous devez utiliser au minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - Interface PHP',
	'simamboversion'	=> ' Version'.$_VERSION->PRODUCT,
	'siuseragent'		=> 'Version du Navigateur',
	'sirelevantsettings' => 'Param&egrave;tres PHP avanc&eacute;s',
	'sisafemode'		=> 'Mode Sécurisé',
	'sibasedir'			=> 'Ouvrir r&eacute;pertoire de base',
	'sidisplayerrors'	=> 'Erreurs PHP',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Date Uploads',
	'simagicquotes'		=> 'Guillements Magiques',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Buffer de Sortie',
	'sisesssavepath'	=> 'Chemin de Sauvegarde Session',
	'sisessautostart'	=> 'Session Automatique',
	'sixmlenabled'		=> 'XML activ&eacute;',
	'sizlibenabled'		=> 'ZLIB activ&eacute;',
	'sidisabledfuncs'	=> 'Fonction non valid&eacute;es',
	'sieditor'			=> 'Editeur WYSIWYG',
	'siconfigfile'		=> 'Fichier de configuration',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'Permissions R&eacute;pertoire',
	'sidirpermsmess'	=> 'Pour obtenir un fonctionnement correct de '.$_VERSION->PRODUCT.' assurez vous que vous poss&egrave;dez les droits en &eacute;criture sur l\'ensemble des r&eacute;pertoires. [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Voulez-vous r&eacute;ellement extraire ce fichier Ici?\\nCe fichier &eacute;crasera le fichier existant!",
	'extract_success' => "Extraction r&eacute;ussi",
	'extract_failure' => "Extraction &eacute;chou&eacute;e",
	
	'overwrite_files' => '&eacute;craser le(s) fichier(s) existant(s)?',
	"viewlink"		=> "Aper&ccedil;u",
	"actview"		=> "Aper&ccedil;u des sources du fichier",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'R&eacute;cursif dans les sous-r&eacute;pertoires?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'V&eacute;rifier si une version sup&eacute;rieure existe',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Renommer le r&eacute;pertoire ou fichier...',
	'newname'		=>	'Nouveau nom',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Retourner au r&eacute;pertoire apr&egrave;s sauvegarde?',
	'line'		=> 	'Ligne',
	'column'	=>	'Colonne',
	'wordwrap'	=>	'Wordwrap: (IE seulement)',
	'copyfile'	=>	'Copier fichier avec ce nom de fichier',
	
	// Bookmarks
	'quick_jump' => 'Saut rapide vers',
	'already_bookmarked' => 'Ce r&eacute;pertoire existe d&eacute;j&agrave; dans le signet',
	'bookmark_was_added' => 'R&eacute;pertoire ajout&eacute; &agrave; la liste de signet.',
	'not_a_bookmark' => 'Ce r&eacute;pertoire n\'est pas un signet.',
	'bookmark_was_removed' => 'Ce r&eacute;pertoire &agrave; &eacute;t&eacute; supprim&eacute; de la list de signet.',
	'bookmarkfile_not_writable' => "&eacute;chec lors de %s dans le signet.\n Le fichier signet '%s' \nn\'est pas accessible en &eacute;criture.",
	
	'lbl_add_bookmark' => 'Ajouter ce r&eacute;pertoire au signet',
	'lbl_remove_bookmark' => 'Supprimer ce r&eacute;pertoire de la liste de signet',
	
	'enter_alias_name' => 'SVP, entrez un alias pour ce signet',
	
	'normal_compression' => 'compression normal',
	'good_compression' => 'compression moyenne',
	'best_compression' => 'compression meilleur',
	'no_compression' => 'pas de compression',
	
	'creating_archive' => 'Cr&eacute;ation du Fichier Archive...',
	'processed_x_files' => '%s of %s Fichiers trait&eacute;s',
	
	'ftp_header' => 'Authentification FTP Locale',
	'ftp_login_lbl' => 'SVP, entrez un login de connexion pour le serveur FTP',
	'ftp_login_name' => "Nom d'utilisateur FTP",
	'ftp_login_pass' => 'Mot de passe FTP',
	'ftp_hostname_port' => 'Nom du serveur FTP et Port <br />(Le port est optionnel)',
	'ftp_login_check' => 'Test connexion serveur FTP...',
	'ftp_connection_failed' => "Serveur FTP impossible &agrave; contacter. \nSVP, v&eacute;rifiez que le service FTP est lanc&eacute; sur le serveur.",
	'ftp_login_failed' => "Login FTP incorrect. SVP, V&eacute;rifiez le nom et mot de passe utilisateur et r&eacute;essayez.",
		
	'switch_file_mode' => 'Mode courant: <strong>%s</strong>. Vous pouvez passer en mode %s.',
	'symlink_target' => 'Cible du lien symbolique',
	
	"permchange"		=> "CHMOD r&eacute;ussi:",
	"savefile"		=> "Le fichier est sauvegard&eacute;.",
	"moveitem"		=> "D&eacute;placement r&eacute;ussi.",
	"copyitem"		=> "Copie r&eacute;ussi.",
	'archive_name' 		=> "Nom de l'archive",
	'archive_saveToDir' 	=> "Sauvez l'archive dans ce r&eacute;pertoire",
	
	'editor_simple'	=> 'Mode Editeur Simple',
	'editor_syntaxhighlight'	=> 'Coloration Syntaxique',

	'newlink'	=> 'Nouveau Fichier/Dossier',
	'show_directories' => 'Voir les Dossiers',
	'actlogin_success' => 'Login r&eacute;ussi!',
	'actlogin_failure' => 'Login &eacute;chou&eacute;, essayez encore.',
	'directory_tree' => 'Arborescense Dossier',
	'browsing_directory' => 'Parcourir Dossier',
	'filter_grid' => 'Filtre',
	'paging_page' => 'Page',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => 'Premi&egrave;re Page',
	'paging_lastpage' => 'Derni&egrave;re Page',
	'paging_nextpage' => 'Page Suivante',
	'paging_prevpage' => 'Page pr&eacute;c&eacute;dente',
	
	'paging_info' => 'Affiche El&eacute;ment {0} - {1} de {2}',
	'paging_noitems' => 'Aucun &eacute;l&eacute;ment &agrave; afficher',
	'aboutlink' => 'Au sujet de...',
	'password_warning_title' => 'Important - Changer votre mot de passe!',
	'password_warning_text' => 'Le compte usager pour votre acc&egrave;s (admin avec mot de passe admin) corresponde au compte privil&eacute;gi&eacute; eXtplorer par defaut. Votre installation eXtplorer est sujette &agrave; intrusion et vous devez corriger cette faille de s&eacute;curit&eacute; imm&eacute;diatement!',
	'change_password_success' => 'Votre mot de passe a &eacute;t&eacute; chang&eacute;!',
	'success' => 'Succ&egrave;s',
	'failure' => 'Echec',
	'dialog_title' => 'Dialogue site',
	'upload_processing' => 'Processing Upload, please wait...',
	'upload_completed' => 'Upload successful!',
	'acttransfer' => 'Transfer from another Server',
	'transfer_processing' => 'Processing Server-to-Server Transfer, please wait...',
	'transfer_completed' => 'Transfer completed!',
	'max_file_size' => 'Maximum File Size',
	'max_post_size' => 'Maximum Upload Limit',
	'done' => 'Done.',
	'permissions_processing' => 'Applying Permissions, please wait...',
	'archive_created' => 'The Archive File has been created!',
	'save_processing' => 'Saving File...',
	'current_user' => 'This script currently runs with the permissions of the following user:',
	'your_version' => 'Your Version',
	'search_processing' => 'Searching, please wait...',
	'url_to_file' => 'URL of the File',
	'file' => 'File'
);
?>
