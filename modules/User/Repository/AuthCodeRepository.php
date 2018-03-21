<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 15:41
 */
namespace User\Repository;

use App\Entity\AuthCode;
use App\Repository\BaseRepository;

class AuthCodeRepository extends BaseRepository
{
    public function add($data){
        $code = new AuthCode();
        $code->setMobile($data['mobile']);
        $code->setStatus(1);
        $code->setCreated(new \DateTime())
            ->setCode($data['code'])
            ->setUserId($data['user_id']);

        $this->persist($code);
        $this->flush();
    }
}