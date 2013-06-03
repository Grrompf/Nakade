<?php
namespace User\Form\Fieldset;

use \Zend\Form\Fieldset;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;

/**
 * Abstract form implementing a translator.
 * Using the translate method will return the given string if
 * no translator is set.
 */
class AbstractFieldset 
            extends Fieldset 
            implements TranslatorAwareInterface
{
   
    protected $_translator;
    protected $_textDomain="User";
    protected $_entity_manager;
    
    /**
   * Sets the EntityManager
   *
   * @param EntityManager $entitymanager
   * @access protected
   * @return ActionController
   */
   public function setEntityManager($em)
   {
      $this->_entity_manager = $em;
      return $this;
   }

  /**
   * Returns the EntityManager
   *
   * Fetches the EntityManager from ServiceLocator if it has not been initiated
   * and then returns it
   *
   * @access protected
   * @return EntityManager
   */
   public function getEntityManager()
   {
      return $this->_entity_manager;
   }
   
    /**
     * Sets translator to use in helper
     *
     * @param  Translator $translator  [optional] translator.
     *          Default is null, which sets no translator.
     * @param  string     $textDomain  [optional] text domain
     *          Default is null, which skips setTranslatorTextDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslator(
            Translator $translator = null, 
            $textDomain = null
            ) 
    {
        if(isset($translator))
            $this->_translator=$translator;
    
        if(isset($textDomain))
            $this->_textDomain=$textDomain;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
     */
    public function getTranslator()
    {   
        return $this->_translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return isset($this->_translator);
    }

    /**
     * Sets whether translator is enabled and should be used
     *
     * @param  bool $enabled [optional] whether translator should be used.
     *                       Default is true.
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true) {;}
    
    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled() {;}

    /**
     * Set translation text domain
     *
     * @param  string $textDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->_textDomain=$textDomain;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->_textDomain;
    }
    
    /**
    * Helper for i18N. If a translator is set to the controller, the 
    * message is translated.
    *  
    * @param string $message
    * @return string
    */
   public function translate($message) 
   {
   
       $translator = $this->getTranslator();
       if ($translator===null)
           return $message;
       
       return $translator->translate(
                  $message, 
                  $this->getTranslatorTextDomain()
              );
       
   }
    
}
?>