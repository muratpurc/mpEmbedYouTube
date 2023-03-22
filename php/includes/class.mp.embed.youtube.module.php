<?php
/**
 * CONTENIDO module class for mp_embed_youtube.
 *
 * @package     Module
 * @subpackage  MpEmbedYoutubeModule
 * @author      Murat Purç
 * @copyright   Murat Purç it-solutions
 * @license     GPL-2.0-or-later
 * @link        https://www.purc.de
 */

use CONTENIDO\Plugin\MpDevTools\Module\AbstractBase;

/**
 * CONTENIDO module class for mp_embed_youtube
 *
 * @property string defaultDimensions
 * @property array i18n
 * @property array previewImageFileTypes
 */
class MpEmbedYoutubeModule extends AbstractBase
{

    const YOUTUBE_URL = 'https://www.youtube.com/embed/';

    const YOUTUBE_NO_COOKIE_URL = 'https://www.youtube-nocookie.com/embed/';

    const PREVIEW_IMAGE_NAMES = ['maxresdefault.jpg', 'mqdefault.jpg', '0.jpg'];

    const THUMBNAIL_URL_TPL = 'https://img.youtube.com/vi/%s/%s';

    /**
     * Module properties structure.
     *
     * See {@see AbstractBase::$baseProperties} for base properties. Only
     * properties being defined here and in the base class ($baseProperties)
     * will be taken over to the $properties structure.
     *
     * @var  array
     */
    protected $properties = [
        'defaultDimensions' => '',
        'previewImageFileTypes' => [],
        'i18n' => [],
    ];

    /**
     * @var array
     */
    private $videoSizes;

    /**
     * Constructor, sets some properties.
     *
     * @param array $properties Properties array
     * @throws cException
     */
    public function __construct(array $properties = [])
    {
        parent::__construct('mp_embed_youtube', $properties);

        $this->videoSizes = [
            '426x240' => '426 x 240',
            '640x360' => '640 x 360',
            '854x480' => '854 x 480',
            '1280x720' => '1280 x 720',
            '1920x1080' => '1920 x 1080',
            '2560x1440' => '2560 x 1440',
            '3840x2160' => '3840 x 2160',
            'custom' => $this->i18n['CUSTOM'],
        ];
    }

    /**
     * @return array
     */
    public function getVideoSizes(): array
    {
        return $this->videoSizes;
    }

    /**
     * Splits passed dimensions string (`<width>x<height>`) and returns the
     * values as integer type.
     *
     * @param string $dimensions
     * @return int[]
     */
    public function dimensionsToArray(string $dimensions): array
    {
        $dimensions = explode('x', $dimensions);
        return array_map(function ($item) {
            return cSecurity::toInteger($item);
        }, $dimensions);
    }

    /**
     * Extracts the video id from passed URL.
     *
     * @param string $videoUrl
     * @return string|int The video id (string) or one of the error codes (int) 1-6.
     */
    public function getVideoIdFromUrl(string $videoUrl)
    {
        $videoId = '';

        if (empty($videoUrl)) {
            // Empty URL
            return 1;
        } else {
            $urlComp = @parse_url(htmlspecialchars($videoUrl));
            if (!is_array($urlComp) || empty($urlComp['host'])) {
                // Invalid URL/host
                return 2;
            } else {
                // Check for youtu.be url
                if ('youtu.be' === $urlComp['host']) {
                    if (empty($urlComp['path'])) {
                        // Empty path
                        return 3;
                    } else {
                        $videoId = str_replace('/', '', $urlComp['path']);
                    }
                } else {
                    // Check for regular YouTube url with 'v' parameter
                    if (empty($urlComp['query'])) {
                        // Invalid/missing v parameter
                        return 4;
                    } else {
                        $params = null;
                        @parse_str($urlComp['query'], $params);
                        if (!is_array($params) || empty($params['v'])) {
                            // Invalid/missing v parameter
                            return 5;
                        } else {
                            $videoId = $params['v'];
                        }
                    }
                }
            }
        }

        if (empty($videoId)) {
            return 6;
        }

        return $videoId;
    }

    /**
     * Returns the URL to the preview image, either from upload or cache (downloaded from YouTube).
     *
     * @param string $previewImage Selected preview image from upload directory.
     * @param string $videoUrl The video URL.
     * @return string URL to the preview image.
     * @throws cDbException
     * @throws cException
     */
    public function getPreviewImageUrl(string $previewImage, string $videoUrl): string
    {
        if (!empty($previewImage)) {
            return $this->getPreviewImageUrlFromUpload($previewImage);
        } else {
            return $this->getPreviewImageUrlFromYouTube($videoUrl);
        }
    }

