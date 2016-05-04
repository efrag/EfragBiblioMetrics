<?php

namespace Efrag\Lib\BiblioMetrics\Ranking;

/**
 * Interface RankingInterface
 * @package Efrag\Lib\BiblioMetrics
 */
interface RankingInterface
{
    /**
     * Method used to provide the entityValue array
     *
     * @param array $entityValue
     * @return mixed
     */
    public function setEntityValue(array $entityValue);

    /**
     * Method to retrieve the final rankings
     *
     * @return array
     */
    public function getRanking();
}
