<?php

use Helpers\HubspotClientHelper;
use Helpers\OAuth2Helper;
use Repositories\TokensRepository;

$response = HubspotClientHelper::getOAuth2Resource()->getTokensByCode(
		GF_HUBSPOT_CLIENT_ID,
		GF_HUBSPOT_CLIENT_SECRET,
		GF_HUBSPOT_REDIRECT_URI,
		$_GET['code']
);

if (HubspotClientHelper::isResponseSuccessful($response)) {
		OAuth2Helper::saveTokenResponse($response->toArray());

		echo "You may now close this window and return to your WordPress Admin screen.";
}
