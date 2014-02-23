<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Form for adding or editing a new User.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class UserForm extends DefaultForm
{
  
    /**
     * Init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {


        $this->setPersonalFields();
        $this->setNicknameFields();

        
         //birthday
        $this->add(
            array(
                'name' => 'birthday',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('Birthday (opt.):'),
                    'format' => 'Y-m-d',
                 ),
                'attributes' => array(
                     'min' => '1900-01-01',
                     'max' => date('Y-m-d'), 
                     'step' => '1',
                )
            )
        );
        
        //User name
        $this->add(
            array(
                'name' => 'username',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('User Name:'),
                ),
                'attributes' => array(
                    
                )
            )
        );

        //kgs name
        $this->add(
            array(
                'name' => 'kgs',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('KGS (opt.):'),
                ),
                'attributes' => array(

                )
            )
        );
        
        //email
        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' =>  $this->translate('email:'),
                    
                ),
                'attributes' => array(
                    'multiple' => false,
                )
            )
        );
        

        $this->setRoleFields();
        $this->setDefaultFields();

    }

    private function setNicknameFields()
    {
        //nick name
        $this->add(
            array(
                'name' => 'nickname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Nick (opt.):'),
                ),
            )
        );

        //anonym
        $this->add(
            array(
                'name' => 'anonym',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('use nick always (anonymizer):'),
                    'checked_value' => true,
                ),
            )
        );
    }

    private function setPersonalFields()
    {
        $this->add(
            array(
                'name' => 'sex',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Salutation:'),
                    'value_options' => array(
                        'm' => $this->translate('Herr'),
                        'f' => $this->translate('Frau'),
                    )
                ),
            )
        );

        //Title
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Title (opt.):'),
                ),
            )
        );

        //first name
        $this->add(
            array(
                'name' => 'firstname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('First Name:'),
                ),
            )
        );

        //family name
        $this->add(
            array(
                'name' => 'lastname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Family Name:'),
                ),
            )
        );
    }


    private function setRoleFields()
    {
        //roles
        $this->add(
            array(
                'name' => 'role',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Role'),
                    'value_options' => array(
                        'guest'     => $this->translate('Guest'),
                        'user'      => $this->translate('User'),
                        'member'    => $this->translate('Member'),
                        'moderator' => $this->translate('Moderator'),
                        'admin'     => $this->translate('Administrator'),

                    )
                ),
                'attributes' => array(
                    'value' => 'user'
                )

            )


        );
    }

     
    /**
     * get the InputFilter
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new \Zend\InputFilter\InputFilter();
        $this->setPersonalFilter($filter);
        $this->setNicknameFilter($filter);
        $this->setUsernameFilter($filter);
        $this->setEmailFilter($filter);
        $this->setBirthdayFilter($filter);
               
         
         return $filter;
    }

    private function setBirthdayFilter(&$filter)
    {
        $filter->add(
            array(
                'name' => 'birthday',
                'required' => false,
                'validators' => array(
                    array('name'    => 'Date',
                        'options' => array (
                            'format' => 'Y-m-d',
                        )
                    ),
                )
            )
        );

    }

    private function setEmailFilter(&$filter)
    {
        $filter->add(
            array(
                'name' => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'min'  => '6',
                            'max'  => '120',
                        )
                    ),
                    array('name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array(
                            'entity'    => 'User\Entity\User',
                            'property'  => 'email',
                            'exclude'  => $this->getIdentifierValue(),
                            'adapter'  => $this->getEntityManager(),
                        )
                    )

                )
            )
        );
    }

    private function setUsernameFilter(&$filter)
    {
        $filter->add(
            array(
                'name' => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '20',
                        )
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array(
                            'entity'    => 'User\Entity\User',
                            'property' => 'username',
                            'exclude'  => $this->getIdentifierValue(),
                            'adapter'  => $this->getEntityManager(),
                        )
                    ),

                )
            )
        );
    }

    private function setNicknameFilter(&$filter)
    {
        $filter->add(
            array(
                'name' => 'nickname',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '20',
                        )
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array(
                            'entity'    => 'User\Entity\User',
                            'property' => 'nickname',
                            'exclude'  => $this->getIdentifierValue(),
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                )
            )
        );
    }

    private function setPersonalFilter(&$filter)
    {
        $filter->add(
            array(
                'name' => 'title',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '10',
                        )
                    ),
                )
            )
        );

        $filter->add(
            array(
                'name' => 'firstname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '20',
                        )
                    ),
                )
            )
        );

        $filter->add(
            array(
                'name' => 'lastname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '30',
                        )
                    ),
                )
            )
        );


    }
}
?>
