/**
 * Javascript for module mp_embed_youtube output.
 *
 * @package     Module
 * @subpackage  mp_embed_youtube
 * @author      Murat Purç
 * @copyright   Murat Purç it-solutions
 * @license     GPL-2.0-or-later
 * @link        https://www.purc.de
 */

(function() {

    var MpEmbedYoutube = function(widget) {
        var context = this;

        this.IFRAME_TPL = '<iframe width="{width}" height="{height}" src="{src}" frameborder="0" allowfullscreen></iframe>';
        this.PREVIEW_IMAGE_CSS_TPL = 'background-image: url({url}); background-size: {width}px {height}px;';
        this.LINK_TPL = ''
            + '<a href="javascript:void(0)" class="mp_embed_youtube_link" style="width:{width}px;height:{height}px;{previewImageCss}">'
            + '    <span class="mp_embed_youtube_button"></span>'
            + '</a>';

        this.widget = widget;
        this.protection = this.widget.dataset.protection;
        this.src = this.widget.dataset.src;
        this.width = this.widget.dataset.width;
        this.height = this.widget.dataset.height;
        this.previewImage = this.widget.dataset.previewImage;

        if (this.protection) {
            renderLink();
        } else {
            renderIframe();
        }

        function onButtonClick() {
            context.widget.removeEventListener('click', onButtonClick);
            renderIframe();
        }

        function renderLink() {
            var link = context.LINK_TPL.replace('{width}', context.width)
                    .replace('{height}', context.height),
                previewImageCss = '';

            if (context.previewImage) {
                previewImageCss = context.PREVIEW_IMAGE_CSS_TPL.replace('{url}', context.previewImage)
                    .replace('{width}', context.width)
                    .replace('{height}', context.height);
            }

            link = link.replace('{previewImageCss}', previewImageCss);

            context.widget.innerHTML = link;
            context.widget.addEventListener('click', onButtonClick);
        }

        function renderIframe() {
            var iframe = context.IFRAME_TPL.replace('{width}', context.width)
                .replace('{height}', context.height)
                .replace('{src}', context.src);
            context.widget.innerHTML = iframe;
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        var widgets = document.querySelectorAll('[data-action-widget=mp_embed_youtube]');
        for (var i = 0; i < widgets.length; i++) {
            new MpEmbedYoutube(widgets[i]);
        }
    });

}());
