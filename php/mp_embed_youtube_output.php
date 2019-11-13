<?php
/**
 * Module-Output: mpEmbedYouTube
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

$modContext->youTubeUrl = 'http://www.youtube.com/embed/';
$modContext->youTubeNoCookieUrl = 'http://www.youtube-nocookie.com/embed/';

// (bool) Flag to display error message if an error occurs
$modContext->debug = true;


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

$modContext->defaultDimensions = '426x240';
$modContext->src = '';
$modContext->video = '';
$modContext->width = 0;
$modContext->height = 0;
$modContext->error = '';
$modContext->errorMsgMissingInvalid = mi18n("MSG_INVALID_YOUTUBE_VIDEO_URL");


// #################################################################################################
// CMS VARIABLES & LOGIC

$modContext->cmsValueVideoUrl = "CMS_VALUE[1]";

$modContext->cmsValueVideoSize = "CMS_VALUE[2]";
if (!isset($modContext->videoSizes[$modContext->cmsValueVideoSize])) {
    $modContext->cmsValueVideoSize = $modContext->defaultDimensions;
}

$modContext->cmsValueCustomWidth = "CMS_VALUE[3]";
$modContext->cmsValueCustomWidth = (int) $modContext->cmsValueCustomWidth;

$modContext->cmsValueCustomHeight = "CMS_VALUE[4]";
$modContext->cmsValueCustomHeight = (int) $modContext->cmsValueCustomHeight;

if ('custom' != $modContext->cmsValueVideoSize) {
    $modContext->cmsValueCustomWidth = 0;
    $modContext->cmsValueCustomHeight = 0;
}

$modContext->cmsValueSuggestedVideos = "CMS_VALUE[5]";
$modContext->cmsValueSuggestedVideos = ('1' == $modContext->cmsValueSuggestedVideos) ? '1' : '0';

$modContext->cmsValueUseHttps = "CMS_VALUE[6]";
$modContext->cmsValueUseHttps = ('1' == $modContext->cmsValueUseHttps) ? '1' : '0';

$modContext->cmsValuePrivacyMode = "CMS_VALUE[7]";
$modContext->cmsValuePrivacyMode = ('1' == $modContext->cmsValuePrivacyMode) ? '1' : '0';

// Extract video id
if (empty($modContext->cmsValueVideoUrl)) {
    $modContext->error = $modContext->errorMsgMissingInvalid . ' [1]';
} else {
    $urlComp = @parse_url(htmlspecialchars($modContext->cmsValueVideoUrl));
    if (!is_array($urlComp) || empty($urlComp['host'])) {
        $modContext->error = $modContext->errorMsgMissingInvalid . ' [2]';
    } else {
        // Check for youtu.be url
        if ('youtu.be' == $urlComp['host']) {
            if (empty($urlComp['path'])) {
                $modContext->error = $modContext->errorMsgMissingInvalid . ' [3]';
            } else {
                $modContext->video = str_replace('/', '', $urlComp['path']);
            }
        } else {
            // Check for regular youtube url with 'v' parameter
            if (empty($urlComp['query'])) {
                $modContext->error = $modContext->errorMsgMissingInvalid . ' [4]';
            } else {
                $params = null;
                @parse_str($urlComp['query'], $params);
                if (!is_array($params) || empty($params['v'])) {
                    $modContext->error = $modContext->errorMsgMissingInvalid . ' [5]';
                } else {
                     $modContext->video = $params['v'];
                }
            }
        }
    }
}

if (empty($modContext->video)) {
    $modContext->error = $modContext->errorMsgMissingInvalid . ' [6]';
}

if (empty($modContext->error)) {
    // Video dimensions
    if ('custom' != $modContext->cmsValueVideoSize) {
        $dimensions = explode('x', $modContext->cmsValueVideoSize);
    } else {
        $dimensions = array($modContext->cmsValueCustomWidth, $modContext->cmsValueCustomHeight);
    }
    if (!is_array($dimensions) || count($dimensions) !== 2) {
        $dimensions = explode('x', $modContext->defaultDimensions);
    } elseif ((int) $dimensions[0] <= 0 || (int) $dimensions[1] <= 0) {
        $dimensions = explode('x', $modContext->defaultDimensions);
    }

    $modContext->width = (int) $dimensions[0];
    $modContext->height = (int) $dimensions[1];

    // Base URL
    if ('1' == $modContext->cmsValuePrivacyMode) {
        $modContext->src = $modContext->youTubeNoCookieUrl;
    } else {
        $modContext->src = $modContext->youTubeUrl;
    }

    // HTTPS
    if ('1' == $modContext->cmsValueUseHttps) {
        $modContext->src = str_replace('http://', 'https://', $modContext->src);
    }

    // Video id
    $modContext->src .= $modContext->video;

    // Suggested videos
    if ('1' !== $modContext->cmsValueSuggestedVideos) {
        $modContext->src .= '?rel=0';
    }
}


// #################################################################################################
// OUTPUT

?>

<div class="mpEmbedYouTube">
    <?php if ($modContext->error) : ?>
        <?php if (true == $modContext->debug) : ?>

            <p><strong>mpEmbedYouTube error</strong>: <?php echo $modContext->error ?>.</p>

        <?php endif; ?>
    <?php else : ?>

        <iframe width="<?php echo $modContext->width ?>" height="<?php echo $modContext->height ?>" src="<?php echo $modContext->src ?>" frameborder="0" allowfullscreen></iframe>

    <?php endif; ?>

</div>

<?php

unset($modContext);

?>