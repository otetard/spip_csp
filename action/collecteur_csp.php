<?php
/**
 * Module CSP pour SPIP.
 *
 * @plugin     Content Security Policy
 * @copyright  2014
 * @author     Olivier Tétard
 * @licence    GNU/GPL v3
 * @package    SPIP\Csp\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function action_collecteur_csp_dist() {
	include_spip('inc/config');

	/* FIXME: on devrait retourner un 403 */
        if(lire_config('csp/activer') != 'on' || lire_config('csp/console_activer') != 'on')
		return;

	$data = file_get_contents("php://input");
	if($data && $data_decode = json_decode($data)) {
		$rapport = $data_decode->{'csp-report'};

		$id = sql_insertq('spip_csp_rapports',
				  array('rapport' => json_encode($rapport),
					'document_uri' => $rapport->{'document-uri'},
					'blocked_uri' => $rapport->{'blocked-uri'},
					'date' => date('Y-m-d H:i:s')));
	}
}
