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
use App\Util\StringUtil;

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

    public function fetchUserByMobileAndPassword($mobile, $password, $status = 1)
    {
        $filter = array(
            'mobile' => $mobile,
            'password' => StringUtil::encodePassword($password),
            'status' => $status
        );
        return $this->findOneBy($filter);
    }

    /**
     * @param $mobile
     * @param int $status
     * @return null| Users
     */
    public function fetchUserByMobile($mobile, $status = 1)
    {
        $filter = array(
            'mobile' => $mobile,
            'status' => $status
        );
        return $this->findOneBy($filter);
    }
}