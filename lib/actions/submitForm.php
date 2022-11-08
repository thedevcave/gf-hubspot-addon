<?php
use Helpers\HubspotClientHelper;
use HubSpot\Client\Crm\Contacts\Model\CollectionResponseSimplePublicObject;

$hubSpot = HubspotClientHelper::createFactory();

//https://developers.hubspot.com/docs/api/crm/contacts
/** @var CollectionResponseSimplePublicObject $contactsPage */
$submit_response = $hubSpot->forms()->submit($portal_id, $hs_form_id, $hs_data_json);
// echo "<pre>"; print_r($response); echo "</pre>";