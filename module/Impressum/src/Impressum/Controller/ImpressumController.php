<?php

// module/Impressum/src/Impressum/Controller/ImpressumController.php:
namespace Impressum\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImpressumController extends AbstractActionController
{
  
    
    public function indexAction()
    {
        return new ViewModel();
    }

   
    
    
}