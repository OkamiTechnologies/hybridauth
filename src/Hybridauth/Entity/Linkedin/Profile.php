<?php
namespace Hybridauth\Entity\Linkedin;

class Profile extends \Hybridauth\Entity\Profile
{
    public static function generateFromResponse($response,$adapter) {
        $profile = parent::generateFromResponse($response,$adapter);
        $profile->setIdentifier ( static::parser( 'id', $response                   ) );
        $profile->setEmail      ( static::parser( 'emailAddress', $response         ) );
        $profile->setFirstName  ( static::parser( 'firstName',$response             ) );
        $profile->setLastName   ( static::parser( 'lastName',$response              ) );
        $profile->setDisplayName( static::parser( 'firstName',$response) . ' ' . static::parser( 'lastName',$response));

        return $profile;
    }
}
