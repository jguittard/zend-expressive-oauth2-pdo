<?php

namespace Zend\Expressive\OAuth2\Mapper\Pdo;

use Interop\Container\ContainerInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class PdoMapperFactory
 *
 * @package   Zend\Expressive\OAuth2\Mapper\Pdo
 * @author    Julien Guittard <julien.guittard@me.com>
 */
class PdoMapperFactory
{
    /**
     * @param ContainerInterface $container
     * @param  string $requestedName
     * @param  array|null $options
     * @return PdoMapper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $config = $config['oauth2'];
        $repositoryConfig = $config['repository'][$requestedName];
        $entityClass = $repositoryConfig['entity'];
        $hydratorClass = $repositoryConfig['hydrator'];
        $dbAdapter = $container->get($config['adapter']);
        $table = new TableGateway($repositoryConfig['table'], $dbAdapter, null, new HydratingResultSet(new $hydratorClass, new $entityClass));
        return new PdoMapper($table, $repositoryConfig['primary_key']);
    }
}