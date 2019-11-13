?><?php
/**
 * Module-Input: mpEmbedYouTube
 *
 * Module to embed YouTube videos in CONTENIDO
 * See http://youtube.com/
 *
 * @package     CONTENIDO_Modules
 * @subpackage  mpEmbedYouTube
 * @author      Murat Purc <murat@purc.de>
 * @copyright   Copyright (c) 2012 Murat Purc (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 * @version     $Id: $
 */

// #################################################################################################
// INITIALIZATION

$modContext = new stdClass();

$modContext->id = uniqid();

$modContext->videoSizes = array(
    '426x240' => '426 x 240',
    '640x360' => '640 x 360',
    '854x480' => '854 x 480',
    '1280x720' => '1280 x 720',
    '1920x1080' => '1920 x 1080',
    '2560x1440' => '2560 x 1440',
    '3840x2160' => '3840 x 2160',
    'custom' => mi18n("CUSTOM")
);

// #################################################################################################
// CMS VARIABLES &amp; LOGIC

$modContext->cmsValueVideoUrl = "CMS_VALUE[1]";
$modContext->cmsVarVideoUrl = "CMS_VAR[1]";

$modContext->cmsValueVideoSize = "CMS_VALUE[2]";
$modContext->cmsVarVideoSize = "CMS_VAR[2]";
$modContext->idVideoSize = "video_size_" . $modContext->id;

$modContext->idVideoCustomSize = "video_custom_size_" . $modContext->id;

$modContext->cmsValueCustomWidth = "CMS_VALUE[3]";
$modContext->cmsVarCustomWidth = "CMS_VAR[3]";

$modContext->cmsValueCustomHeight = "CMS_VALUE[4]";
$modContext->cmsVarCustomHeight = "CMS_VAR[4]";

if ('custom' != $modContext->cmsValueVideoSize) {
    $modContext->cmsValueCustomWidth = '';
    $modContext->cmsValueCustomHeight = '';
}

$modContext->cmsValueSuggestedVideos = "CMS_VALUE[5]";
$modContext->cmsVarSuggestedVideos = "CMS_VAR[5]";
$modContext->cmsChkSuggestedVideos = ("1" == $modContext->cmsValueSuggestedVideos) ? ' checked="checked"' : '';
$modContext->idSuggestedVideos = "suggested_videos_" . $modContext->id;

$modContext->cmsValueUseHttps = "CMS_VALUE[6]";
$modContext->cmsVarUseHttps = "CMS_VAR[6]";
$modContext->cmsChkUseHttps = ("1" == $modContext->cmsValueUseHttps) ? ' checked="checked"' : '';
$modContext->idUseHttps = "use_https_" . $modContext->id;

$modContext->cmsValuePrivacyMode = "CMS_VALUE[7]";
$modContext->cmsVarPrivacyMode = "CMS_VAR[7]";
$modContext->cmsChkPrivacyMode = ("1" == $modContext->cmsValuePrivacyMode) ? ' checked="checked"' : '';
$modContext->idPrivacyMode = "privacy_mode_" . $modContext->id;

$modContext->idResetOptions = "reset_options_" . $modContext->id;

// #################################################################################################
// OUTPUT

?>

<!-- load jQuery if not available -->
<script>!window.jQuery &amp;&amp; document.write(unescape('%3Cscript src="scripts/jquery/jquery.js"%3E%3C/script%3E'))</script>

<table cellspacing="0" cellpadding="3" border="0" class="mpEmbedYouTube">

