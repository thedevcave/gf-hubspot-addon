<?php
use Helpers\HubspotClientHelper;
use HubSpot\Client\Crm\Contacts\Model\CollectionResponseSimplePublicObject;

$hubSpot = HubspotClientHelper::createFactory();

//https://developers.hubspot.com/docs/api/crm/contacts
/** @var CollectionResponseSimplePublicObject $contactsPage */
$hs_account = $hubSpot->integration()->getAccountDetails();
$portal_id = $hs_account->portalId;
// echo "<pre>"; print_r($hs_account->portalId); echo "</pre>";