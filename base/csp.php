<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Content Security Policy
 * @copyright  2014
 * @author     Olivier Tétard
 * @licence    GNU/GPL v3
 * @package    SPIP\Csp\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function csp_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['csp_rapports'] = 'csp_rapports';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function csp_declarer_tables_objets_sql($tables) {

	$tables['spip_csp_rapports'] = array(
		'type' => 'csp_rapport',
		'principale' => "oui", 
		'table_objet_surnoms' => array('csprapport'), // table_objet('csp_rapport') => 'csp_rapports' 
		'field'=> array(
			"id_csp_rapport"     => "bigint(21) NOT NULL",
			"rapport"            => "text NOT NULL DEFAULT ''",
			"document_uri"       => "text NOT NULL DEFAULT ''",
			"blocked_uri"        => "text NOT NULL DEFAULT ''",
			"date"               => "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'", 
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_csp_rapport",
		),
		'titre' => "'' AS titre, '' AS lang",
		'date' => "date",
		'champs_editables'  => array(),
		'champs_versionnes' => array(),
		'rechercher_champs' => array(),
		'tables_jointures'  => array(),
		

	);

	return $tables;
}



?>