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
function csp_affichage_entetes_final($entetes){
        if(!function_exists('lire_config'))
                include_spip('inc/config');

        if(lire_config('csp/activer') == 'on') {
		$policy = "";

		/* Filtre par défaut (filtre global) */
		if(lire_config('csp/filtrer_defaut') == "on") {
			foreach(preg_split('/,/', lire_config('csp/filtre_defaut')) as $rule) {
				$policy['default-src'] .= " $rule";
			}
		}

		/* Application des filtres */
		foreach(array('script', 'object', 'style', 'img', 'frame', 'connect', 'font', 'media') as $type) {
			if(lire_config("csp/filtrer_{$type}") == "on") {
				$rules = lire_config("csp/filtre_{$type}");

				if($rules) {
					foreach(preg_split('/,/', $rules) as $rule) {
						$policy["{$type}-src"] .= " $rule";
					}
				}
				else {
					$policy["{$type}-src"] .= " 'none'";
				}
			}
		}

		/* Autorisation de l'appel à la fonction eval() */
		if(lire_config('csp/autoriser_eval') == "on")
			$policy['script-src'] .= " 'unsafe-eval'";

		/* Autorisation de l'appel aux scripts inline */
		if(lire_config('csp/autoriser_script_inline') == "on")
			$policy['script-src'] .= " 'unsafe-inline'";

		/* Autorisation de l'appel aux styles inline */
		if(lire_config('csp/autoriser_style_inline') == "on")
			$policy['style-src'] .= " 'unsafe-inline'";

		/* Activation de la console CSP */
		if(lire_config('csp/console_activer') == 'on') {
			$policy["report-uri"] = generer_url_action('collecteur_csp', "", true, true);
		}

		$policy_str = implode('; ', array_map(function ($v, $k) { return $k . ' ' . $v; }, $policy, array_keys($policy)));

		spip_log("Content-Security-Policy: $policy_str");

		if(lire_config('csp/filtrage_impose') == "on")
			$entetes['Content-Security-Policy'] = $policy_str;
		else
			$entetes['Content-Security-Policy-Report-Only'] = $policy_str;
        }

        return $entetes;
}
