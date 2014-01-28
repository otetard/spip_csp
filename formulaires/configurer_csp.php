<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/config');

function formulaires_configurer_csp_verifier_dist() {
	include_spip('csp_fonctions');

	$erreurs = array();

	foreach(array('default', 'script', 'object', 'style', 'img', 'frame', 'connect', 'font', 'media') as $type) {
		if(_request("filtrer_{$type}") == "on") {
			$rules = _request("filtre_{$type}");

			if($rules) {
				foreach(preg_split('/,/', $rules) as $rule) {
					if(!verifier_syntaxe_regle($rule)) {
						$erreurs["filtre_{$type}"] = _T("csp:erreur_format_domaine");
					}
				}
			}
		}
	}

	return $erreurs;
}