<tr>
    <td valign="top" class="text_medium_bold"><?php echo mi18n("EMBED_YOUTUBE_VIDEO") ?></td>
    <td class="text_medium">
        <!-- video url -->
        <?php echo mi18n("VIDEO_URL") ?>:<br />
        <input type="text" class="text_medium" style="width:300px;" name="<?php echo $modContext->cmsVarVideoUrl ?>" value="<?php echo $modContext->cmsValueVideoUrl ?>" /><br />
        <small>
        - http://www.youtube.com/watch?v=6jxsnIRpy2E
          [<a href="http://www.youtube.com/watch?v=6jxsnIRpy2E" class="blue" target="_blank"> ? </a>]<br />
        - http://youtu.be/6jxsnIRpy2E
          [<a href="http://youtu.be/6jxsnIRpy2E" class="blue" target="_blank"> ? </a>]
        </small><br />
        <br />

        <!-- video size -->
        <?php echo mi18n("VIDEO_SIZE") ?>:
        <div style="width:350px;">
            <select name="<?php echo $modContext->cmsVarVideoSize ?>" id="<?php echo $modContext->idVideoSize ?>" style="float:left;width:150px;margin-right:10px;">
            <?php
            foreach ($modContext->videoSizes as $k => $v) {
                $sel = ($modContext->cmsValueVideoSize == $k) ? ' selected="selected"' : '';
                echo '<option value="' . $k . '"' . $sel . '>' . $v . '</option>' . "\n";
            }
            ?>
            </select>
            <div id="<?php echo $modContext->idVideoCustomSize ?>" style="float:left;width:175px;">
                <input type="text" class="text_medium" style="width:50px;" name="<?php echo $modContext->cmsVarCustomWidth ?>" value="<?php echo $modContext->cmsValueCustomWidth ?>" />
                x
                <input type="text" class="text_medium" style="width:50px;" name="<?php echo $modContext->cmsVarCustomHeight ?>" value="<?php echo $modContext->cmsValueCustomHeight ?>" />
                <small><?php echo mi18n("(W_x_H)") ?></small>
            </div>
            <div style="clear:both;"></div>
        </div>
        <br />

        <!-- show suggested videos -->
        <input type="radio" name="<?php echo $modContext->cmsVarSuggestedVideos ?>" id="<?php echo $modContext->idSuggestedVideos ?>" value="1"<?php echo $modContext->cmsChkSuggestedVideos ?>>
        <label for="<?php echo $modContext->idSuggestedVideos ?>"><?php echo mi18n("SHOW_SUGGESTED_VIDEOS_WHEN_THE_VIDEO_FINISHES") ?></label><br />

        <!-- use https -->
        <input type="radio" name="<?php echo $modContext->cmsVarUseHttps ?>" id="<?php echo $modContext->idUseHttps ?>" value="1"<?php echo $modContext->cmsChkUseHttps ?>>
        <label for="<?php echo $modContext->idUseHttps ?>"><?php echo mi18n("USE_HTTPS") ?></label>
        [<a href="http://www.google.com/support/youtube/bin/answer.py?answer=171780&amp;expand=UseHTTPS#HTTPS" class="blue" target="_blank"> ? </a>]<br />

        <!-- enable privacy-enhanced mode -->
        <input type="radio" name="<?php echo $modContext->cmsVarPrivacyMode ?>" id="<?php echo $modContext->idPrivacyMode ?>" value="1"<?php echo $modContext->cmsChkPrivacyMode ?>>
        <label for="<?php echo $modContext->idPrivacyMode ?>"><?php echo mi18n("ENABLE_PRIVACY_ENHANCED_MODE") ?></label>
        [<a href="http://www.google.com/support/youtube/bin/answer.py?answer=171780&amp;expand=PrivacyEnhancedMode#privacy" class="blue" target="_blank"> ? </a>]<br />

        <div style="margin-top:10px"><a href="#" class="blue" id="<?php echo $modContext->idResetOptions ?>"><?php echo mi18n("RESET_OPTIONS") ?></a></div>
    </td>
</tr>

</table>
<script type="text/javascript">
(function($) {
    $(document).ready(function() {
        var $custom = $('#<?php echo $modContext->idVideoCustomSize ?>');

        var _toggleCustom = function(selectedValue) {
            if ('custom' == selectedValue) {
                $custom.show();
            } else {
                $custom.hide();
            }
        };

        // on video size select change
        $('#<?php echo $modContext->idVideoSize ?>').change(function(e) {
            var value = $(this).find("option:selected").attr("value");
            _toggleCustom(value);
        });

        // on reset options click
        $('#<?php echo $modContext->idResetOptions ?>').click(function(e) {
            $.each(['#<?php echo $modContext->idSuggestedVideos ?>','#<?php echo $modContext->idUseHttps ?>','#<?php echo $modContext->idPrivacyMode ?>'], function(i, v) {
                $(v).removeAttr('checked');
            });
            return false;
        });

        // initial custom sizes state
        _toggleCustom('<?php echo $modContext->cmsValueVideoSize ?>');
    });
})(jQuery);
</script>

<?php

unset($modContext);