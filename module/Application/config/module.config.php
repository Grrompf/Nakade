<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication
 * for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;
return array(

    'view_helpers' => array(
        'invokables' => array(
            'Submenu' => 'Application\View\Helper\Submenu',
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' =>
                'Application\Controller\IndexController',
            'Application\Controller\Training' =>
                'Application\Controller\TrainingController',
            'Application\Controller\Privacy' =>
                'Application\Controller\PrivacyController',
            'Application\Controller\Imprint' =>
                'Application\Controller\ImprintController',

        ),
        'factories' => array(
            'Application\Controller\Contact' =>
                'Application\Services\ContactControllerFactory',
        ),
    ),

    'router' => array(
            'routes' => array(

                'home' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Application\Controller',
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'index',
                        ),
                    ),
                ),
                'training' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/training',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Application\Controller',
                            'controller' => 'Application\Controller\Training',
                            'action'     => 'index',
                        ),
                    ),
                ),
                'privacy' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/privacy',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Application\Controller',
                            'controller' => 'Application\Controller\Privacy',
                            'action'     => 'index',
                        ),
                    ),
                ),
                'imprint' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/imprint',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Application\Controller',
                            'controller' => 'Application\Controller\Imprint',
                            'action'     => 'index',
                        ),
                    ),
                ),
                'contact' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/contact',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Application\Controller',
                            'controller' => 'Application\Controller\Contact',
                            'action'     => 'index',
                        ),
                    ),
                ),


        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Application\Services\ContactFormFactory'
                => 'Application\Services\ContactFormFactory',
            'Application\Services\ContactCaptchaFactory'
                => 'Application\Services\ContactCaptchaFactory',
            'Application\Services\MailService'
                => 'Application\Services\MailService',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Application',
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ .
                  '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ .
                  '/../view/application/index/index.phtml',
            'error/403'               => __DIR__ .
                  '/../view/error/403.phtml',
            'error/404'               => __DIR__ .
                  '/../view/error/404.phtml',
            'error/index'             => __DIR__ .
                  '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
