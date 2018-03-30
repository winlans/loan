<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 10:36
 */
namespace App\Service;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use App\Util\ArrayUtil;

class Security
{
    /** @var \App\Doctrine\ORM\EntityManager  */
    private $em;


    /** @var Request  */
    private $request;

    const KEY_SESSION_USER = 'user';

    function __construct(EmFactory $emFactory, Request $request)
    {
        $this->em = $emFactory->getEntityManager();
        $this->request = $request;
    }

    /**
     * @param Users $user
     * @param bool $is_admin
     * @return Users
     */
    public function login($user, $is_admin = false)
    {
        $user = clone $user;
        $userInfo = $user->toArray(false);

        // 给管理员用户打上标记
        $is_admin && $userInfo['is_admin'] = 1;

        $removed= [
            'password',
        ];

        ArrayUtil::filterField($userInfo, $removed);
        // write to session
        $this->request->getSession()->set(Security::KEY_SESSION_USER, $userInfo);
        return $user;
    }

    public function logout()
    {
        return (bool)$this->request->getSession()->remove(self::KEY_SESSION_USER);
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return (bool)$this->request->getSession()->get(self::KEY_SESSION_USER);
    }

    public function isAdmin(){
        return (bool) $this->request->getSession()->get(self::KEY_SESSION_USER)['is_admin'];
    }



}