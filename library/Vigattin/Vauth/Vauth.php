<?php // vauth client v1.0-beta.1

use Vigattin\Auth\Auth;
use Vigattin\Coupon\Coupon;
use Vigattin\Photos\Photos;
use Vigattin\Member\Member;


class Vauth {
    
    protected $auth;
    protected $coupon;
    protected $photos;
    protected $member;

    // initial
    public function __construct() {
        $this->auth = new Auth();
        
        // Catch vigattin login/logout request
        if($auth_data = $this->auth->catchServerRequest()) {
            if($auth_data !== FALSE) {
                print_r($auth_data);
                exit();
            }
        }
        
        $this->coupon = new Coupon();
        $this->photos = new Photos();
        $this->member = new Member();
    }
    
    // ################## login-logout #########################################
    
    /**
     * Create login URL
     * 
     * @param string $redirect Url to be redirected after login.
     * @param bool $secure Use https protocol for security. 
     * @return string Url for login.
     */
    public function get_login_url($redirect = '', $secure = FALSE) {
        return $this->auth->getLoginUrl($redirect, $secure);
    }
    
    /**
     * Create logout URL
     * 
     * @param string $redirect Url to be redirected after logout.
     * @param bool $secure Use https protocol for security. 
     * @return string Url for logout.
     */
    public function get_logout_url($redirect = '', $secure = FALSE) {
        return $this->auth->getlogoutUrl($redirect, $secure);
    }
    
    /**
     * Check if user is login or not
     * 
     * @return boolean true if login false if not.
     */
    public function is_login() {
        if($this->auth->getInfo('vauth_vigid')) return true;
        else return false;
    }
    
    /**
     * Check if user has an fb token or grant access to his/her account
     * 
     * @return boolean true if fb logn or false if not
     */
    public function is_fb_login() {
        if($this->get_token()) return true;
        else return false;
    }
    
    /**
     * Clear user info data
     */
    public function clear_data() {
        $this->auth->clearInfo();
    }
    
    // ################### user data ###########################################
    
    /**
     * Get full user info
     * 
     * @return array
     */
    public function get_user_full_info() {
        return $this->auth->getInfo();
    }
    
    /**
     * Get info by key name
     * 
     * @param string $key
     * @return mixed
     */
    public function get_user_info_by_key($key) {
        return $this->auth->getInfo($key);
    }
    
    /**
     * Get user facebook ID from vigattin members database
     * 
     * @return int Facebook ID.
     */
    public function get_id() {
        return strval($this->auth->getInfo('vauth_id'));
    }
    
    /**
     * Get user unique ID from vigattin members database
     * 
     * @return int User ID
     */
    public function get_vigid() {
        return $this->auth->getInfo('vauth_vigid');
    }
    
    /**
     * Get user email from vigattin members database
     * 
     * @return string User email.
     */
    public function get_email() {
        return $this->auth->getInfo('vauth_email');
    }
    
    /**
     * Get user first name from vigattin members database
     * 
     * @return string User first name.
     */
    public function get_first_name() {
        return $this->auth->getInfo('vauth_first_name');
    }
    
    /**
     * Get user last name from vigattin members database
     * 
     * @return string User lastname.
     */
    public function get_last_name() {
        return $this->auth->getInfo('vauth_last_name');
    }
    
    /**
     * Get user profile photo link from vigattin members database
     * 
     * @return string User profile photo URL.
     */
    public function get_profile_photo() {
        return $this->auth->getInfo('vauth_profile_photo');
    }
    
    /**
     * Get user cover photo link from vigattin members database
     * 
     * @return string User cover photo URL.
     */
    public function get_cover_photo() {
        return $this->auth->getInfo('vauth_cover_photo');
    }
    
    /**
     * Get fb access token if any
     * 
     * @return string fb access token
     */
    public function get_token() {
        return $this->auth->getInfo('facebook_access_token');
    }
    
    /**
     * Get type of user
     * 
     * @return string
     */
    public function get_type() {
        return $this->auth->getInfo('vauth_type');
    }
    
    /**
     * Get user name
     * 
     * @return string username
     */
    public function get_username() {
        return $this->auth->getInfo('vauth_username');
    }
    
    /**
     * Get user gender
     * 
     * @return string gender male or famale
     */
    public function get_gender() {
        return $this->auth->getInfo('vauth_gender');
    }
    
    /**
     * Get user full name
     * 
     * @return string
     */
    public function get_name() {
        return $this->auth->getInfo('vauth_name');
    }
    
