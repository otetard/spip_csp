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

include_spip('csp_fonctions');
include_spip('inc/config');

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

        if(lire_config('csp/activer') == 'on' && lire_config('csp/activer_meta', 'off') == 'off') {
		$politique = csp_obtenir_politique();

		spip_log("Content-Security-Policy: $politique");

		if(lire_config('csp/filtrage_impose') == "on")
			$entetes['Content-Security-Policy'] = $politique;
		else
			$entetes['Content-Security-Policy-Report-Only'] = $politique;
        }

        return $entetes;
}

/**
 * Insertion dans le pipeline insert_head (SPIP)
 *
 * Intégration de la politique de sécurité CSP via une en-tête <meta>
 * dans l'en-tête HTML, avant l'envoi des données au client.
 *
 * @param string $flux
 *              Le contenu des en-têtes HTTP
 * @return string $flux
 *              Le contenu des en-têtes HTTP modifié
 */
function csp_insert_head($flux) {
	if(lire_config('csp/activer') == 'on' && lire_config('csp/activer_meta', 'off') == 'on') {
		$politique = csp_obtenir_politique();

		spip_log("Content-Security-Policy: $politique");

		if(lire_config('csp/filtrage_impose') == "on")
			$type_meta = "Content-Security-Policy";
		else
			$type_meta = "Content-Security-Policy-Report-Only";

		$flux .= "<meta http-equiv=\"{$type_meta}\" content=\"{$politique}\" />";
	}

        return $flux;
}

/**
 * Insertion dans le pipeline affichage_final (SPIP)
 *
 * Modification de *toutes* les balises <script> qui intègrent du code
 * 'inline' pour leur ajouter l’attribut 'nonce'. Cette technique, en
 * cours de standardisation dans CSP 1.1 permet ainsi d’exécuter les
 * scripts 'inline' jugés valides côtés serveur.
 *
 * Remarques :
 *   - le pipeline est n’est ici pas idéal, il prend toutes les
 *     balises scripts, même celles qui aurait pu être injectées via
 *     une XSS permanente (elle ne permet par exemple d’empêcher
 *     l’exploitation de la vulnérabilité corrigée dans SPIP 3.0.14.
 *   - on utilise ici le pipeline 'affichage_final', ce qui n’est
 *     clairement pas optimal (pas de mise en cache).
 *
 * @param string $flux
 *              Le contenu HTML de la page
 * @return string $flux
 *              Le contenu HTML de la page modifié
 */
function csp_affichage_final($flux) {
	if(lire_config('csp/activer') == 'on' && lire_config('csp/activer_nonce', 'off') == 'on') {
		include_spip('inc/filtres');

		$balises = extraire_balises($flux, 'script');
		$balises_nonce = array();

		foreach($balises as $balise) {
			if(extraire_attribut($balise, 'src') === NULL)
				$balises_nonce[] = inserer_attribut($balise, 'nonce', generer_nonce());
		}

		$flux = str_replace($balises, $balises_nonce, $flux);
	}

	return $flux;
}
