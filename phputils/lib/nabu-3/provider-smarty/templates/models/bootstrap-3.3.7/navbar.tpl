{if isset($collapsable) && is_string($collapsable) && strlen($collapsable)>0}
    {assign var=nav_collapsable value=$collapsable scope=parent}
{else}
    {assign var=nav_collapsable value=false scope=parent}
{/if}
<nav class="navbar{if isset($inverse) && $inverse} navbar-inverse{/if}{if isset($fixed)} navbar-fixed-{$fixed}{/if}">
    <div class="{if isset($container) && $container==='fluid'}container-fluid{else}container{/if}">
        {if isset($header) && $header}
            <div class="navbar-header">
                {if $nav_collapsable}
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#{$collapsable}" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                {/if}
                {if isset($brand) && is_array($brand)}
                    <a class="navbar-brand" href="{if isset($brand.final_url)}{$brand.final_url}{else}#{/if}"{if isset($brand_name)} title="{$brand_name}"{elseif isset($brand.title)} title="{$brand.title|escape:html}"{elseif isset($brand.name)} title="{$brand.name|escape:html}"{/if}>{if isset($brand_name)}{$brand_name}{elseif isset($brand.content)}{$brand.content}{elseif isset($brand.name)}{$brand.name}{/if}</a>
                {/if}
            </div>
        {/if}
        {if $nav_collapsable}
            <div id="{$collapsable}" class="collapse navbar-collapse">
        {/if}
