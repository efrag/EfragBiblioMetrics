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
 * Class MSOAuthorGsDefinition
 * @package Efrag\Lib\BiblioMetrics\MSO
 */
class MSOAuthorGsDefinition extends MSOAuthor
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
        $curGen = [
            'papers' => array_fill_keys($paperIds, []),
            'authors' => array_fill_keys($paperIds, [])
        ];

        foreach ($paperIds as $paperId) {
            $prevGenCitations   = $prevGen['papers'][$paperId];
            $paperAuthors       = $this->paperAuthors[$paperId];

            $curGen['authors'][$paperId] = array_fill_keys($paperAuthors, []);

            foreach ($prevGenCitations as $prevGenCitation) {
                $prevGenCitationGen1 = $this->paperCitations[$prevGenCitation];

                foreach ($prevGenCitationGen1 as $pgGen1) {

                    // here we want a check that:
                    // * this is not a self citation (in the sense of a cycle)
                    // * the paper is not included in any of the previous generations
                    if ($paperId != $pgGen1 && !in_array($pgGen1, $this->distinct[$paperId])) {
                        $curGen['papers'][$paperId][] = $pgGen1;

                        foreach ($paperAuthors as $author) {
                            if (!in_array($author, $this->paperAuthors[$pgGen1])) {
                                $curGen['authors'][$paperId][$author][] = $pgGen1;
                            }
                        }
                        $this->distinct[$paperId][] = $pgGen1;
                    }
                }
            }

            foreach ($paperAuthors as $author) {
                $this->mso[$paperId][$author][$depth] = count(
                    $curGen['authors'][$paperId][$author]
                );
            }
        }

        return $curGen;
    }
}
