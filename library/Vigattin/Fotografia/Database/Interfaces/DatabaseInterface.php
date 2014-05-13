<?php
namespace Vigattin\Database\Interfaces;

interface DatabaseInterface
{
    const RESULT_OBJECT = 0;
    const RESULT_ARRAY = 1;

    /**
     * @param string $sql
     * @param array $parameter
     * @param int $resultType
     * @return mixed
     */
    public function query(string $sql, array $parameter = array(), $resultType = self::RESULT_ARRAY);
}