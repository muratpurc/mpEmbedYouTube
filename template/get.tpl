<!-- mp_embed_youtube -->

{if $isBackendEditMode}

    <label class="content_type_label">{$label|escape}</label>

{/if}

<div class="mp_embed_youtube">

    {if 0 lt $error|strlen}
        {if $debug}

            <p><strong>{$errorLabel|escape}</strong>: {$error|escape}.</p>

        {/if}
    {else}

        <div data-action-widget="mp_embed_youtube" data-width="{$width}" data-height="{$height}" data-src="{$src}" data-protection="{$protection}" data-preview-image="{$previewImage}"></div>

    {/if}

</div>

<!-- /mp_embed_youtube -->
