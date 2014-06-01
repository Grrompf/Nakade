<?php
namespace League\Standings\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\GameStats;

/**
 * Class LostGames
 *
 * @package League\Standings\Games
 */
class LostGames extends GameStats implements GameStatsInterface
{

    /**
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        /* @var $match \Season\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (is_null($match->getResult()) ||
                $match->getResult()->getId() == RESULT::DRAW  ||
                $match->getResult()->getId() == RESULT::SUSPENDED ||
                $match->getWinner()->getId() == $playerId
            ) {
                continue;
            }

            if ($match->getBlack()->getId() == $playerId || $match->getWhite()->getId() == $playerId ) {
                $count++;
            }
        }
        return $count;
    }


}

