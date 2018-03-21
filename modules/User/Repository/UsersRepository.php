<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 15:12
 */
namespace User\Repository;

use App\Entity\Users;
use App\Repository\BaseRepository;

class UsersRepository extends BaseRepository{
    public function add($data){
        $user = new Users();
        $user->setStatus(1)
            ->setMobile($data['mobile'])
            ->setNickname($data['nickname'])
            ->setChannel($data['channel'])
            ->setLogIp($data['log_ip'])
            ->setPackageName($data['package_name']);

        $this->persist($user);
        $this->flush();

        return $user->getId();
    }
}