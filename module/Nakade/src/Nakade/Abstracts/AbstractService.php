<?php
namespace Nakade\Abstracts;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Extending for having the mail transport system and default message body.
 * In addition an optional translator can be set
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
abstract class AbstractService extends AbstractTranslation implements FactoryInterface
{

    protected $_mailFactory;
    protected $_mapperFactory;

     /**
     * get a mapper factory for DB interaction
     * @return object
     */
    public function getMapperFactory()
    {
        return $this->_mapperFactory;
    }

    /**
     * set a mapper for DB interaction
     * @param object $factory
     *
     * @return \Nakade\Abstracts\AbstractService
     */
    public function setMapperFactory($factory)
    {
        $this->_mapperFactory=$factory;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getMapper($name)
    {
        if (null === $this->_mapperFactory) {
            throw new \RuntimeException(
                sprintf('Mapper factory could not be found in service.')
            );
        }

        return $this->_mapperFactory->getMapper($name);
    }



    /**
     * get an instance of a mail Factory
     * @return object
     */
    public function getMailFactory()
    {
        return $this->_mailFactory;
    }

    /**
     * @param mixed $factory
     *
     * @return $this
     */
    public function setMailFactory($factory)
    {
        $this->_mailFactory=$factory;
        return $this;
    }

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed
     */
    abstract public function createService(ServiceLocatorInterface $services);
}

