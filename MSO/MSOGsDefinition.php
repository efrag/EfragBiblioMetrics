<?php
/**
 * Copyright (C) 2016 Eleni Fragkiadaki
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Efrag\Lib\BiblioMetrics\MSO;

/**
 * Class MSOGsDefinition
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
class MSOGsDefinition extends MSOPaper
{
    /**
     * In MSOGsDefinition self-citations and citations from papers encountered in previous generations are not included.
     * Also only one citation is counted for in cases we have multiple citations of the same length originating from the
     * same source paper.
     *
     * @param int $depth
     * @param array $prevGen
     * @return array
     */
    protected function findPath($depth, array $prevGen)
    {
        $paperIds = array_keys($this->paperCitations);
        $curGen = [];

        foreach ($paperIds as $paperId) {
            $prevGenCitations = $prevGen[$paperId];
            $curGen[$paperId] = [];

            foreach ($prevGenCitations as $prevGenCitation) {
                $prevGenCitationGen1 = $this->paperCitations[$prevGenCitation];

                foreach ($prevGenCitationGen1 as $pgGen1) {
                    if ($paperId != $pgGen1 && !in_array($pgGen1, $this->distinct[$paperId])) {
                        $curGen[$paperId][] = $pgGen1;
                        $this->distinct[$paperId][] = $pgGen1;
                    }
                }
            }

            $this->mso[$paperId][$depth] = count($curGen[$paperId]);
        }

        return $curGen;
    }
}
