<?php
namespace Moderator\Controller;

use Moderator\Entity\SupportMessage;
use Moderator\Services\FormService;
use Moderator\Services\MailService;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;

/**
 * Class TicketController
 *
 * @package Moderator\Controller
 */
class TicketController extends DefaultController
{
    const HOME = 'ticket';

    //todo: PERMISSION isLM OR isModerator OR isReferee
    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $allTickets = $this->getTicketMapper()->getTicketsByPages($this->identity()->getId());
        $pagination = new ItemPagination($allTickets);

        return new ViewModel(
            array(
                'supportRequests' => $this->getTicketMapper()->getTicketsByPages(
                        $this->identity()->getId(),
                        $pagination->getOffset($page)
                    ),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }

    /**
     *
     * @return array|ViewModel
     */
    public function detailAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(
            array(
                'ticket' => $this->getTicketMapper()->getTicketById($ticketId),
            )
        );
    }

    /**
     *
     * @return array|ViewModel
     */
    public function mailAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);

        $ticket = $this->setTicketState($ticketId, self::STAGE_WAITING);
        $author = $this->getUserById($this->identity()->getId());
        $message = new SupportMessage($ticket, $author);

        /* @var $form \Moderator\Form\MailForm */
        $form = $this->getForm(FormService::MAIL_FORM);
        $form->bindEntity($message);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute(self::HOME, array('action' => 'detail', 'id' => $ticketId));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $message = $form->getData();
                $this->sendMail($ticket, MailService::REPLY_INFO_MAIL);
                $this->getMapper()->save($message);

                $this->flashMessenger()->addSuccessMessage('New Support Reply Message');
                return $this->redirect()->toRoute(self::HOME);
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }


    /**
     *
     * @return array|ViewModel
     */
    public function doneAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_DONE);
        if (!is_null($ticket)) {
            $ticket->setDoneDate(new \DateTime());
            $this->sendMail($ticket, MailService::STAGE_CHANGED_MAIL);
            $this->flashMessenger()->addSuccessMessage('Ticket done.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

    /**
     *
     * @return array|ViewModel
     */
    public function acceptAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_IN_PROCESS);
        if (!is_null($ticket)) {
            $ticket->setStartDate(new \DateTime());
            $this->sendMail($ticket, MailService::STAGE_CHANGED_MAIL);
            $this->flashMessenger()->addSuccessMessage('Ticket accepted.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

    /**
     *
     * @return array|ViewModel
     */
    public function cancelAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_CANCELED);

        if (!is_null($ticket)) {
            $this->flashMessenger()->addSuccessMessage('Ticket canceled.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

    /**
     *
     * @return array|ViewModel
     */
    public function reopenAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_REOPENED);
        if (!is_null($ticket)) {
            $this->sendMail($ticket, MailService::STAGE_CHANGED_MAIL);
            $this->flashMessenger()->addSuccessMessage('Ticket reopened.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

}
