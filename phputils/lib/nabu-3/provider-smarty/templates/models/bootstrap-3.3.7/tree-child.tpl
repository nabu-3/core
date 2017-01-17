{nabu_model model="bootstrap-3.3.7"}
{if is_array($data) && count($data) > 0}
    <ul class="nabu-tree-level"{if isset($draggable) && $draggable===true} data-toggle="drop-container" data-drop-id="tree-child" draggable="true"{/if}>
        {foreach from=$data item=li}
            {if isset($field_childs) && array_key_exists($field_childs, $li) && is_array($li[$field_childs]) && count($li[$field_childs]) > 0}
                {assign var=childs value=$li[$field_childs]}
            {else}
                {assign var=childs value=false}
            {/if}
            {strip}
                <li{if isset($field_id) && array_key_exists($field_id, $li)} data-id="{$li[$field_id]}"{/if}{if $childs} class="expanded"{/if}{if isset($draggable) && $draggable===true} data-toggle="drag-item" data-drop-ids="tree-child" draggable="true"{/if}>
                    <div class="tree-item">
                        <div class="btn-toolbar">
                            {if $childs}<button class="btn btn-flat btn-expand"><i class="fa fa-minus-square-o"></i><i class="fa fa-plus-square-o"></i></button>{/if}
                            <button class="btn btn-flat btn-edit"><i class="fa fa-pencil"></i></button>
                        </div>
                        {assign var=final_content value='&lt;Nonamed&gt;'}
                        {if isset($template) && strlen($template)>0}
                            {capture name=li assign=final_content}{include file=$template node=$li}{/capture}
                        {elseif isset($field_name) && array_key_exists($field_name, $li)}
                            {assign var=final_content value=$data[$field_name]}
                        {elseif array_key_exists('translation', $li) && is_array($li.translation) && array_key_exists($field_name, $li.translation)}
                            {assign var=final_content value=$li.translation[$field_name]}
                        {/if}
                        {$final_content}
                    </div>
                    {if $childs}
                        {include file="tree-child.tpl" data=$childs}
                    {/if}
                </li>
            {/strip}
        {/foreach}
    </ul>
{/if}
