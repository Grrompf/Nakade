<?php
namespace League\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * processing user input, in detail results and postponing
 * matches.
 * 
 */
class MatchdayController extends AbstractController 
{
   
   /**
    * showing all open results of the actual season
    */
    public function indexAction()
    {
        
       return new ViewModel(
                
            array(
                'matches' =>  $this->getService()->getOpenMatches()
            )
        );
       
    }
    
    
    /**
    * Form for edit a result
    */
    public function editAction()
    {
        
        $pid  = (int) $this->params()->fromRoute('id', 0);
        $form = $this->getService()->setResultFormValues($pid);
        //$form = $this->getForm('result');//->setResultFormValues($pid);
       
        if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            $this->getService()->prepareFormForValidation($form, $postData);
            
            //if validated data are saved to database
            if ($this->getService()->processResultData($form)) {
                  return $this->redirect()->toRoute('actual');
            }
        }
          
       return new ViewModel(
           array(
             // 'id'      => $pid, 
             // 'match'   => $this->getService()->getMatch(), 
             // 'form'    => $form
           )
       );
    }

    
}
