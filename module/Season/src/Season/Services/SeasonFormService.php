<?php

namespace Season\Services;

use Nakade\Abstracts\AbstractFormFactory;
use Season\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Season\Form\Hydrator\SeasonHydrator;

/**
 * Class SeasonFormService
 *
 * @package Season\Services
 */
class SeasonFormService extends AbstractFormFactory
{

    const SEASON_FORM = 'season';
    private $fieldSetService;
    private $repository;



    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {

        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($this->entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        $translator = $services->get('translator');
        $repository = $services->get('Season\Services\RepositoryService');
        $fieldSetService = $services->get('Season\Services\SeasonFieldsetService');

        $this->setRepository($repository);
        $this->setFieldSetService($fieldSetService);
        $this->setTranslator($translator);
        $this->setTranslatorTextDomain($textDomain);

       return $this;
    }

    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     *
     * @param string $typ
     *
     * @return \Zend\Form\Form
     *
     * @throws \RuntimeException
     */
    public function getForm($typ)
    {
        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        switch (strtolower($typ)) {

           case self::SEASON_FORM:
               $tiebreak = $this->getFieldSetService()->getFieldset('tiebreaker');
               $buttons = $this->getFieldSetService()->getFieldset('button');
               $extraTime = $mapper->getByoyomi();

               $form = new Form\SeasonForm($extraTime);
               $form->setHydrator(new SeasonHydrator($this->getEntityManager()));
               $form->setTieBreaker($tiebreak);
               $form->setButtons($buttons);
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown form type was provided.')
               );
        }

        $form->init();
        return $form;
    }

    /**
     * @param mixed $fieldSetService
     */
    public function setFieldSetService($fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
    }

    /**
     * @return mixed
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }



}
