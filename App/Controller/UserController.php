<?php

namespace App\Controller;

use App\AppRepoManager;
use App\Model\User;
use Laminas\Diactoros\ServerRequest;
use Vroumvroum\View;

class UserController
{

    public static function hash(string $str): string
    {
        return $str;
    }

    public function create(ServerRequest $request): void
    {

        $user_data = $request->getParsedBody();

        if (!isset($user_data['user_type'])) $user_data['user_type'] = User::CLIENT;

        $new_user = new User($user_data);
        $new_user->password = self::hash( $new_user->password );

        $user = AppRepoManager::getRm()->getUserRepo()->create($new_user);

        if( is_null( $user ) ) {
            $_SESSION[ 'FORM_RESULT' ] = 'Une erreur s\'est produite';
            header( 'Location: /inscription' );
            die();
        }

        $user->password = '';

        $_SESSION[ 'USER' ] = $user;
        header( 'Location: /chambres' );
    }

    public function auth( ServerRequest $request ): void
    {
        $log_data = $request->getParsedBody();

        if (!(isset($log_data['mail']) && isset($log_data['password']))) { View::renderError(500); die(); };


        $test_user = new User($log_data);

        var_dump($test_user);

        $user = AppRepoManager::getRm()->getUserRepo()->auth($test_user->mail, $test_user->password);

        if ($user){
            if ($user->user_type === User::CLIENT || $user->user_type = User::ANNOUNCER ){
                $user->password = '';
                $_SESSION[ 'USER' ] = $user;
                header("Location: /chambres");
            }
            else {
                $_SESSION[ 'FORM_RESULT' ] = 'Vous ne vous êtes pas authentifié correctement <br>';
                header( 'Location: /connection' );
            }
        } else {
            $_SESSION[ 'FORM_RESULT' ] = 'Vous ne vous êtes pas authentifié correctement<br>';
            header( 'Location: /connection' );
        }

    }

    public function disconnect(): void
    {
        unset($_SESSION);
        session_regenerate_id(true);
        session_destroy();
        header("Location: /connection");
    }

}