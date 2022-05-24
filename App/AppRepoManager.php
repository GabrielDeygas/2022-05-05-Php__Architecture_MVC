<?php

namespace App;

use App\Model\Repository\EquipRepository;
use App\Model\Repository\FormRepository;
use App\Model\Repository\RentsRepository;
use App\Model\Repository\RoomRepository;
use App\Model\Repository\UserRepository;
use Vroumvroum\RepositoryManager;

class AppRepoManager
{
	use RepositoryManager;

	private RoomRepository $roomRepository;
	public function getRoomRepo(): RoomRepository { return $this->roomRepository; }

    private EquipRepository $equipRepository;
    public function getEquipRepo(): EquipRepository { return $this->equipRepository; }

    private RentsRepository $rentsRepository;
    public function getRentsRepo(): RentsRepository { return $this->rentsRepository; }

    private UserRepository $userRepository;
    public function getUserRepo(): UserRepository { return $this->userRepository; }

	protected function __construct()
	{
		$config = App::getApp();

		$this->roomRepository   = new RoomRepository( $config );
        $this->equipRepository  = new EquipRepository( $config );
        $this->rentsRepository  = new RentsRepository( $config );
        $this->userRepository   = new UserRepository( $config );
	}
}