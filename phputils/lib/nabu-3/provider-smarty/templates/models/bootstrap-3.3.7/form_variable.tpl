<!-- {$from|print_r:true} -->
{if isset($from) && ((is_array($from) && isset($field) && is_string($field) && array_key_exists($field, $from)) || is_string($from))}
<input type="hidden"{if isset($name)} name="{$name}"{/if} value="{if is_array($from)}{$from[$field]|escape:html}{else}{$from|escape:html}{/if}">
{/if}