    /**
     * Get profile photo y offset
     * 
     * @return int
     */
    public function get_offset_y() {
        return $this->auth->getInfo('vauth_offset_y');
    }
    
    /**
     * Get user birthday ing epoch time
     * 
     * @return type int
     */
    public function get_birtday() {
        return $this->auth->getInfo('vauth_birthday');
    }
    
    /**
     * Check if user email is verified
     * 
     * @return int 1 if yes 0 if not
     */
    public function get_verified() {
        return $this->auth->getInfo('vauth_verified');
    }
    
    /**
     * Get promo code if any
     * 
     * @return array
     */ 
    public function get_promo() {
        return $this->auth->getInfo('vauth_promo');
    }
    
    // ####################### Coupon Code #####################################
    
    /**
     * Check coupon status
     * @param string $code coupon code
     * @param string $item item code
     * @param float $price price of the item
     * @return array coupon status
     * <br>
     * <pre>
     *  Array(
            [status] => ok
            [result] => Array(
                [error] => 
                [old_price] => 500
                [new_price] => 0
                [save_type_code] => 3
                [save_type_description] => get item for free
                [code] => 25659SVB
                [save] => 10
                [item] => dfsdfs
                [usable] => 5
                [expire] => 1380211200
            )
            [request] => Array(
                [mode] => 9
                [code] => 25659SVB
                [item] => dfsdfs
                [price] => 500
                [vapi_request_expire] => 1380173902
            )
        )
     * </pre>
     */
    public function coupon_check($code, $item, $price) {
        return $this->coupon->couponCheck($code, $item, $price);
    }
    
    /**
     * Redeem the coupon
     * @param string $code coupon code
     * @param string $item item code
     * @param float $price price of the item
     * @return array coupon status
     * <br>
     * <pre>
     *  Array(
            [status] => ok
            [result] => Array(
                [error] => 
                [old_price] => 500
                [new_price] => 0
                [save_type_code] => 3
                [save_type_description] => get item for free
                [code] => 25659SVB
                [save] => 10
                [item] => dfsdfs
                [usable] => 5
                [expire] => 1380211200
            )
            [request] => Array(
                [mode] => 9
                [code] => 25659SVB
                [item] => dfsdfs
                [price] => 500
                [vapi_request_expire] => 1380173902
            )
        )
     * </pre>
     */
    public function coupon_redeem($code, $item, $price) {
        return $this->coupon->couponRedeem($code, $item, $price);
    }
    
    // ####################### user photo api ##################################
    
    public function get_picture($size = 'normal' /* small, normal or large */) {
        return 'http://graph.facebook.com/'.$this->get_id().'/picture?type='.$size;
    }
    public function get_public_photos($uid = '', $start = 0, $limit = 30) {
        if($uid === '') $uid = $this->get_vigid();
        return $this->photos->getPublicPhotos($uid, $start, $limit);
    }
    public function save_photo_to_public($image_link, $fb_object_id = '', $title = '', $description = '') {
        $uid = $this->get_vigid();
        $user_full_name = $this->get_name();
        $email = $this->get_email();
        return $this->photos->savePhotoUrlToPublic($uid, $user_full_name, $email, $image_link, $fb_object_id, $title, $description);
    }
    public function delete_public_photo($pid) {
        $uid = $this->get_vigid();
        return $this->photos->deletePublicPhoto($uid, $pid);
    }
    
    // ########################## fb photo api #################################
    
