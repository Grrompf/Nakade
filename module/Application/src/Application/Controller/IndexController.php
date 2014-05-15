<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for
 * the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Message\Mapper\MessageMapper;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 *
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        $blogWidget  = $this->forward()
                            ->dispatch('/Blog/Controller/Blog');

        $tableWidget = $this->forward()
                            ->dispatch('/League/Controller/ActualSeason');

        $page = new ViewModel(array( 'No' => "5"));

        $page->addChild($blogWidget, 'blogWidget');
        $page->addChild($tableWidget, 'tableWidget');

        return $page;

    }

}
