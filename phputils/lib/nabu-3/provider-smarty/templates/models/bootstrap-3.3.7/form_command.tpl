{if isset($class)}
    {assign var=final_class value="btn {$class}"}
{else}
    {assign var=final_class value="btn"}
{/if}
<button type="{if isset($type)}{$type}{else}button{/if}" class="{$final_class}"{if isset($id)} id="{$id}"{/if}{if isset($command)} name="{$command}"{/if}>{if isset($anchor_img)}<img src="{$anchor_img}"{if isset($anchor_img_title)} title="{$anchor_img_title|escape:html}"{/if}{if isset($anchor_img_alt)} alt="{$anchor_img_alt|escape:html}"{/if}>{/if}{if isset($anchor_icon)}<i class="{$anchor_icon}"></i>{/if}{if isset($anchor_text)}{$anchor_text}{/if}</button>
