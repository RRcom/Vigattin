<?php
namespace Vigattin\Member;

use Doctrine\ORM\NoResultException;
use Vigattin\Connect\DoctrineConnect;

class MemberDbSearch {

    const ORDER_BY_ASD = 0;
    const ORDER_BY_DESC = 1;

    protected $entityManager;
    
    public function __construct() {
        $this->entityManager = DoctrineConnect::createEntityManager();
    }
    
    public function getInfo($emailOrID, $searchFbid = FALSE) {
        
        if(filter_var($emailOrID, FILTER_VALIDATE_EMAIL)) {
            $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.email = :email");
            $query->setParameter('email', $emailOrID);
        }
        elseif($searchFbid) {
            if(!is_numeric($emailOrID)) return FALSE;
            $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.fbid = :fbid");
            $query->setParameter('fbid', $emailOrID);
        }
        else {
            if(!is_numeric($emailOrID)) return FALSE;
            $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.id = :id");
            $query->setParameter('id', $emailOrID);
        }

        try {
            $result = $query->getSingleResult($query::HYDRATE_ARRAY);
        } catch (NoResultException $ex) {
            $result = FALSE;
        }
        return $result;
    }

    public function searchMember($name, $start = 0, $limit = 30, $includeNewuser = TRUE, $includeNoProfilePhoto = TRUE, $resultOrder = self::ORDER_BY_DESC) {
        $newUser = ($includeNewuser) ? "(m.new_user = '' OR m.new_user = 0 OR m.new_user = 1)" : "(m.new_user = '' OR m.new_user = 0)";
        $profilePhoto = ($includeNoProfilePhoto) ? "(m.profile_photo != '' OR m.profile_photo = '')" : "(m.profile_photo != '')";
        $orderBy = ($resultOrder) ? "DESC": "ASC";
        if(empty($name)) {
            $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE $newUser AND $profilePhoto ORDER BY m.id $orderBy");
        }
        else {
            $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.name LIKE :name AND $newUser AND $profilePhoto ORDER BY m.id $orderBy");
            $query->setParameter('name', "%$name%");
        }
        $query->setFirstResult((int)$start);
        $query->setMaxResults((int)$limit);
        try {
            $result = $query->getResult($query::HYDRATE_ARRAY);
        } catch (NoResultException $ex) {
            $result = array();
        }
        return $result;
    }

    public function searchMemberTotalCount($name, $includeNewuser = TRUE, $includeNoProfilePhoto = TRUE) {
        $newUser = ($includeNewuser) ? "(m.new_user = '' OR m.new_user = 0 OR m.new_user = 1)" : "(m.new_user = '' OR m.new_user = 0)";
        $profilePhoto = ($includeNoProfilePhoto) ? "(m.profile_photo != '' OR m.profile_photo = '')" : "(m.profile_photo != '')";
        if(empty($name)) {
            $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m WHERE $newUser AND $profilePhoto");
        }
        else {
            $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m WHERE m.name LIKE :name AND $newUser AND $profilePhoto");
            $query->setParameter('name', "%$name%");
        }
        try {
            $result = $query->getSingleScalarResult();
        } catch (NoResultException $ex) {
            $result = array();
        }
        return $result;
    }

}
