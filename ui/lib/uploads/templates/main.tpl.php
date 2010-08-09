<?php 
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
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
 * 
 * Uber simple/fast template for TubePress. Idea from here: http://seanhess.net/posts/simple_templating_system_in_php
 * Sure, maybe your templating system of choice looks prettier but I'll bet it's not faster :)
 */

$idArray = array();
?>
<p>Your video uploads base directory is <code><?php echo ${org_tubepress_uploads_admin_AdminPageHandler::PATH_TO_VIDEO_UPLOADS}; ?></code>. Click any video to edit its properties or thumbnails.</p>

<div class="span-24 last" id="album_list">
	<ul>
	<?php foreach (${org_tubepress_uploads_admin_AdminPageHandler::ADMIN_ALBUM_ARRAY} as $album) : ?>
	
        	<li id="video_<?php echo md5($album->getRelativeContainerPath()); ?>"><a><?php echo $album->getRelativeContainerPath(); ?></a>
        	<?php
            		$idArray[] =  'video_' . md5($album->getRelativeContainerPath());
        
            		if (sizeof($album->getRelativeVideoPaths()) === 0) {
                		echo '</li>';
                		continue;
            		}
            	?>
            		<ul>
            			<?php foreach ($album->getRelativeVideoPaths() as $video) : ?>
                			<li rel="video" title="<?php echo base64_encode($video); ?>"><a><?php echo basename($video); ?></a></li>
            			<?php endforeach; ?>
            		</ul>
	       </li>
	<?php endforeach; ?>
	</ul>
</div>

<script type="text/javascript">

function toggleVideoEditor(base64encoded_relative_path) {
	alert(base64encoded_relative_path);
}

jQuery(document).ready(function () {
	jQuery("li[rel='video']").click(function () { toggleVideoEditor(jQuery(this).attr('title')); });
});

jQuery.jstree._themes = "jstree/themes/";

jQuery("#album_list").jstree({
    	"themes" 	: { 
				"theme" : "classic" 
			},
    	"core" 		: {
				"animation" : 100,
    	        		"initially_open" : [ "#<?php echo implode('", "#', $idArray); ?>" ]
      	         	},
    	"types" 	: {
            			"types" : {
                			"video" : {
                    				"icon" : { 
                        				"image" : "famfam/film.png" //thank you! http://www.famfamfam.com/lab/icons/silk/
                    				}
                			}      
            			}
        		},
        "html_data" 	: {
             			"data" : $("#album_list").html()
         		},
     	"plugins" 	: [ "themes", "html_data", "ui", "cookies", "types" ]
});
</script>
