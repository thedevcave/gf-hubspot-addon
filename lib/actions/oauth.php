<?php 

// Define the Oauth credentials required to keep access to Hubspot current 
define( 'GF_HUBSPOT_CLIENT_ID', '6c94be52-bbf1-41d4-b9ce-813f20b22680' );
define( 'GF_HUBSPOT_CLIENT_SECRET', '537720e7-eef4-45ac-9452-b5b0e478b643' );
define( 'GF_HUBSPOT_SCOPES', 'forms crm.objects.contacts.write crm.objects.contacts.read' );
define( 'GF_HUBSPOT_REDIRECT_URI', get_site_url(null, '/wp-json/gf-hs/v1/auth/') );