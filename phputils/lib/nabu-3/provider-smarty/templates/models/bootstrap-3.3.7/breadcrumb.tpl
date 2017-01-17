{assign var=root_sitemap value=false}
{if isset($sitemap)}
    {if is_string($sitemap)}
        {nabu_exists sitemap=$sitemap}
            {nabu_assign var=root_sitemap sitemap=$sitemap}
        {/nabu_exists}
    {elseif is_array($sitemap)}
        {assign var=root_sitemap value=$sitemap}
    {/if}
{/if}
{if is_array($root_sitemap) && array_key_exists('breadcrumb', $root_sitemap) && $root_sitemap.breadcrumb}
    <ol class="breadcrumb{if isset($class) && is_string($class)} {$class}{/if}">{strip}
        {assign var=parent_sitemap value=null}
        {while $root_sitemap!==null && $root_sitemap.breadcrumb}
            <li{if $root_sitemap.current} class="active"{/if}>
                {if isset($parts) && array_key_exists($root_sitemap.key, $parts) && is_array($parts[$root_sitemap.key]) && array_key_exists('lookup', $parts[$root_sitemap.key])}
                    {if count($parts[$root_sitemap.key].lookup) > 1}
                        <div class="dropdown">
                            {strip}
                                <button class="btn btn-breadcrumb dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    {foreach from=$parts[$root_sitemap.key].lookup item=lookup}
                                        {if $lookup.breadcrumb}{$root_sitemap.translation.content|vsprintf:$lookup.title}<span class="caret"></span>{break}{/if}
                                    {/foreach}
                                </button>
                            {/strip}
                            <ul class="dropdown-menu">
                                {foreach from=$parts[$root_sitemap.key].lookup item=lookup}
                                    {strip}
                                        <li{if $lookup.breadcrumb} class="active"{/if}>
                                            <a href="{$root_sitemap.translation.final_url|vsprintf:$lookup.slug}">
                                                {$root_sitemap.translation.content|vsprintf:$lookup.title}
                                            </a>
                                        </li>
                                    {/strip}
                                {/foreach}
                            </ul>
                        </div>
                    {else}
                        {strip}
                            {foreach from=$parts[$root_sitemap.key].lookup item=lookup}
                                <a href="{$root_sitemap.translation.final_url|vsprintf:$lookup.slug}">
                                    {$root_sitemap.translation.content|vsprintf:$lookup.title}
                                </a>
                            {/foreach}
                        {/strip}
                    {/if}
                {elseif $parent_sitemap===null || !isset($level_dropdown) || !$level_dropdown || count($parent_sitemap)<2}
                    {strip}
                        {if isset($parts) && array_key_exists($root_sitemap.key, $parts)}
                            <a href="{$root_sitemap.translation.final_url|sprintf:$parts[$root_sitemap.key]['slug']}">
                                {$root_sitemap.translation.content|sprintf:$parts[$root_sitemap.key]['title']}
                            </a>
                        {else}
                            <a href="{$root_sitemap.translation.final_url}">
                                {$root_sitemap.translation.content}
                            </a>
                        {/if}
                    {/strip}
                {else}
                    <div class="dropdown">
                        <button class="btn btn-breadcrumb dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">{$root_sitemap.translation.content}<span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            {foreach from=$parent_sitemap item=parent}
                                {strip}
                                    <li{if $parent.breadcrumb} class="active"{/if}>
                                            {if isset($parts) && array_key_exists($parent.key, $parts)}
                                                <a href="{$parent.translation.final_url|sprintf:$parts[$parent.key]['slug']}">
                                                    {$parent.translation.content|sprintf:$parts[$parent.key]}
                                                </a>
                                            {else}
                                                <a href="{$parent.translation.final_url}">
                                                    {$parent.translation.content}
                                                </a>
                                            {/if}
                                    </li>
                                {/strip}
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </li>
            {assign var=next_sitemap value=null}
            {foreach from=$root_sitemap.childs item=child_map}
                {if $child_map.breadcrumb}
                    {assign var=next_sitemap value=$child_map}
                    {assign var=parent_sitemap value=$root_sitemap.childs}
                    {break}
                {/if}
            {/foreach}
            {assign var=root_sitemap value=$next_sitemap}
        {/while}
    {/strip}</ol>
{/if}
