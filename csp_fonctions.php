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

			/* -- Cas particulier pour les scripts --
			 * Autorisation de l'appel à la fonction eval() */
			if(lire_config("csp/autoriser_eval_{$type}") == "on")
				$policy["{$type}-src"][] = "'unsafe-eval'";

			/* -- Cas particuliers pour les scripts et les styles --
			 * Autorisation de l'appel aux scripts/styles inline */
			if(lire_config("csp/autoriser_inline_{$type}") == "on")
				$policy["{$type}-src"][] = "'unsafe-inline'";
		}
	}

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



/*
 * Cette fonction permet de vérifier la syntaxe d'une 'source' (au du
 * standard).
 *
 * Le standard (<http://www.w3.org/TR/CSP/#source-list>) spécifie le
 * format suivant. Remarque, ici on ne prend pas en compte les «
 * keywords » qui ne passent pas par cette fonction.
 *
 * source-list       = *WSP [ source-expression *( 1*WSP source-expression ) *WSP ]
 *                   / *WSP "'none'" *WSP
 * source-expression = scheme-source / host-source / keyword-source
 * scheme-source     = scheme ":"
 * host-source       = [ scheme "://" ] host [ port ]
 * ext-host-source   = host-source "/" *( <VCHAR except ";" and ","> )
 *                   ; ext-host-source is reserved for future use.
 * keyword-source    = "'self'" / "'unsafe-inline'" / "'unsafe-eval'"
 * scheme            = <scheme production from RFC 3986>
 * host              = "*" / [ "*." ] 1*host-char *( "." 1*host-char )
 * host-char         = ALPHA / DIGIT / "-"
 * port              = ":" ( 1*DIGIT / "*" )
 */
function verifier_syntaxe_regle($rule) {
	$scheme = "[a-zA-Z][a-zA-Z0-9\+\-\.]*";
	$host = "(\*|(\*\.)?[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*)";
	$port = "[0-9]+";

	return preg_match("/^{$scheme}:|({$scheme}:\/\/)?${host}(:{$port})?$/", $rule);
}
