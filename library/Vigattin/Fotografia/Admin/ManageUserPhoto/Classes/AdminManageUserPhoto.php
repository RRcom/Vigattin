<?php
namespace Vigattin\Fotografia\Admin\ManageUserPhoto\Classes;

use Vigattin\Fotografia\Admin\ManageUserPhoto\Interfaces\AdminManagePhotoInterface;
use Vigattin\Fotografia\Database\Interfaces\DatabaseAwareInterface;
use Vigattin\Fotografia\Database\Interfaces\DatabaseInterface;

class AdminManageUserPhoto implements AdminManagePhotoInterface, DatabaseAwareInterface
{
    /**
     * @var \Vigattin\Fotografia\Database\Interfaces\DatabaseInterface
     */
    protected $database;

    /**
     * Upload image in string format
     * @param array $postArray
     * @return array Result and image info of the uploaded image
     */
    public function uploadFromString(array $requestArray)
    {
        // TODO: Implement uploadFromString() method.
    }

    /**
     * Update image info
     * @param array $requestArray
     * @return string Jsonp update result
     */
    public function updateInfo(array $requestArray)
    {
        // TODO: Implement updateInfo() method.
    }

    /**
     * Get info of an image
     * @param int $imageId
     * @return string Jsonp image info
     */
    public function getInfo($imageId)
    {
        return $this->database->query("SELECT * FROM `photo_box` WHERE `id` = ? LIMIT 1", array($imageId));
    }

    /**
     * Delete image from server
     * @param int $imageId
     * @return string Jsonp delete result
     */
    public function delete($imageId)
    {
        // TODO: Implement delete() method.
    }

    public function setDatabase(DatabaseInterface $database)
    {
        $this->database = $database;
    }

}