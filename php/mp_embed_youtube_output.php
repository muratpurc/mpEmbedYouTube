<?php
/**
 * Module-Output: mpEmbedYouTube
 *
 * Module to embed YouTube videos in CONTENIDO
 * See https://youtube.com/
 *
 * @package     CONTENIDO_Modules
 * @subpackage  mpEmbedYouTube
 * @author      Murat Purç <murat@purc.de>
 * @copyright   Copyright (c) 2012-2019 Murat Purç (http://www.purc.de)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html - GNU General Public License, version 2
 */

// ##############################################
// INITIALIZATION

$modContext = new stdClass();

$modContext->youTubeUrl = 'https://www.youtube.com/embed/';
$modContext->youTubeNoCookieUrl = 'https://www.youtube-nocookie.com/embed/';

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
$modContext->urlParams = array();

// ##############################################
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

if ($modContext->cmsValueVideoSize !== 'custom') {
    $modContext->cmsValueCustomWidth = 0;
    $modContext->cmsValueCustomHeight = 0;
}

$modContext->cmsValueSuggestedVideos = "CMS_VALUE[5]";
$modContext->cmsValueSuggestedVideos = ('1' == $modContext->cmsValueSuggestedVideos) ? '1' : '0';

$modContext->cmsValueUseHttp = "CMS_VALUE[6]";
$modContext->cmsValueUseHttp = ('1' == $modContext->cmsValueUseHttp) ? '1' : '0';

$modContext->cmsValuePrivacyMode = "CMS_VALUE[7]";
$modContext->cmsValuePrivacyMode = ('1' == $modContext->cmsValuePrivacyMode) ? '1' : '0';

$modContext->cmsValuePlayerControls = "CMS_VALUE[8]";
$modContext->cmsValuePlayerControls = ('1' == $modContext->cmsValuePlayerControls) ? '1' : '0';

// Extract video id
if (empty($modContext->cmsValueVideoUrl)) {
    $modContext->error = $modContext->errorMsgMissingInvalid . ' [1]';
} else {
    $urlComp = @parse_url(htmlspecialchars($modContext->cmsValueVideoUrl));
    if (!is_array($urlComp) || empty($urlComp['host'])) {
        $modContext->error = $modContext->errorMsgMissingInvalid . ' [2]';
    } else {
        // Check for youtu.be url
        if ('youtu.be' === $urlComp['host']) {
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
    if ($modContext->cmsValueVideoSize !== 'custom') {
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
    if ($modContext->cmsValuePrivacyMode === '1') {
        $modContext->src = $modContext->youTubeNoCookieUrl;
    } else {
        $modContext->src = $modContext->youTubeUrl;
    }

    // HTTP
    if ($modContext->cmsValueUseHttp === '1') {
        $modContext->src = str_replace('https://', 'http://', $modContext->src);
    }

    // Video id
    $modContext->src .= $modContext->video;

    // Suggested videos
    if ($modContext->cmsValueSuggestedVideos !== '1') {
        $modContext->urlParams['rel'] = '0';
    }

    // Video controls
    if ($modContext->cmsValuePlayerControls !== '1') {
        $modContext->urlParams['controls'] = '0';
    }

    if (count($modContext->urlParams) > 0) {
        $modContext->src .= '?' . http_build_query($modContext->urlParams);
    }
}

// ##############################################
// OUTPUT

$tpl = cSmartyFrontend::getInstance();
$tpl->assign('label', mi18n("LBL_YOUTUBE_VIDEO"));
$tpl->assign('errorLabel', mi18n("LBL_YOUTUBE_ERROR"));
$tpl->assign('isBackendEditMode', cRegistry::isBackendEditMode());
$tpl->assign('error', $modContext->error);
$tpl->assign('debug', $modContext->debug);
$tpl->assign('width', $modContext->width);
$tpl->assign('height', $modContext->height);
$tpl->assign('src', $modContext->src);
$tpl->display('get.tpl');

unset($modContext);

?>