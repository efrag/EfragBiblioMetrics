<?php

namespace Efrag\Lib\BiblioMetrics\Ranking\Method;

use Efrag\Lib\BiblioMetrics\Ranking\BaseRanking;

/**
 * Class Ordinal
 * @package Efrag\Lib\BiblioMetrics\Ranking
 */
class Ordinal extends BaseRanking
{
    /**
     * Helper method that returns the average of the integer values between $firstRank and the total number of
     * entities that share the same value. For example if we say that we have started from rank 15 and we have 5
     * entities with the same score then the position that these will occupy would be (15 + 16 + 17 + 18 + 19) / 5 =  17
     *
     * @param integer $firstRank
     * @param array $same
     *
     * @return float
     */
    protected function getCommonRankings($firstRank, array $same)
    {
        $commonRank = $firstRank;

        for ($i = 1; $i < count($same); $i++) {
            $commonRank += $firstRank + $i;
        }
        $commonRank = $commonRank / count($same);

        return $commonRank;
    }

    /**
     * Helper method that modifies the results array by adding the entities along with their scores for the next group
     * of entities that share the same score.
     *
     * @param integer $start
     * @param array $same
     * @param array $results
     * @return array
     */
    protected function updateResultSet($start, array $same, array $results)
    {
        if (count($same) === 1) {
            $results[array_pop($same)] = $start;
        } else {
            $commonRank = $this->getCommonRankings($start, $same);

            foreach ($same as $sameEntity) {
                $results[$sameEntity] = $commonRank;
            }
        }

        return $results;
    }

    /**
     * Method that actually generates the Ordinal ranking of the entities included in the entityBaseRank array. The
     * array should be sorted in ascending order based on the BaseRanking of the entities and entities with the same
     * score should have the same rank assigned to them.
     *
     * @return array
     */
    protected function generateFinalRanking()
    {
        $results = [];

        list($entity, $rank) = each($this->entityBaseRank);
        $same = [$entity];
        $currentRank = $rank;
        $start = 1;

        while (list($entity, $rank) = each($this->entityBaseRank)) {
            if ($rank === $currentRank) {
                // the currently examined entity has the same base rank as the previous one
                $same[] = $entity;
            } else {
                // the base rank has changed, so we need to update the result set
                $results = $this->updateResultSet($start, $same, $results);

                $start = $start + count($same);

                $same = [$entity];
                $currentRank = $rank;
            }
        }

        return $this->updateResultSet($start, $same, $results);
    }
}
