<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin CSP.
 *
 * @plugin     Content Security Policy
 * @copyright  2014
 * @author     Olivier Tétard
 * @licence    GNU/GPL v3
 * @package    SPIP\Csp\Administrations
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Fonction d'installation et de mise à jour du plugin CSP.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
 **/
function csp_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	$maj['create'] = 
		array(array('maj_tables', array('spip_csp_rapports')));

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Fonction de désinstallation du plugin CSP.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
 **/
function csp_vider_tables($nom_meta_base_version) {
	sql_drop_table("spip_csp_rapports");
	effacer_meta($nom_meta_base_version);
}
