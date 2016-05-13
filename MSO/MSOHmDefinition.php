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
 * Class MSOHmDefinition
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
class MSOHmDefinition extends MSO
{
    /**
     * @return bool
     */
    protected function isInitialized()
    {
        return (isset($this->paperCitations) && isset($this->depth));
    }

    /**
     * In MSOHmDefinition all citations count for each of the papers. Self-citations and cycles do participate in the
     * counts
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
                    $curGen[$paperId][] = $pgGen1;
                }
            }

            $this->mso[$paperId][$depth] = count($curGen[$paperId]);
        }

        return $curGen;
    }
}
