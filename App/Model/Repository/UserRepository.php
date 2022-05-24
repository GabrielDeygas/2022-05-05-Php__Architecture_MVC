<?php

namespace App\Model\Repository;

use App\Controller\UserController;
use Vroumvroum\Repository;
use App\Model\User;

class UserRepository extends Repository
{

    protected function getTableName(): string { return 'user'; }

    public function create( User $user ): ?User
    {
        $q = sprintf( 'INSERT INTO `%1$s` (`nickname`, `password`, `mail`, `user_type`)
                               VALUES (:nickname, :password, :mail, :user_type)', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );

        if( !$sth ) return null;

        $success = $sth->execute( [
            'nickname'  => $user->nickname,
            'password'  => UserController::hash($user->password),
            'mail'      => $user->mail,
            'user_type' => $user->user_type
            ] );

        if (!$success) return null;

       $user->id = $this->pdo->lastInsertId();

       return $user;
    }

    public function auth(string $mail, string $password): ?User
    {
        $q = sprintf( 'SELECT * FROM `%1$s` as u
                               WHERE u.mail = :mail && u.password = :password', $this->getTableName() );

        $sth = $this->pdo->prepare( $q );
        if( !$sth ) return null;

        $sth->execute( [
            'mail'      => $mail,
            'password'  => UserController::hash( $password )
        ] );


        $row_data = $sth->fetch();

        if( !( $row_data ) ) return null;
        var_dump($row_data);



        return new User( $row_data );
    }
}