    public function fb_get_album_list($fbid = '', $start = 0, $limit = 30) {
        /*
        RETURN
        Array
        (
            [status] => ok
            [total] => 2
            [albums] => Array
                (
                    [0] => Array
                        (
                            [url_large] => https://fbcdn-sphotos-d-a.akamaihd.net/hphotos-ak-ash4/s2048x2048/295606_256368404381532_5874251_n.jpg
                            [url_medium] => https://fbcdn-sphotos-d-a.akamaihd.net/hphotos-ak-ash4/295606_256368404381532_5874251_n.jpg
                            [url_xmedium] => https://fbcdn-sphotos-d-a.akamaihd.net/hphotos-ak-ash4/s320x320/295606_256368404381532_5874251_n.jpg
                            [url_small] => https://fbcdn-photos-d-a.akamaihd.net/hphotos-ak-ash4/295606_256368404381532_5874251_a.jpg
                            [url_xsmall] => https://fbcdn-photos-d-a.akamaihd.net/hphotos-ak-ash4/295606_256368404381532_5874251_s.jpg
                            [title] => Web Cam
                            [description] => 
                            [total] => 1
                            [aid] => 100000251232910_68767
                        )

                    [1] => Array
                        (
                            [url_large] => https://fbcdn-sphotos-h-a.akamaihd.net/hphotos-ak-prn2/s2048x2048/734893_534413109910392_1726481955_n.jpg
                            [url_medium] => https://fbcdn-sphotos-h-a.akamaihd.net/hphotos-ak-prn2/734893_534413109910392_1726481955_n.jpg
                            [url_xmedium] => https://fbcdn-sphotos-h-a.akamaihd.net/hphotos-ak-prn2/s320x320/734893_534413109910392_1726481955_n.jpg
                            [url_small] => https://fbcdn-photos-h-a.akamaihd.net/hphotos-ak-prn2/734893_534413109910392_1726481955_a.jpg
                            [url_xsmall] => https://fbcdn-photos-h-a.akamaihd.net/hphotos-ak-prn2/734893_534413109910392_1726481955_s.jpg
                            [title] => Profile Pictures
                            [description] => 
                            [total] => 5
                            [aid] => 100000251232910_27281
                        )
                )
        ) 
        */
        
        if($fbid === '') $fbid = $this->get_id();
        $access_token = $this->get_token();
        return $this->photos->fbGetAlbumList($fbid, $access_token, $start, $limit);
    }
    public function fb_get_album_photos($aid, $start = 0, $limit = 30) {
        /*
        RETURN
        Array
        (
            [status] => ok
            [total] => 2
            [photos] => Array
                (
                    [0] => Array
                        (
                            [url_large] => https://fbcdn-sphotos-e-a.akamaihd.net/hphotos-ak-prn1/s2048x2048/41153_145976728754034_1475750_n.jpg
                            [url_medium] => https://fbcdn-sphotos-e-a.akamaihd.net/hphotos-ak-prn1/41153_145976728754034_1475750_n.jpg
                            [url_xmedium] => https://fbcdn-sphotos-e-a.akamaihd.net/hphotos-ak-prn1/s320x320/41153_145976728754034_1475750_n.jpg
                            [url_small] => https://fbcdn-photos-e-a.akamaihd.net/hphotos-ak-prn1/41153_145976728754034_1475750_a.jpg
                            [url_xsmall] => https://fbcdn-photos-e-a.akamaihd.net/hphotos-ak-prn1/41153_145976728754034_1475750_s.jpg
                            [caption] => 
                            [object_id] => 145976728754034
                            [aid] => 100000251232910_22586
                        )

                    [1] => Array
                        (
                            [url_large] => https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-ash3/s2048x2048/40650_145976698754037_658873_n.jpg
                            [url_medium] => https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-ash3/40650_145976698754037_658873_n.jpg
                            [url_xmedium] => https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-ash3/s320x320/40650_145976698754037_658873_n.jpg
                            [url_small] => https://fbcdn-photos-c-a.akamaihd.net/hphotos-ak-ash3/40650_145976698754037_658873_a.jpg
                            [url_xsmall] => https://fbcdn-photos-c-a.akamaihd.net/hphotos-ak-ash3/40650_145976698754037_658873_s.jpg
                            [caption] => 
                            [object_id] => 145976698754037
                            [aid] => 100000251232910_22586
                        )
                )
        )
        */
        $access_token = $this->get_token();
        return $this->photos->fbGetAlbumPhotos($aid, $access_token, $start, $limit);
    }

    // ############################ members api ################################
    
