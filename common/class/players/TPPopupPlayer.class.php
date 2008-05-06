<?php
/**
 * Copyright 2006, 2007, 2008 Eric D. Hough (http://ehough.com)
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
 * Plays videos in an HTML popup window
 */
class TPPopupPlayer extends TubePressPlayer
{
    public function __construct() {
        $this->setName(TubePressPlayer::popup);
        $this->setTitle("in a popup window");
    }
	
	/**
	 * Tells the gallery how to play the videos
	 */
	public function getPlayLink(TubePressVideo $vid, TubePressStorage_v160 $stored)
	{
	    global $tubepress_base_url;

	    $title = $vid->getTitle();
	    $height = $stored->getCurrentValue(TubePressEmbeddedOptions::EMBEDDED_HEIGHT);
	    $width = $stored->getCurrentValue(TubePressEmbeddedOptions::EMBEDDED_WIDTH);
	    $embed = new TubePressEmbeddedPlayer($vid, $stored);
		
	    $url = new Net_URL($tubepress_base_url . "/common/ui/popup.php");
	    $url->addQueryString("embed", $embed->toString());
	    $url->addQueryString("name", $title);
	    
	    return "href='#' onclick='tubePress_popup(" .
            '"' . $url->getURL() . '",' . $height . ',' . $width . ')\''; 
	}
}
?>
