<?php
namespace Vigattin\Entity;

/**
 * @Entity
 * @Table(name="members", uniqueConstraints={@UniqueConstraint(name="email", columns={"email"})}, indexes={@Index(name="password", columns={"password"}), @Index(name="type", columns={"type"}), @Index(name="fbid", columns={"fbid"}), @Index(name="name", columns={"name"}), @Index(name="time", columns={"time"}), @Index(name="new_user", columns={"new_user"}), @Index(name="version", columns={"version"}), @Index(name="grant", columns={"grant"}), @Index(name="username", columns={"username"})})
 */
class Members {
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue 
     */
    protected $id;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $username;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $email;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $password;
    
    /**
     * @Column(type="string", length=255);
     */
    protected $first_name;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $last_name;
    
    /**
     * @Column(type="integer", options={"unsigned"=true})
     */
    protected $birthday;
    
    /**
     * @Column(type="smallint")
     */
    protected $gender;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $country;
    
    /**
     * @Column(type="boolean")
     */
    protected $verified = FALSE;
    
    /**
     * @Column(type="smallint")
     */
    protected $type = 0;
    
    /**
     * @Column(type="bigint")
     */
    protected $fbid = 0;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $name;
    
    /**
     * @Column(type="integer", options={"unsigned"=true})
     */
    protected $time = 0;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $profile_photo;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $cover_photo;
    
    /**
     * @Column(type="smallint")
     */
    protected $offset_y = 0;
    
    /**
     * @Column(type="boolean")
     */
    protected $is_local = FALSE;
    
    /**
     * @Column(type="boolean")
     */
    protected $new_user = TRUE;
    
    /**
     * @Column(type="smallint", options={"unsigned"=true})
     */
    protected $version = 0;
    
    /**
     * @Column(type="string", length=255)
     */
    protected $grant = 'a';
    
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getFirstname() {
        return $this->first_name;
    }
    
    public function getLastname() {
        return $this->last_name;
    }
    
    public function getBirthday() {
        return $this->birthday;
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function getVerified() {
        return $this->verified;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getFbid() {
        return $this->fbid;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getTime() {
        return $this->time;
    }
    
    public function getProfilePhoto() {
        return $this->profile_photo;
    }
    
    public function getCoverPhoto() {
        return $this->cover_photo;
    }
    
    public function getOffsetY() {
        return $this->offset_y;
    }
    
    public function getIsLocal() {
        return $this->is_local;
    }
    
    public function getNewuser() {
        return $this->new_user;
    }
    
    public function getVersion() {
        return $this->version;
    }
    
    public function getGrant() {
        return $this->grant;
    }
    
    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setFirstname($firstName) {
        $this->first_name = $firstName;
    }
    
    public function setLastname($lastName) {
        $this->last_name = $lastName;
    }
    
    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }
    
    public function setGender($gender) {
        $this->gender = $gender;
    }
    
    public function setCountry($country) {
        $this->country = $country;
    }
    
    public function setVerified($verified) {
        $this->verified = $verified;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function setFbid($fbid) {
        $this->fbid = $fbid;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function setTime($time) {
        $this->time = $time;
    }
    
    public function setProfilePhoto($profilePhoto) {
        $this->profile_photo = $profilePhoto;
    }
    
    public function setCoverPhoto($coverPhoto) {
        $this->cover_photo = $coverPhoto;
    }
    
    public function setOffsetY($offsetY) {
        $this->offset_y = $offsetY;
    }
    
    public function setIsLocal($isLocal) {
        $this->is_local = $isLocal;
    }
    
    public function setNewuser($newUser) {
        $this->new_user = $newUser;
    }
    
    public function setVersion($version) {
        $this->version = $version;
    }
    
    public function setGrant($grant) {
        $this->grant = $grant;
    }
}