    /**
     * Returns the URL to the preview image from upload.
     *
     * @param string $previewImage
     * @return string
     * @throws cDbException
     * @throws cException
     */
    protected function getPreviewImageUrlFromUpload(string $previewImage): string
    {
        $previewImage = $this->getGuiUploadSelectValues($previewImage);
        if (count($previewImage) > 0) {
            $idupl = $previewImage[0]['idupl'];
            $cApiUpl = new cApiUpload($idupl);
            if ($cApiUpl->isLoaded()) {
                $path = trim($cApiUpl->get('dirname') . $cApiUpl->get('filename'), '/');
                return $this->getClientInfo()->getUploadUrl($path);
            }
        }

        return '';
    }

    /**
     * Returns the URL to the preview image from cache (downloaded from YouTube).
     *
     * @param string $videoUrl
     * @return string
     * @throws cException
     * @throws cInvalidArgumentException
     */
    protected function getPreviewImageUrlFromYouTube(string $videoUrl): string
    {
        $videoId = $this->getVideoIdFromUrl($videoUrl);
        if (is_numeric($videoId) && empty($videoId)) {
            return '';
        }

        $clientInfo = $this->getClientInfo();
        $fileName = 'module_' . $this->getModuleName() . '_' . $videoId . '.jpg';
        $filePath = $clientInfo->getCachePath($fileName);

        if (cFileHandler::isFile($filePath)) {
            return str_replace($clientInfo->getPath(), $clientInfo->getUrl(), $filePath);
        }

        foreach (self::PREVIEW_IMAGE_NAMES as $imageName) {
            $request = new cHttpRequestCurl(
                sprintf(self::THUMBNAIL_URL_TPL, $videoId, $imageName)
            );
            $request->setOpt(CURLOPT_CONNECTTIMEOUT, 5);
            $request->setOpt(CURLOPT_TIMEOUT, 5);
            $previewImage = $request->getRequest();
            if ($previewImage !== false) {
                if (cFileHandler::write($filePath, $previewImage)) {
                    return str_replace($clientInfo->getPath(), $clientInfo->getUrl(), $filePath);
                }
                break;
            }
        }

        return '';
    }

    /**
     * Renders the JavaScript code for the module input.
     *
     * @return string
     */
    public function renderModuleInputJavaScript(): string
    {
        static $javaScriptRendered;

        if (isset($javaScriptRendered)) {
            return '';
        }
        $javaScriptRendered = true;

        return '
    <script>
    (function($) {
        $(function() {
            var $moduleBox = $(".mp_embed_youtube"),
                $videoSize = $moduleBox.find("[data-type=\'video_size\']"),
                $custom = $moduleBox.find("[data-type=\'video_custom_size\']"),
                $dataProtection = $moduleBox.find("[data-type=\'data_protection\']"),
                $previewImageRow = $moduleBox.find("[data-type=\'preview_image_row\']"),
                $previewImage = $moduleBox.find("[data-type=\'preview_image\']");

            function _toggleCustom(selectedValue) {
                if (selectedValue === "custom") {
                    $custom.show();
                } else {
                    $custom.hide();
                }
            }

            function _togglePreviewImageRow(show) {
                if (show) {
                    $previewImageRow.show();
                } else {
                    $previewImageRow.hide();
                }
            }

            // On video size select change
            $videoSize.change(function() {
                _toggleCustom($videoSize.val());
            });

            // On data protection change
            $dataProtection.change(function() {
                _togglePreviewImageRow($dataProtection.prop("checked"));
                if (!$dataProtection.prop("checked")) {
                    $previewImage.find("option:selected").prop("selected", false);
                }
            });

            // Initial states
            _toggleCustom($videoSize.val());
            _togglePreviewImageRow($dataProtection.prop("checked"));
        });
    })(jQuery);
    </script>
';
    }

    /**
     * Renders the Stylesheets code for the module input.
     *
     * @return string
     */
    public function renderModuleInputStyles(): string
    {
        static $stylesRendered;

        if (isset($stylesRendered)) {
            return '';
        }
        $stylesRendered = true;

        return '
<style>
    .mp_embed_youtube {width: 100%;}
    .mp_embed_youtube .checkbox_wrapper {display: inline-block;}
    .mp_embed_youtube_row {width: 400px;}
    .mp_embed_youtube_video_size {float: left; width: 150px; margin-right: 10px;}
    .mp_embed_youtube_video_custom_size {float: left; width: 200px;}
    .mp_embed_youtube_video_custom_size span {margin-right: 10px;}
    .mp_embed_youtube_video_custom_size_field {width: 50px;}
</style>
        ';
    }

}
