<?php
namespace Vigattin\Fotografia\Database\Classes;

use Vigattin\Fotografia\Database\Interfaces\DatabaseInterface;
use Vigattin\Fotografia\Database\Interfaces\string;

class CiDatabase implements DatabaseInterface
{
    protected $ciDatabase;

    public function __construct($ciDatabase)
    {
        $this->ciDatabase = $ciDatabase;
    }

    /**
     * @param string $sql
     * @param array $parameter
     * @param int $resultType
     * @return mixed
     */
    public function query(string $sql, array $parameter = array(), $resultType = self::RESULT_ARRAY)
    {
        return $this->ciDatabase->query($sql, $parameter);
    }

}