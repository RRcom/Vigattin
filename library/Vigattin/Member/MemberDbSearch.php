<?php
namespace Vigattin\Member;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Vigattin\Entity\Members;
use Vigattin\Connect\DoctrineConnect;

class MemberDbSearch {
    
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
            $result = $query->getSingleResult();
        } catch (NoResultException $ex) {
            $result = FALSE;
        }
        return $result;
    }
    
    public function searchMember($name, $start = 0, $limit = 30, $includeNewuser = TRUE) {
        if(empty($name)) {
            if((bool)$includeNewuser) $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.new_user = 1");
            else $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.new_user = 0");
        }
        else {
            if((bool)$includeNewuser) $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.name LIKE :name AND m.new_user = 1");
            else $query = $this->entityManager->createQuery("SELECT m FROM Vigattin\Entity\Members m WHERE m.name LIKE :name AND m.new_user = 0");
            $query->setParameter('name', "%$name%");
        }
        $query->setFirstResult((int)$start);
        $query->setMaxResults((int)$limit);
        try {
            $result = $query->getResult();
        } catch (NoResultException $ex) {
            $result = array();
        }
        return $result;
    }
    
    public function searchMemberTotalCount($name, $includeNewuser = TRUE) {
        if(empty($name)) {
            if((bool)$includeNewuser) $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m");
            else $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m WHERE m.new_user != 1");
        }
        else {
            if((bool)$includeNewuser) $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m WHERE m.name LIKE :name");
            else $query = $this->entityManager->createQuery("SELECT COUNT(m.id) FROM Vigattin\Entity\Members m WHERE m.name LIKE :name AND m.new_user != 1");
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
