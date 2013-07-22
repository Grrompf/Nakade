<?php

namespace League\Factory;

use Nakade\Abstracts\AbstractFormFactory;
use League\Statistics\Results;
use Traversable;
use League\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;



/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class FormFactory extends AbstractFormFactory
{
    
    /**
     * implemented ServiceLocator
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \User\Services\FormFactory
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $textDomain = isset($config['League']['text_domain']) ? 
            $config['League']['text_domain'] : null;
        
        
        $translator = $services->get('translator');
       
        //@todo: Übersetzung der Std Validatoren
        
       //needed for translating validation messages
        $translator->addTranslationFile( 
            'phpArray', 
            'vendor/zendframework/zendframework/resources/languages/de/Zend_Validate.php',
            'default',
            'de_DE'    
        );
        $this->setTranslator($translator, $textDomain);
        
        //EntityManager for database access by doctrine
        $entityManager = $services->get('Doctrine\ORM\EntityManager');
        $this->setEntityManager($entityManager);
        
        return $this;
    }
    
    /**
     * fabric method for getting the form needed. expecting the form name as
     * string. Throws an exception if provided typ is unknown.
     * 
     * @param string $typ
     * @return Form
     * @throws RuntimeException
     */
    public function getForm($typ)
    {
        
        switch (strtolower($typ)) {
           
           case "league":   $form = new Form\LeagueForm();
                            break;
               
           case "player":   $form = new Form\ParticipantsForm();
                            break;
                        
           case "result":   $form = new Form\ResultForm();
                            $results  = new Results();
                            $results->setTranslator(
                                    $this->getTranslator(),
                                    $this->getTranslatorTextDomain()
                            );
        
                            //set result types
                            $form->setResultlist($results->getResultTypes());
                            break;                          
                        
           case "season":   $form = new Form\SeasonForm();
                            break;
           
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown form type was provided.')
           );              
        }
        
        //em 
        $entityManager = $this->getEntityManager();
        $form->setEntityManager($entityManager);
   
        //translator
        $form->setTranslator(
            $this->getTranslator(), 
            $this->getTranslatorTextDomain()
        );
        
        //init + filter
        $form->init();
        $form->setInputFilter($form->getFilter());
        
        return $form;
    }      
}
