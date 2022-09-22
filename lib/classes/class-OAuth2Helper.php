<?php

namespace Helpers;

// use HubSpot\Client\Auth\OAuth\Model\TokenResponseIF;
// use HubSpot\Factory;
// use HubSpot\Utils\OAuth2;
use SevenShores\Hubspot\Utils\OAuth2;

class OAuth2Helper
{
    // const APP_REQUIRED_SCOPES = ['forms crm.objects.contacts.write crm.objects.contacts.read'];
    const CALLBACK_PATH = '/oauth/callback';
    const SESSION_TOKENS_KEY = 'tokens';
    
    public static function isAuthenticated(): bool
    {
        return !empty( get_option( 'gf_hs_addon_token' ) );
    }
    
    public static function getAuthUrl(): string 
    {
        $authUrl = OAuth2::getAuthUrl(
            GF_HUBSPOT_CLIENT_ID,
            GF_HUBSPOT_REDIRECT_URI,
            [ GF_HUBSPOT_SCOPES ]
        );
        
        return $authUrl;
    }
    
    public static function saveTokenResponse($tokens): void
    {
        $tokens['expires_at'] = time() + $tokens['expires_in'] * 0.95;
        update_option( 'gf_hs_addon_token', $tokens );
    }

    public static function refreshAndGetAccessToken(): string
    {
        $tokens = get_option( 'gf_hs_addon_token' );
        
        if ( empty( $tokens ) ) {
            throw new \Exception('Please authorize via OAuth2');
        }

        if (time() > $tokens['expires_at']) {
            $tokens = HubspotClientHelper::getOAuth2Resource()->getTokensByRefresh(
                GF_HUBSPOT_CLIENT_ID,
                GF_HUBSPOT_CLIENT_SECRET,
                $tokens['refresh_token']
            )->toArray();
            // $tokens = Factory::create()->auth()->oAuth()->tokensApi()->createToken(
            //     'refresh_token',
            //     null,
            //     GF_HUBSPOT_REDIRECT_URI,
            //     GF_HUBSPOT_CLIENT_ID,
            //     GF_HUBSPOT_CLIENT_SECRET,
            //     $tokens['refresh_token']
            // );
            self::saveTokenResponse($tokens);
        }

        return $tokens['access_token'];
    }
}
