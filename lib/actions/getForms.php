<?php
use Helpers\HubspotClientHelper;
use HubSpot\Client\Crm\Contacts\Model\CollectionResponseSimplePublicObject;

$hubSpot = HubspotClientHelper::createFactory();

//https://developers.hubspot.com/docs/api/crm/contacts
/** @var CollectionResponseSimplePublicObject $contactsPage */
$response = $hubSpot->forms()->all([
		'count' => 10,
]);
// echo "<pre>"; print_r($response); echo "</pre>";
$forms = $response->data;