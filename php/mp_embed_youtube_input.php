?><?php

/**
 * Module mp_embed_youtube input.
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
        // (string) Default dimensions
        'defaultDimensions' => '426x240',

        // (array) List of filer types for the preview image
        'previewImageFileTypes' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'tiff'],

        'i18n' => [
            'CUSTOM' =>  mi18n("CUSTOM")
        ],
    ]);

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

    $customWidth = $cmsVideoSize->value === 'custom' ? $cmsCustomWidth->value : '';
    $customHeight = $cmsVideoSize->value === 'custom' ? $cmsCustomHeight->value : '';

    // ########################################################################
    // ########## Output

    $table = $module->getGuiTable(['class' => 'mp_embed_youtube']);

    // Header row
    $table->addContrastRow(
        [mi18n("EMBED_YOUTUBE_VIDEO")], [], [['colspan' => 2]]
    );

    // Video URL row
    $input = new cHTMLTextbox($cmsVideoUrl->var, $cmsVideoUrl->value);
    $input->setClass('mp_embed_youtube_row');
    $infoBox = new cGuiBackendHelpbox('
<ul>
    <li>https://www.youtube.com/watch?v=WxnN05vOuSM [<a href="https://www.youtube.com/watch?v=WxnN05vOuSM" class="blue" target="_blank"> &#128279; </a>]</li>
    <li>https://youtu.be/WxnN05vOuSM [<a href="https://youtu.be/WxnN05vOuSM" class="blue" target="_blank"> &#128279; </a>]</li>
</ul>
    ');
    $table->addRow(
        [mi18n("VIDEO_URL"), $input->render() . $infoBox->render()]
    );

    // Video size row
    $select = new cHTMLSelectElement($cmsVideoSize->var);
    $select->setClass('mp_embed_youtube_video_size')
        ->setAttribute('data-type', 'video_size')
        ->autoFill($module->getVideoSizes())
        ->setDefault($cmsVideoSize->value);

    $customSize = new cHTMLDiv();
    $customSize->setClass('mp_embed_youtube_video_custom_size')
        ->setAttribute('data-type', 'video_custom_size');
    $inputWidth = new cHTMLTextbox($cmsCustomWidth->var, $customWidth);
    $inputWidth->setClass('mp_embed_youtube_video_custom_size_field');
    $inputHeight = new cHTMLTextbox($cmsCustomHeight->var, $customHeight);
    $inputHeight->setClass('mp_embed_youtube_video_custom_size_field');
    $customSize->setContent([$inputWidth, '<span>x</span>', $inputHeight, mi18n("(W_x_H)")]);

    $wrapper = new cHTMLDiv();
    $wrapper->setClass('mp_embed_youtube_row')
        ->setContent([$select, $customSize, '<div style="clear:both;"></div>']);

    $table->addRow(
        [mi18n("VIDEO_DIMENSIONS"), $wrapper->render()]
    );

    // Show suggested videos row
    $checkbox = new cHTMLCheckbox($cmsSuggestedVideos->var, '1');
    $checkbox->setLabelText(mi18n("SHOW_SUGGESTED_VIDEOS_WHEN_THE_VIDEO_FINISHES"))
        ->setChecked($cmsSuggestedVideos->value);
    $table->addRow([mi18n("LBL_OPTIONS"), $checkbox]);

    // Show player controls row
    $checkbox = new cHTMLCheckbox($cmsPlayerControls->var, '1');
    $checkbox->setLabelText(mi18n("SHOW_PLAYER_CONTROLS"))
        ->setChecked($cmsPlayerControls->value);
    $table->addRow(['&nbsp;', $checkbox]);

    // Use https row
    $checkbox = new cHTMLCheckbox($cmsUseHttp->var, '1');
    $checkbox->setLabelText(mi18n("USE_HTTP"))
        ->setChecked($cmsUseHttp->value);
    $table->addRow(['&nbsp;', $checkbox]);

    // Enable privacy-enhanced mode row
    $checkbox = new cHTMLCheckbox($cmsPrivacyMode->var, '1');
    $checkbox->setLabelText(mi18n("ENABLE_PRIVACY_ENHANCED_MODE"))
        ->setChecked($cmsPrivacyMode->value);
    $table->addRow(['&nbsp;', $checkbox]);

    // Data protection row
    $infoBox = new cGuiBackendHelpbox(mi18n("MSG_DATA_PROTECTION"));
    $checkbox = new cHTMLCheckbox($cmsProtection->var, '1');
    $checkbox->setLabelText(mi18n("LBL_DATA_PROTECTION"))
        ->setAttribute('data-type', 'data_protection')
        ->setChecked($cmsProtection->value);
    $table->addRow(['&nbsp;', $checkbox->render() . $infoBox->render()]);

    // Preview image row
    $infoBox = new cGuiBackendHelpbox(mi18n("MSG_PREVIEW_IMAGE"));
    $uploadSelect = $module->getGuiUploadSelect(
        $cmsPreviewImage->var, $module->clientId, $module->languageId, ['data-type' => 'preview_image']
    );
    $uploadSelect = $uploadSelect->render(
        '',
        $cmsPreviewImage->value,
        ['fileTypes' => $module->previewImageFileTypes, 'directoryIsSelectable' => false]
    );
    $table->addRow([
        '&nbsp;',
        mi18n("LBL_PREVIEW_IMAGE") . ':<br>' . $uploadSelect . $infoBox->render()
    ], ['data-type' => 'preview_image_row']);

    // YouTube embed help row
    $link = '<a href="https://support.google.com/youtube/answer/171780" class="blue" target="_blank">' . mi18n("LBL_YOUTUBE_HELP") . '</a>';
    $table->addRow([$link], [], [['colspan' => 2]]);

    // Output of the module input
    echo $module->renderModuleInputStyles()
        . $table->render()
        . $module->renderModuleInputJavaScript();

})();
