<?php
/**
 * Zend Expressive OAuth2 (http://framework.zend.com/)
 *
 * @see       https://github.com/jguittard/zend-expressive-oauth2 for the canonical source repository
 * @copyright Copyright (c) 2015-2017 Julien Guittard. (https://julienguittard.com)
 * @license   https://github.com/jguittard/zend-expressive-oauth2/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Expressive\OAuth2\Mapper\Pdo;

use Traversable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Expressive\OAuth2\Mapper\MapperInterface;

/**
 * Class PdoMapper
 *
 * @package   Zend\Expressive\OAuth2\Mapper\Pdo
 * @author    Julien Guittard <julien.guittard@me.com>
 */
class PdoMapper implements MapperInterface
{
    /**
     * @var TableGateway
     */
    protected $table;

    /**
     * @var string
     */
    protected $identifierName;

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function findAll()
    {
        return $this->table->select();
    }

    /**
     * @param array $params
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function findAllBy(array $params)
    {
        return $this->table->select($params);
    }

    /**
     * @param string|integer $id
     * @return \ArrayObject|boolean
     */
    public function findOne($id)
    {
        return $this->findOneBy([ $this->identifierName => $id ]);
    }

    /**
     * @param array $params
     * @return array|\ArrayObject|bool
     */
    public function findOneBy(array $params)
    {
        $entity = $this->table->select($params);
        return $entity ? $entity->current() : false;
    }

    /**
     * @param array|Traversable $data
     * @return array|\ArrayObject|bool
     */
    public function create($data)
    {
        $rows = $this->table->insert($data);
        return ($rows === 1) ? $this->findOneBy([
            $this->identifierName => $this->table->lastInsertValue
        ]) : false;
    }

    /**
     * @param int|string $id
     * @param array|Traversable $data
     * @return \ArrayObject|bool
     */
    public function update($id, $data)
    {
        $rows = $this->table->update($data, [ $this->identifierName => $id ]);
        return ($rows === 1) ? $this->findOne($id) : false;
    }

    /**
     * @param string|integer $id
     * @return boolean
     */
    public function delete($id)
    {
        $rows = $this->table->delete([ $this->identifierName => $id ]);
        return ($rows === 1);
    }
}
