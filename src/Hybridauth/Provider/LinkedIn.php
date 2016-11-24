<?php
/*!
* This file is part of the HybridAuth PHP Library (hybridauth.sourceforge.net | github.com/hybridauth/hybridauth)
*
* This branch contains work in progress toward the next HybridAuth 3 release and may be unstable.
*/

namespace Hybridauth\Provider;

use Hybridauth\Exception;
use Hybridauth\Http\Request;
use Hybridauth\Adapter\Template\OAuth2\OAuth2Template;
use Hybridauth\Entity\Linkedin\Profile;

/**
* LinkedIn adapter extending OAuth1 Template
*
* http://hybridauth.sourceforge.net/userguide/IDProvider_info_LinkedIn.html
*/
class LinkedIn extends OAuth2Template
{
	/**
	* Internal: Initialize adapter. This method isn't intended for public consumption.
	*
	* Basically on initializers we feed defaults values to \OAuth2\Template::initialize()
	*
	* let*() methods are similar to set, but 'let' will not overwrite the value if its already set
	*/
	function initialize()
	{
		parent::initialize();

		$this->letApplicationId( $this->getAdapterConfig( 'keys', 'key' ) );
		$this->letApplicationSecret( $this->getAdapterConfig( 'keys', 'secret' ) );

		$scope = $this->getAdapterConfig( 'scope' ) 
			? $this->getAdapterConfig( 'scope' ) 
			: 'r_basicprofile+r_emailaddress+rw_nus';

		$this->setApplicationScope( $scope );
		$this->letEndpointRedirectUri( $this->getHybridauthEndpointUri() );
		$this->letEndpointBaseUri( 'https://api.linkedin.com/v1' );
		$this->letEndpointAuthorizeUri( 'https://www.linkedin.com/oauth/v2/authorization');
		$this->letEndpointRequestTokenUri( 'https://www.linkedin.com/oauth/v2/accessToken');
		//$this->letEndpointAccessTokenUri( 'https://api.linkedin.com/uas/oauth/accessToken' ); 
	}

	// --------------------------------------------------------------------

	/**
	* Returns user profile
	*
	* Examples:
	*
	*	$data = $hybridauth->authenticate( "LinkedIn" )->getUserProfile();
	*/
	function getUserProfile()
	{
		$response = $this->signedRequest( '/people/~:(id,first-name,last-name,email-address)', Request::GET,
			['format' => 'json'], ['Authorization' => 'Authorization: Bearer ' . $this->tokens->accessToken ] );
		$response = json_decode ( $response );

		if ( ! isset( $response->id ) || isset ( $response->error ) ){
			throw new
				Exception(
					'User profile request failed: Provider returned an invalid response. ' .
					'HTTP client state: (' . $this->httpClient->getState() . ')',
					Exception::USER_PROFILE_REQUEST_FAILED,
					$this
				);
		}

		return Profile::generateFromResponse($response,$this);
	}

	// --------------------------------------------------------------------

	/**
	* Returns user contacts list 
	*/
	function getUserContacts()
	{
		/// ToDo

		throw new Exception( "Unsupported", Exception::UNSUPPORTED_FEATURE, null, $this );
	}

	// --------------------------------------------------------------------

	/**
	* Updates user status 
	*/
	function setUserStatus( $status )
	{
		/// ToDo

		throw new Exception( "Unsupported", Exception::UNSUPPORTED_FEATURE, null, $this );
 	}
}
