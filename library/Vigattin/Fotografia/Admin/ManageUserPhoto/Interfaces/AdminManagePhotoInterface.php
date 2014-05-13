<?php
namespace Vigattin\Fotografia\Admin\ManageUserPhoto\Interfaces;

interface AdminManagePhotoInterface
{

    /**
     * Upload image in string format
     * @param array $postArray
     * @return array Result and image info of the uploaded image
     */
    public function uploadFromString(array $requestArray);

    /**
     * Update image info
     * @param array $requestArray
     * @return string Jsonp update result
     */
    public function updateInfo(array $requestArray);

    /**
     * Get info of an image
     * @param int $imageId
     * @return string Jsonp image info
     */
    public function getInfo($imageId);

    /**
     * Delete image from server
     * @param int $imageId
     * @return string Jsonp delete result
     */
    public function delete($imageId);

}