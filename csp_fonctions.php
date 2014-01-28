<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

function csp_obtenir_politique() {
        if(!function_exists('lire_config'))
                include_spip('inc/config');
	
	$policy = array();

	/* Application des filtres */
	foreach(array('default', 'script', 'object', 'style', 'img', 'frame', 'connect', 'font', 'media') as $type) {
		if(lire_config("csp/filtrer_{$type}") == "on") {
			$policy["{$type}-src"] = array();

			if(lire_config("csp/filtre_autoriser_self_{$type}") == "on")
				$policy["{$type}-src"][] = "'self'";

			$rules = lire_config("csp/filtre_{$type}");
			if($rules) {
				foreach(preg_split('/,/', $rules) as $rule) {
					$policy["{$type}-src"][] = $rule;
				}
			}
		}
	}

	/* Autorisation de l'appel à la fonction eval() */
	if(lire_config('csp/filtrer_script')  == "on" && lire_config('csp/autoriser_eval') == "on")
		$policy['script-src'][] = "'unsafe-eval'";

	/* Autorisation de l'appel aux scripts inline */
	if(lire_config('csp/filtrer_script') == "on" && lire_config('csp/autoriser_script_inline') == "on")
		$policy['script-src'][] = "'unsafe-inline'";

	/* Autorisation de l'appel aux styles inline */
	if(lire_config('csp/filtrer_style') == "on" && lire_config('csp/autoriser_style_inline') == "on")
		$policy['style-src'][] = "'unsafe-inline'";
	
	/* Activation de la console CSP */
	if(lire_config('csp/console_activer') == 'on') {
		$policy["report-uri"][] = generer_url_action('collecteur_csp', "", true, true);
	}
	
	/* Transformation de la politique sous la forme d'une chaine
	 * de caractères */
	$policy_str = "";
	foreach($policy as $directive => $sources) {
		$policy_str .= "$directive ";
		if(count($sources) > 0)
			$policy_str .= implode(" ", $sources);
		else {
			$policy_str .= " 'none'";
		}

		$policy_str .= "; ";
	}

	return $policy_str;
}

