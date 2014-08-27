<?php
namespace Application\View\Helper;
use Support\Services\RepositoryService;

/**
 * Class GetWaitingTicketAmount
 *
 * @package Appointment\View\Helper
 */
class GetWaitingTicketAmount extends DefaultViewHelper
{

    /**
     * @return string
     */
    public function __invoke()
    {
        $uid = $this->getIdentity()->getId();
        return count($this->getMapper()->getWaitingTicketsByUser($uid));
    }

    /**
     * @return \Support\Mapper\TicketMapper
     */
    private function getMapper()
    {
        /* @var $repository \Support\Services\RepositoryService */
        $repository = $this->getService('Support\Services\RepositoryService');
        return $repository->getMapper(RepositoryService::TICKET_MAPPER);
    }

}