    /**
     * Read vigattin.com members database table
     * 
     * @param int $start_id id + 1 where the start of reading will begin ex. if $start_id = 10 the reading will start from 11, 12, 13 and soon
     * @param int $limit tha max output number of row 
     * @return array result of the request
     */
    public function db_read_all_members($start_id = 0, $limit = 30) {
        /*
        Sample Return:
            Array
            (
                [status] => ok
                [result] => Array
                    (
                        [0] => Array
                            (
                                [id] => 0000000011
                                [email] => ellainegomez29@yahoo.com
                                [password] => cec41dbfb58bf5ffdb1cc24ebd285f5f30b8a41e
                                [first_name] => Ellaine
                                [last_name] => Gomez
                                [birthday] => 315532800
                                [gender] => 0
                                [country] => Anonymous
                                [verified] => 1
                                [type] => 1
                                [fbid] => 100001774838846
                                [name] => Ellaine Gomez
                                [time] => 1368753612
                                [profile_photo] => 
                                [cover_photo] => 
                                [offset_y] => 0
                                [is_local] => 0
                                [new_user] => 1
                                [version] => 0
                            )

                        [1] => Array
                            (
                                [id] => 0000000012
                                [email] => lovettejam@yahoo.com
                                [password] => 090cd6f86b11ec238ef6830a4f4ac3ed0a5556e0
                                [first_name] => Lovette
                                [last_name] => Jam
                                [birthday] => 478310400
                                [gender] => 0
                                [country] => Anonymous
                                [verified] => 1
                                [type] => 0
                                [fbid] => 1132617639
                                [name] => Lovette Jam
                                [time] => 0
                                [profile_photo] => 
                                [cover_photo] => 
                                [offset_y] => 0
                                [is_local] => 0
                                [new_user] => 1
                                [version] => 0
                            )

                        [2] => Array
                            (
                                [id] => 0000000013
                                [email] => temp_email_100003515029676@vig.com
                                [password] => cbaf9974bec6ae589bf36734b11994707acda927
                                [first_name] => Ian
                                [last_name] => Freelancemasseur
                                [birthday] => 315532800
                                [gender] => 1
                                [country] => Anonymous
                                [verified] => 0
                                [type] => 0
                                [fbid] => 100003515029676
                                [name] => Ian Freelancemasseur
                                [time] => 0
                                [profile_photo] => 
                                [cover_photo] => 
                                [offset_y] => 0
                                [is_local] => 0
                                [new_user] => 1
                                [version] => 0
                            )

                    )

                [request] => Array
                    (
                        [mode] => 5
                        [start_id] => 10
                        [limit] => 3
                        [vapi_request_expire] => 1379407068
                    )
        */
        
        return $this->member->dbReadAllMembers($start_id, $limit);
    }
    
    /**
     * Update vigattin.com members database table
     * 
     * @param string $id_or_email the id or email of member from vigattin members table
     * @param array $field_array array of key value pair to be updated ex. update member first_name and last_name: $field_array = array('first_name' => 'vigattin', 'last_name' => 'inc.');
     * @return array result of the request
     */
    public function db_update_member($id_or_email, $field_array = array()) {
        /*
        Sample Return:
            Array
            (
                [status] => ok
                [result] => update success
                [request] => Array
                    (
                        [mode] => 7
                        [id_or_email] => resty_rizal2@live.com
                        [field_array] => Array
                            (
                                [first_name] => Test
                                [last_name] => User
                            )

                        [vapi_request_expire] => 1379406901
                    )

            )
        */
        
        return $this->member->dbUpdateMember($id_or_email, $field_array);
    }
    
    /**
     * Create new vigattin.com member
     * 
     * @param string $email member email (must be a valid email)
     * @param string $password member password
     * @param string $first_name member first name
     * @param string $last_name member last name
     * @param string $gender member gender, posible value are male or female
     * @param ing $birthday member birthday in epoch time (optional)
     * @param int $verified Force the email to set as verified
     * @param int $version Add version to the inserted account for use in deleting or updating if problem occurs when inserting multiple account
     * @return array result of the request
     */
    public function db_create_member($email, $password, $first_name, $last_name, $gender, $birthday = '', $verified = 0, $version = 0, $username = '') {
        /*
        Sample Return:
            Array
            (
                [status] => ok
                [result] => Array
                    (
                        [id] => 7843
                        [error] => 
                    )

                [request] => Array
                    (
                        [mode] => 6
                        [email] => resty_rizal2@live.com
                        [password] => 12345
                        [first_name] => resty
                        [last_name] => rizal
                        [gender] => male
                        [birthday] => 
                        [vapi_request_expire] => 1379406732
                    )

            )
        */
        
        return $this->member->dbCreateMember($email, $password, $first_name, $last_name, $gender, $birthday, $verified, $version, $username);
    }
    
    // ####################### tools ###########################################
    
    public static function build_object() {
        return new self();
    }
    
    // ################## old api ##############################################
    
    public function login($redirect = '', $secure = FALSE) {
        $url = $this->get_login_url($redirect, $secure);
        header('Location: '.$url);
        exit();
    }
    public function logout($redirect = '', $secure = FALSE) {
        $url = $this->get_logout_url($redirect, $secure);
        header('Location: '.$url);
        exit();
    }
}