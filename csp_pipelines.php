<?php
/**
 * Utilisations de pipelines par Content Security Policy
 *
 * @plugin     Content Security Policy
 * @copyright  2014
 * @author     Olivier Tétard
 * @licence    GNU/GPL v3
 * @package    SPIP\Csp\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function csp_jquery_plugins($flux) {
	$flux[] = 'lib/select2/select2.min.js';

	return $flux;
}


function csp_header_prive_css($texte) {
        $css = find_in_path('lib/select2/select2.css');
        $texte .= "<link rel='stylesheet' type='text/css' media='all' href='".direction_css($css)."' />\n";

        return $texte;
}

/**
 * Insertion dans le pipeline affichage_entetes_final (SPIP)
 *
 * Intégration de la politique de sécurité CSP dans les en-têtes HTTP
 * avant l'envoi des données au client.
 *
 * @param string $entetes
 *              Le contenu des en-têtes HTTP
 * @return string $flux
 *              Le contenu des en-têtes HTTP modifié
 */
function csp_affichage_entetes_final($entetes) {
	include_spip('csp_fonctions');

        if(!function_exists('lire_config'))
                include_spip('inc/config');

        if(lire_config('csp/activer') == 'on') {
		$politique = csp_obtenir_politique();

		spip_log("Content-Security-Policy: $politique");

		if(lire_config('csp/filtrage_impose') == "on")
			$entetes['Content-Security-Policy'] = $politique;
		else
			$entetes['Content-Security-Policy-Report-Only'] = $politique;
        }

        return $entetes;
}
