<fieldset{if isset($class) && is_string($class) && strlen($class)>0} class="{$class}"{/if}{if isset($id) && is_string($id) && strlen($id)>0} id="{$id}"{/if}>
    {if isset($title) && is_string($title) && strlen($title)>0}<legend>{$title}</legend>{/if}
