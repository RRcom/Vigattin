<?php
namespace Vigattin\Coupon;

use Vigattin\Connect\Connect;

class Coupon {
    
    public $connect;
    
    public function __construct() {
        $this->connect = new Connect();
    }
    
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
    public function couponCheck($code, $item, $price) {
        $request = array(
            'mode' => Connect::REQUEST_MODE_COUPON_CHECK,
            'code' => $code,
            'item' => $item,
            'price' => $price
        );
        $result_data = $this->connect->apiCall($request);
        return json_decode($result_data, true);
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
    public function couponRedeem($code, $item, $price) {
        $request = array(
            'mode' => Connect::REQUEST_MODE_COUPON_REDEEM,
            'code' => $code,
            'item' => $item,
            'price' => $price
        );
        $result_data = $this->connect->apiCall($request);
        return json_decode($result_data, true);
    }
    
}

