<?php
namespace Vigattin\Photos;

use Vigattin\Connect\Connect;
use Vigattin\Config\Config;
use Facebook;

class Photos {
    
    public $connect;
    public $config;
    public $facebook;
    
    public function __construct() {
        $this->connect = new Connect();
        $this->config = new Config();
        $this->facebook = new Facebook(array('appId'  => $this->config->config['facebook']['appId'], 'secret' => $this->config->config['facebook']['appSecret'], 'cookie' => true));
    }
    
    public function getPublicPhotos($uid, $start = 0, $limit = 30) {
        /*
        RETURN
        Array
        (
            [status] => ok
            [total] => 19
            [photos] => Array
                (
                    [0] => Array
                        (
                            [pid] => 81448
                            [url_large] => http://image.vigattin.com/box/normal/81/447_18261932442090028117.jpg
                            [url_medium] => http://image.vigattin.com/box/medium/81/447_18261932442090028117.jpg
                            [url_xmedium] => http://image.vigattin.com/box/cover/81/447_18261932442090028117.jpg
                            [url_small] => http://image.vigattin.com/box/small/81/447_18261932442090028117.jpg
                            [url_square] => http://image.vigattin.com/box/thumb/81/447_18261932442090028117.jpg
                            [title] => 
                            [description] => 
                        )

                    [1] => Array
                        (
                            [pid] => 81447
                            [url_large] => http://image.vigattin.com/box/normal/81/446_1955015526693422459.jpg
                            [url_medium] => http://image.vigattin.com/box/medium/81/446_1955015526693422459.jpg
                            [url_xmedium] => http://image.vigattin.com/box/cover/81/446_1955015526693422459.jpg
                            [url_small] => http://image.vigattin.com/box/small/81/446_1955015526693422459.jpg
                            [url_square] => http://image.vigattin.com/box/thumb/81/446_1955015526693422459.jpg
                            [title] => 
                            [description] => 
                        )
                )
        )
        */
        
        $result = array('status' => '', 'total' => 0, 'photos' => array());
        if(!$uid) {
            $result['status'] = 'invalid user id '.$uid;
            return $result;
        }
        $fetched_photos = $this->connect->apiCall(array(
            'mode'  => Connect::REQUEST_MODE_PUBLIC_PHOTOS,
            'uid'   => $uid,
            'start' => $start,
            'limit' => $limit,
            'desc'  => true
        ));
        if(!$fetched_photos) {
            $result['status'] = 'failed';
            return $result;
        }
        $fetched_photos = json_decode($fetched_photos, true);
        $result['status'] = $fetched_photos['status'];
        $result['total'] = $fetched_photos['data']['total'];
        $photos = (isset($fetched_photos['data']['data']) && is_array($fetched_photos['data']['data'])) ? $fetched_photos['data']['data'] : array();
        foreach($photos as $photo) {
            $result['photos'][] = array(
                'pid' => $photo['id'],
                'url_large' => $this->config->config['connect']['imageServer'].'/box/normal/'.$photo['image_dir'].'.jpg',
                'url_medium' => $this->config->config['connect']['imageServer'].'/box/medium/'.$photo['image_dir'].'.jpg',
                'url_xmedium' => $this->config->config['connect']['imageServer'].'/box/cover/'.$photo['image_dir'].'.jpg',
                'url_small' => $this->config->config['connect']['imageServer'].'/box/small/'.$photo['image_dir'].'.jpg',
                'url_square' => $this->config->config['connect']['imageServer'].'/box/thumb/'.$photo['image_dir'].'.jpg',
                'title' => $photo['image_title'],
                'description' => $photo['note']
            );
        }
        return $result;
    }
    
    public function savePhotoUrlToPublic($uid, $user_full_name, $email, $image_url, $fb_object_id = '', $title = '', $description = '') {
        /*
        RETURN
        Array
        (
            [status] => ok
            [photo] => Array
                (
                    [image_id] => 81447
                    [url_large] => http://image.vigattin.com/box/normal/81/446_1955015526693422459.jpg
                    [url_medium] => http://image.vigattin.com/box/medium/81/446_1955015526693422459.jpg
                    [url_xmedium] => http://image.vigattin.com/box/cover/81/446_1955015526693422459.jpg
                    [url_small] => http://image.vigattin.com/box/small/81/446_1955015526693422459.jpg
                    [url_square] => http://image.vigattin.com/box/thumb/81/446_1955015526693422459.jpg
                )

        )
        */
        
        $result = array('status' => '', 'photo' => array());
        $request = array(
            'mode'  => Connect::REQUEST_MODE_UPLOAD_PHOTO,
            'url'   => $image_url,
            'uid'   => $uid,
            'oid'   => $fb_object_id,
            'title' => $title,
            'name'  => $user_full_name,
            'email' => $email,
            'description'  => $description
        );
        $result_data = json_decode($this->connect->apiCall($request), true);
        $result['status'] = $result_data['status'];
        if($result['status'] == 'ok') {
            $result['photo'] = array(
                'image_id' => $result_data['data']['image_id'],
                'url_large' => $this->config->config['connect']['imageServer'].'/box/normal/'.$result_data['data']['link'],
                'url_medium' => $this->config->config['connect']['imageServer'].'/box/medium/'.$result_data['data']['link'],
                'url_xmedium' => $this->config->config['connect']['imageServer'].'/box/cover/'.$result_data['data']['link'],
                'url_small' => $this->config->config['connect']['imageServer'].'/box/small/'.$result_data['data']['link'],
                'url_square' => $this->config->config['connect']['imageServer'].'/box/thumb/'.$result_data['data']['link']
            );
        }
        return $result;
    }
    
