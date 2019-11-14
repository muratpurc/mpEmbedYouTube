<!-- mp_embed_youtube -->

{if $isBackendEditMode}

    <label class="content_type_label">{$label|escape}</label>

{/if}

<div class="mpEmbedYouTube">

    {if 0 lt $error|strlen}
        {if $debug}

            <p><strong>{$errorLabel|escape}</strong>: {$error|escape}.</p>

        {/if}
    {else}

        <iframe width="{$width}" height="{$height}" src="{$src}" frameborder="0" allowfullscreen></iframe>

    {/if}

</div>

<!-- /mp_embed_youtube -->
