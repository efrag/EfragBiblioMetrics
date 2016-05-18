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

namespace Efrag\Lib\BiblioMetrics\Utils;

/**
 * Class GraphProcessing
 * @package Efrag\Lib\BiblioMetrics\Utils
 */
class GraphProcessing
{
    /**
     * Helper method to convert the provided paperAge to the equivalent paperYear array that is expected from the
     * metric classes. The age array is easier to handle with regards to writing tests due to the fact that if we set
     * the age rather than the year we
     * @param array $paperAge
     * @param null $curYear
     * @return array
     */
    public static function generatePublicationYear(array $paperAge, $curYear = null)
    {
        $pubYears = [];

        foreach ($paperAge as $paperId => $age) {
            // age = (curYear - pubYear) + 1 =>
            // age = curYear - pubYear + 1 =>
            // pubYear = curYear - age + 1
            $pubYears[$paperId] = (is_null($curYear) ? date('Y') : $curYear) - $age + 1;
        }

        return $pubYears;
    }
}
