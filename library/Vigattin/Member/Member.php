<?php
namespace Vigattin\Member;

use Vigattin\Connect\Connect;

class Member {
    
    public $connect;
    
    public function __construct() {
        $this->connect = new Connect();
    }
    
    /**
     * Read vigattin.com members database table
     * 
     * @param int $startId id + 1 where the start of reading will begin ex. if $start_id = 10 the reading will start from 11, 12, 13 and soon
     * @param int $limit tha max output number of row 
     * @return array result of the request
     */
    
    public function dbReadAllMembers($startId = 0, $limit = 30) {
        $request = array(
            'mode' => Connect::REQUEST_MODE_DB_READ_ALL_MEMBERS,
            'start_id' => $startId,
            'limit' => $limit
        );
        $resultData = $this->connect->apiCall($request);
        return json_decode($resultData, true);
        
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
    }
    
    /**
     * Update vigattin.com members database table
     * 
     * @param string $id_or_email the id or email of member from vigattin members table
     * @param array $field_array array of key value pair to be updated ex. update member first_name and last_name: $field_array = array('first_name' => 'vigattin', 'last_name' => 'inc.');
     * @return array result of the request
     */
    
    public function dbUpdateMember($id_or_email, $field_array = array()) {
        $request = array(
            'mode' => Connect::REQUEST_MODE_DB_UPDATE_MEMBERS,
            'id_or_email' => $id_or_email,
            'field_array' => $field_array
        );
        $result_data = $this->connect->apiCall($request);
        return json_decode($result_data, true);
        
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
    
    public function dbCreateMember($email, $password, $first_name, $last_name, $gender, $birthday = '', $verified = 0, $version = 0) {
        $request = array(
            'mode' => Connect::REQUEST_MODE_DB_CREATE_MEMBERS,
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'gender' => $gender,
            'birthday' => $birthday,
            'verified' => $verified,
            'version' => $version,
            'username' => ''
        );
        $result_data = $this->connect->apiCall($request);
        return json_decode($result_data, true);
        
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
        
    }
}

