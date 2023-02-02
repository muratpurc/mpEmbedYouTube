?><?php
/**
 * Module-Input: mpEmbedYouTube
 *
 * Module to embed YouTube videos in CONTENIDO
 * See https://youtube.com/
 *
 * @package     CONTENIDO_Modules
 * @subpackage  mpEmbedYouTube
 * @author      Murat Purç <murat@purc.de>
 * @copyright   Murat Purç (https://www.purc.dee)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 */

// ##############################################
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

// ##############################################
// CMS VARIABLES & LOGIC

$modContext->cmsValueVideoUrl = "CMS_VALUE[1]";
$modContext->cmsVarVideoUrl = "CMS_VAR[1]";

$modContext->cmsValueVideoSize = "CMS_VALUE[2]";
$modContext->cmsVarVideoSize = "CMS_VAR[2]";

$modContext->cmsValueCustomWidth = "CMS_VALUE[3]";
$modContext->cmsVarCustomWidth = "CMS_VAR[3]";

$modContext->cmsValueCustomHeight = "CMS_VALUE[4]";
$modContext->cmsVarCustomHeight = "CMS_VAR[4]";

if ($modContext->cmsValueVideoSize !== 'custom') {
    $modContext->cmsValueCustomWidth = '';
    $modContext->cmsValueCustomHeight = '';
}

$modContext->cmsValueSuggestedVideos = "CMS_VALUE[5]";
$modContext->cmsVarSuggestedVideos = "CMS_VAR[5]";
$modContext->cmsChkSuggestedVideos = ($modContext->cmsValueSuggestedVideos === '1') ? ' checked="checked"' : '';

$modContext->cmsValueUseHttp = "CMS_VALUE[6]";
$modContext->cmsVarUseHttps = "CMS_VAR[6]";
$modContext->cmsChkUseHttps = ($modContext->cmsValueUseHttp === '1') ? ' checked="checked"' : '';

$modContext->cmsValuePrivacyMode = "CMS_VALUE[7]";
$modContext->cmsVarPrivacyMode = "CMS_VAR[7]";
$modContext->cmsChkPrivacyMode = ($modContext->cmsValuePrivacyMode === '1') ? ' checked="checked"' : '';

$modContext->cmsValuePlayerControls = "CMS_VALUE[8]";
$modContext->cmsVarPlayerControls = "CMS_VAR[8]";
$modContext->cmsChkPlayerControls = ($modContext->cmsValuePlayerControls === '1') ? ' checked="checked"' : '';

// ##############################################
// OUTPUT

?>

<!-- load jQuery if not available -->
<script>!window.jQuery && document.write(unescape('%3Cscript src="scripts/jquery/jquery.js"%3E%3C/script%3E'))</script>

