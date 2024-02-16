<?php

/**
 * Module mp_embed_youtube output.
 *
 * Module to embed YouTube videos in CONTENIDO
 * See https://youtube.com/
 *
 * @package     Module
 * @subpackage  mp_embed_youtube
 * @author      Murat Purç
 * @copyright   Murat Purç it-solutions
 * @license     GPL-2.0-or-later
 * @link        https://www.purc.de
 */

(function() {

    // ########################################################################
    // ########## Initialization/Settings

    if (!class_exists(\CONTENIDO\Plugin\MpDevTools\Module\AbstractBase::class)) {
        new cException('This module requires the plugin "Mp Dev Tools", please download, install and activate it!');
    }

    // Includes
    if (!class_exists(MpEmbedYoutubeModule::class)) {
        cInclude('module', 'includes/class.mp.embed.youtube.module.php');
    }

    $module = new MpEmbedYoutubeModule([
        // (bool) Flag to display error message if an error occurs
        'debug' => false,

        // (string) Default dimensions
        'defaultDimensions' => '426x240',

        // (array) List of filer types for the preview image
        'previewImageFileTypes' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'tiff'],

        'i18n' => [
            'CUSTOM' =>  mi18n("CUSTOM")
        ],
    ]);

    $videoSizes = $module->getVideoSizes();

    $src = '';
    $width = 0;
    $height = 0;
    $error = '';
    $errorMsgMissingInvalid = mi18n("MSG_INVALID_YOUTUBE_VIDEO_URL");
    $urlParams = [];

    // ########################################################################
    // ########## CMS variables & logic

    $cmsVideoUrl = $module->getCmsToken(1);
    $cmsVideoSize = $module->getCmsToken(2);
    $cmsCustomWidth = $module->getCmsToken(3);
    $cmsCustomHeight = $module->getCmsToken(4);
    $cmsSuggestedVideos = $module->getCmsToken(5);
    $cmsUseHttp = $module->getCmsToken(6);
    $cmsPrivacyMode = $module->getCmsToken(7);
    $cmsPlayerControls = $module->getCmsToken(8);
    $cmsProtection = $module->getCmsToken(9);
    $cmsPreviewImage = $module->getCmsToken(10);

    $videoSize = $videoSizes[$cmsVideoSize->value] ?? $module->defaultDimensions;
    $customWidth = $cmsVideoSize->value === 'custom' ? cSecurity::toInteger($cmsCustomWidth->value) : 0;
    $customHeight = $cmsVideoSize->value === 'custom' ? cSecurity::toInteger($cmsCustomHeight->value) : 0;
    $videoUrl = trim($cmsVideoUrl->value);
    $protection = $cmsProtection->value;

    $previewImage = $module->getPreviewImageUrl(
        $cmsPreviewImage->value, $videoUrl
    );

    // Extract video id
    $video = $module->getVideoIdFromUrl($videoUrl);
    if (is_numeric($video)) {
        $error = $errorMsgMissingInvalid . " [$video]";
        $video = '';
    }

    if (empty($error)) {
        // Video dimensions
        if ($videoSize !== 'custom') {
            $dimensions = $module->dimensionsToArray($videoSize);
        } else {
            $dimensions = [$customWidth, $customHeight];
        }
        if (count($dimensions) !== 2) {
            $dimensions = $module->dimensionsToArray($module->defaultDimensions);
        } elseif ($dimensions[0] <= 0 || $dimensions[1] <= 0) {
            $dimensions = $module->dimensionsToArray($module->defaultDimensions);
        }

        $width = $dimensions[0];
        $height = $dimensions[1];

        // Base URL
        if ($cmsPrivacyMode->value === '1') {
            $src = $module::YOUTUBE_NO_COOKIE_URL;
        } else {
            $src = $module::YOUTUBE_URL;
        }

        // HTTP
        if ($cmsUseHttp->value === '1') {
            $src = str_replace('https://', 'http://', $src);
        }

        // Video id
        $src .= $video;

        // Suggested videos
        if ($cmsSuggestedVideos->value !== '1') {
            $urlParams['rel'] = '0';
        }

        // Video controls
        if ($cmsPlayerControls->value !== '1') {
            $urlParams['controls'] = '0';
        }

        if (count($urlParams) > 0) {
            $src .= '?' . http_build_query($urlParams);
        }
    }

    // ########################################################################
    // ########## Output

    $tpl = cSmartyFrontend::getInstance();
    $tpl->assign('label', mi18n("LBL_YOUTUBE_VIDEO"));
    $tpl->assign('errorLabel', mi18n("LBL_YOUTUBE_ERROR"));
    $tpl->assign('isBackendEditMode', cRegistry::isBackendEditMode());
    $tpl->assign('error', $error);
    $tpl->assign('debug', $module->debug);
    $tpl->assign('width', $width);
    $tpl->assign('height', $height);
    $tpl->assign('src', $src);
    $tpl->assign('protection', $protection);
    $tpl->assign('previewImage', $previewImage);
    $tpl->display('get.tpl');

})();

?>