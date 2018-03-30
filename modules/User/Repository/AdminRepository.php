<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/29
 * Time: 16:24
 */
namespace User\Repository;

use App\Entity\Admin;
use App\Repository\BaseRepository;

class AdminRepository extends BaseRepository
{
    /**
     * @param $mobile
     * @param int $status
     * @return null|Admin
     */
    public function fetchAdminByMobile($mobile, $status = 1)
    {
        $filter = array(
            'mobile' => $mobile,
            'status' => $status
        );
        return $this->findOneBy($filter);
    }
}