<table cellspacing="0" cellpadding="3" border="0" class="mpEmbedYouTube" id="mpEmbedYouTube_<?php echo $modContext->id ?>">
    <tr>
        <td valign="top" class="text_medium_bold"><?php echo mi18n("EMBED_YOUTUBE_VIDEO") ?></td>
        <td class="text_medium">
            <!-- video url -->
            <?php echo mi18n("VIDEO_URL") ?>:<br>
            <input type="text" class="text_medium" style="width:300px;" name="<?php echo $modContext->cmsVarVideoUrl ?>" value="<?php echo $modContext->cmsValueVideoUrl ?>"><br>
            <small>
            - https://www.youtube.com/watch?v=WxnN05vOuSM
              [<a href="https://www.youtube.com/watch?v=WxnN05vOuSM" class="blue" target="_blank"> ? </a>]<br>
            - https://youtu.be/WxnN05vOuSM
              [<a href="https://youtu.be/WxnN05vOuSM" class="blue" target="_blank"> ? </a>]
            </small><br>
            <br>

            <!-- video size -->
            <?php echo mi18n("VIDEO_DIMENSIONS") ?>:
            <div style="width:350px;">
                <select name="<?php echo $modContext->cmsVarVideoSize ?>" data-type="video_size" style="float:left;width:150px;margin-right:10px;">
                <?php
                foreach ($modContext->videoSizes as $k => $v) {
                    $sel = ($modContext->cmsValueVideoSize == $k) ? ' selected="selected"' : '';
                    echo '<option value="' . $k . '"' . $sel . '>' . $v . '</option>' . "\n";
                }
                ?>
                </select>
                <div data-type="video_custom_size" style="float:left;width:175px;">
                    <input type="text" class="text_medium" style="width:50px;" name="<?php echo $modContext->cmsVarCustomWidth ?>" value="<?php echo $modContext->cmsValueCustomWidth ?>">
                    x
                    <input type="text" class="text_medium" style="width:50px;" name="<?php echo $modContext->cmsVarCustomHeight ?>" value="<?php echo $modContext->cmsValueCustomHeight ?>">
                    <small><?php echo mi18n("(W_x_H)") ?></small>
                </div>
                <div style="clear:both;"></div>
            </div>
            <br>

            <div data-type="options" style="margin-bottom:1.0em;">
                <!-- show suggested videos -->
                <label>
                    <input type="radio" name="<?php echo $modContext->cmsVarSuggestedVideos ?>" value="1"<?php echo $modContext->cmsChkSuggestedVideos ?>>
                    <?php echo mi18n("SHOW_SUGGESTED_VIDEOS_WHEN_THE_VIDEO_FINISHES") ?>
                </label><br>

                <!-- show player controls -->
                <label>
                    <input type="radio" name="<?php echo $modContext->cmsVarPlayerControls ?>" value="1"<?php echo $modContext->cmsChkPlayerControls ?>>
                    <?php echo mi18n("SHOW_PLAYER_CONTROLS") ?>
                </label><br>

                <!-- enable privacy-enhanced mode -->
                <label>
                    <input type="radio" name="<?php echo $modContext->cmsVarPrivacyMode ?>" value="1"<?php echo $modContext->cmsChkPrivacyMode ?>>
                    <?php echo mi18n("ENABLE_PRIVACY_ENHANCED_MODE") ?>
                </label><br>

                <!-- use https -->
                <label>
                    <input type="radio" name="<?php echo $modContext->cmsVarUseHttps ?>" value="1"<?php echo $modContext->cmsChkUseHttps ?>>
                    <?php echo mi18n("USE_HTTP") ?>
                </label>
            </div>

            <div>
                <a href="https://support.google.com/youtube/answer/171780" class="blue" target="_blank"><?php echo mi18n("LBL_YOUTUBE_HELP") ?></a>
            </div>

        </td>
    </tr>
</table>
<script type="text/javascript">
(function($) {
    $(document).ready(function() {
        var $moduleBox, $custom;

        $moduleBox = $('#mpEmbedYouTube_<?php echo $modContext->id ?>');
        $custom = $moduleBox.find('[data-type="video_custom_size"]');

        function _toggleCustom(selectedValue) {
            if ('custom' == selectedValue) {
                $custom.show();
            } else {
                $custom.hide();
            }
        }

        function _toggleRadio($input) {
            if ($input.length) {
                if ($input.attr('checked')) {
                    $input.removeAttr('checked');
                } else {
                    $input.attr('checked', true);
                }
            }
        }

        // on video size select change
        $moduleBox.find('[data-type="video_size"]').change(function(e) {
            var value = $(this).find("option:selected").attr("value");
            _toggleCustom(value);
        });

        // toggle radios on click
        $moduleBox.find('[data-type="options"] label').click(function(e) {
            var $input = $(this).find('input[type="radio"]');
            _toggleRadio($input);
            return false;
        });

        // initial custom sizes state
        _toggleCustom('<?php echo $modContext->cmsValueVideoSize ?>');
    });
})(jQuery);
</script>

<?php

unset($modContext);