    public function deletePublicPhoto($uid, $pid) {
        /*
        RETURN
        Array
        (
            [status] => ok
            [data] => Array
                (
                    [status] => success
                )

        ) 
        */
        
        $request = array(
            'mode'  => Connect::REQUEST_MODE_DELETE_PHOTO,
            'pid'   => $pid,
            'uid'   => $uid,
        );
        $result_data = $this->connect->apiCall($request);
        return json_decode($result_data, true);
    }
    
    public function fbGetAlbumList($fbid, $access_token, $start = 0, $limit = 30) {
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
        
        $result = array('status' => 'error', 'total' => 0, 'albums' => array());
        if(!$fbid) {
            $result['status'] = 'invalid user id '.$fbid;
            return $result;
        }
        $fbid = strval($fbid);
        $albums = array();
        $cover = array();
        $multiQuery = array (
            "all_aid" => "SELECT aid FROM album WHERE owner = $fbid AND type IN ( 'profile','mobile','wall','normal')",
            "albums" => "SELECT aid, object_id, cover_object_id, name, description, photo_count FROM album WHERE owner = $fbid AND type IN ( 'profile','mobile','wall','normal') ORDER BY object_id DESC LIMIT $start,$limit",
            "covers" => "SELECT images, aid, object_id FROM photo WHERE object_id IN (SELECT cover_object_id FROM #albums)",
            "user" => "SELECT id, name FROM profile WHERE id = $fbid",
        );
        $params = array (
            'method' => 'fql.multiquery',    
            'queries' => $multiQuery,       
            'callback' => ''
        );
        $this->facebook->setAccessToken($access_token);
        $fb_result = $this->facebook->api($params);
        if(!is_array($fb_result)) {
            $result['status'] = 'failed';
            return $result;
        }
        foreach($fb_result[3]['fql_result_set'] as $key => $value) {
            $cover[strval($value['aid'])] = $value;
        }

        foreach($fb_result[0]['fql_result_set'] as $key => $value) {
            $temp = array();
            if(isset($cover[$value['aid']])) {
                $temp['url_large']      = (empty($cover[$value['aid']]['images'][0])) ? '' : $cover[$value['aid']]['images'][0]['source']; // width max
                $temp['url_medium']     = (empty($cover[$value['aid']]['images'][2])) ? '' : $cover[$value['aid']]['images'][2]['source']; // width 720
                $temp['url_xmedium']    = (empty($cover[$value['aid']]['images'][5])) ? '' : $cover[$value['aid']]['images'][5]['source']; // width 320
                $temp['url_small']      = (empty($cover[$value['aid']]['images'][6])) ? '' : $cover[$value['aid']]['images'][6]['source']; // width 180
                $temp['url_xsmall']     = (empty($cover[$value['aid']]['images'][7])) ? '' : $cover[$value['aid']]['images'][7]['source']; // width 130
                $temp['title']          = $value['name'];
                $temp['description']    = $value['description'];
                $temp['total']          = $value['photo_count'];
                $temp['aid']            = $value['aid'];
            }
            else {
                $temp['url_large']      = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_medium']     = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_xmedium']    = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_small']      = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_xsmall']     = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['title']          = $value['name'];
                $temp['description']    = $value['description'];
                $temp['total']          = $value['photo_count'];
                $temp['aid']            = $value['aid'];
            }
            $albums[] = $temp;
        }
        $result['status'] = 'ok';
        $result['total'] = count($fb_result[1]['fql_result_set']);
        $result['albums'] = $albums;
        return $result;
    }
    
    public function fbGetAlbumPhotos($aid, $access_token, $start = 0, $limit = 30) {
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
        
        $aid = strval($aid);
        $result = array('status' => 'error', 'total' => 0, 'photos' => array());
        if(!$aid) {
            $result['status'] = 'invalid album id '.$aid;
            return $result;
        }
        $photos = array();
        $multiQuery = array (
            "photos" => "SELECT object_id, images, caption, comment_info, aid FROM photo WHERE aid = '$aid' ORDER BY position DESC LIMIT $start,$limit",
            "photos_count" => "SELECT photo_count FROM album WHERE aid = '$aid'"
        );
        $params = array (
            'method' => 'fql.multiquery',       
            'queries' => $multiQuery,
            'callback' => ''
        );
        $this->facebook->setAccessToken($access_token);
        $fb_result = $this->facebook->api($params);
        if(!is_array($fb_result)) {
            $result['status'] = 'failed';
            return $result;
        }
        foreach($fb_result[0]['fql_result_set'] as $value) {
            $temp = array();
            if(isset($value['images'])) {
                $temp['url_large']      = (empty($value['images'][0])) ? '' : $value['images'][0]['source']; // width max
                $temp['url_medium']     = (empty($value['images'][2])) ? '' : $value['images'][2]['source']; // width 720
                $temp['url_xmedium']    = (empty($value['images'][5])) ? '' : $value['images'][5]['source']; // width 320
                $temp['url_small']      = (empty($value['images'][6])) ? '' : $value['images'][6]['source']; // width 180
                $temp['url_xsmall']     = (empty($value['images'][7])) ? '' : $value['images'][7]['source']; // width 130
                $temp['caption']        = $value['caption'];
                $temp['object_id']      = $value['object_id'];
                $temp['aid']            = $value['aid'];
            }
            else {
                $temp['url_large']      = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_medium']     = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_xmedium']    = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_small']      = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['url_xsmall']     = 'http://www.facebook.com//images/photos/empty-album.png';
                $temp['caption']        = $value['caption'];
                $temp['object_id']      = $value['object_id'];
                $temp['aid']            = $value['aid'];
            }
            $photos[] = $temp;
        }
        $result['status'] = 'ok';
        $result['total'] = $fb_result[1]['fql_result_set'][0]['photo_count'];
        $result['photos'] = $photos;
        return $result;
    }
}

