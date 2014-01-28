<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

function csp_obtenir_politique() {
        if(!function_exists('lire_config'))
                include_spip('inc/config');

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

	return $policy;
}
