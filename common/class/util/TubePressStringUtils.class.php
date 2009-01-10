﻿<?php
/**
 * Copyright 2006, 2007, 2008, 2009 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Some string utilities
 *
 */
class TubePressStringUtils
{
    /**
     * Replaces the first occurence of a string by another string
     *
     * @param string $search  The haystack
     * @param string $replace The replacement string
     * @param string $str     The needle
     * 
     * @return string The haystack with the first needle replaced
     *     by the replacement string
     */
    public function replaceFirst($search, $replace, $str)
    {    
        $l    = strlen($str);
        $a    = strpos($str, $search);
        $b    = $a + strlen($search);
        $temp = substr($str, 0, $a) . $replace . substr($str, $b, ($l-$b));
        return $temp;
    }
}