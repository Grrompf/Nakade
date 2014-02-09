<?php
namespace Application\View\Helper;

use Nakade\Abstracts\AbstractViewHelper;
/**
 * shows an reset pwd link image to user
 */
class Submenu extends AbstractViewHelper
{
    
    public function __invoke($title, $items, $id=1)
    {
        
        $submenu = '<li class="dropdown">';
        $submenu .= '<a id="drop'.$id.'" role="button" class="dropdown-toggle"';
        $submenu .= ' data-toggle="dropdown" href="#">';
        $submenu .= $title;
        $submenu .= '<b class="caret"></b></a>';
        
        $submenu .= $this->getSubmenuItems($items);
        $submenu .= '</li>';
            
        return $submenu;
       
       
    }
    
   
    private function getTitle($title) 
    {
        $placeholder = array(
            'title'   => $title,
        );
        
        $test  = '<li class="dropdown">';
        $test .= '<a id="drop1" role="button" class="dropdown-toggle" data-toggle="dropdown" href="#">';
        $test .= '%title%<b class="caret"></b></a>';
        
        return $this->setPlaceholders($test, $placeholder);
    }
    
     private function getSubmenuItems($items) 
     {
         
          $test  = '<ul class="dropdown-menu" role="menue" aria-labelledby="drop1">';
          
          
          
          foreach($items as $name => $route) {
             
              $placeholder = array(
                'url'   => $route,
                'name'  => $name
              );
              
              $menueItem  = '<li role="presentation">';
              $menueItem .= '<a role="menuitem" tabindex="-1" href="%url%">%name%</a>';
              $menueItem .= '</li>';
              
              $test .= $this->setPlaceholders($menueItem, $placeholder); 
               
           }
          
          $test .= '</ul>';
          
          return $test;
        
     }
    
}