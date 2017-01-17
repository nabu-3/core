{nabu_model model="bootstrap-3.3.7"}
<div{if isset($id) && is_string($id)} id="{$id}"{/if} class="nabu-tree{if isset($class)} {$class}{/if}" data-toggle="nabu-tree">
    {include file="tree-child.tpl" data=$data}
</div>
