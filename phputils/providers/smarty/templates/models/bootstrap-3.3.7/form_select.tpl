{strip}
    {if isset($horizontal)}
        {assign var=variant value=horizontal}
        {assign var=rel value="/:/"|preg_split:$horizontal}
    {elseif isset($inline)}
        {assign var=variant value=inline}
        {assign var=rel value=false}
    {elseif isset($form_variant)}
        {assign var=variant value=$form_variant}
        {assign var=rel value=$form_rel}
    {else}
        {assign var=variant value=vertical}
        {assign var=rel value=false}
    {/if}
    {if isset($label)}
        {if !isset($sr_only) || $variant!==vertical}{assign var=sr_only value=false}{/if}
    {else}
        {assign var=label value=false}
        {assign var=sr_only value=false}
    {/if}
    {if !isset($field)}{assign var=field value=false}{/if}
    {if !isset($addon_left_field)}{assign var=addon_left_field value=false}{/if}
    {if !isset($addon_right_field)}{assign var=addon_right_field value=false}{/if}
    {if !isset($name)}{assign var=name value=$field}{/if}
    {if !isset($help)}{assign var=help value=false}{/if}
    {assign var=label_class value=''}
    {assign var=div_class value=''}
    {if $variant===horizontal}
        {if count($rel)===2}
            {assign var=label_class value="col-sm-{$rel.0} control-label"}
            {assign var=div_class value="col-sm-{$rel.1}"}
        {elseif count($rel)===1}
            {assign var=label_class value="col-sm-{$rel.0} control-label"}
            {assign var=div_class value="col-sm-{12-$rel.1}"}
        {else}
            {assign var=label_class value="col-sm-4 control-label"}
            {assign var=div_class value="col-sm-8"}
        {/if}
    {else}
        {if $sr_only}{assign var=label_class value="{$label_class} sr-only"}{/if}
    {/if}
    {assign var=value value=false}
    {assign var=value_left value=false}
    {assign var=value_right value=false}
    {if isset($from) && $from!==null}
        {if is_array($from)}
            {if is_string($field)}
                {if array_key_exists($field, $from)}
                    {assign var=value value=$from[$field]}
                {elseif array_key_exists('translation', $from) && is_array($from.translation) && array_key_exists($field, $from.translation)}
                    {assign var=value value=$from.translation[$field]}
                {/if}
            {/if}
            {if is_string($addon_left_field) && array_key_exists($addon_left_field, $from)}
                {assign var=value_left value=$from[$addon_left_field]}
            {/if}
            {if is_string($addon_right_field) && array_key_exists($addon_right_field, $from)}
                {assign var=value_right value=$from[$addon_right_field]}
            {/if}
        {elseif is_string($from)}
            {assign var=value value=$from}
        {/if}
    {/if}
    {if isset($addon_left) || isset($addon_right)}
        {assign var=addons value=true}
    {else}
        {assign var=addons value=false}
    {/if}
    <div class="form-group{if isset($class)} {$class}{/if}">
        {if is_string($label)}<label{if isset($id)} for="{$id}"{/if}{if strlen($label_class)>0} class="{$label_class|trim}"{/if}>{$label}</label>{/if}
        {if $variant===horizontal}<div{if strlen($div_class)>0} class="{$div_class}"{/if}>{/if}
        {if $addons}<div class="input-group" data-toggle="nabu-input-group">{/if}
        {if isset($addon_left)}
            {if $addon_left==='radiobox'}
                <span class="input-group-addon">
                    <input type="radio"{if isset($addon_left_name)} name="{$addon_left_name}"{/if}{if isset($addon_left_check)} value="{$addon_left_check|escape:'html'}"{if $value_left===$addon_left_check} checked{/if}{/if}>
                </span>
            {elseif $addon_left==='checkbox'}
                <span class="input-group-addon">
                    <input type="checkbox"{if isset($addon_left_name)} name="{$addon_left_name}"{/if}{if isset($addon_left_check)} value="{$addon_left_check|escape:'html'}"{if $value_left===$addon_left_check} checked{/if}{/if}>
                </span>
            {/if}
            {if isset($addon_left_label) && is_string($addon_left_label) && strlen($addon_left_label) > 0}
                <span class="input-group-addon">{$addon_left_label}</span>
            {/if}
        {/if}
        <div class="{if $addons}dropdown{else}dropdown{/if}"{if isset($id)} id="{$id}"{/if} data-toggle="nabu-select">
            <div class="btn-group">
                <button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"{if $addons && isset($addon_left) && ($addon_left==='radiobox' || $addon_left==='checkbox') && isset($addon_left_check) && $value_left!==$addon_left_check} disabled{/if}>{strip}
                    {assign var=final_text value='&nbsp;'}
                    {if isset($options_name)}
                        {if $value && array_key_exists($value, $options) && is_array($options[$value])}
                            {if array_key_exists($options_name, $options[$value])}
                                {assign var=final_text value=$options[$value][$options_name]}
                            {elseif array_key_exists('translation', $options[$value]) && is_array($options[$value]['translation']) && array_key_exists($options_name, $options[$value]['translation'])}
                                {assign var=final_text value=$options[$value]['translation'][$options_name]}
                            {/if}
                        {elseif isset($options_default_name) && is_string($options_default_name)}
                            {assign var=final_text value=$options_default_name}
                        {/if}
                    {else}
                        {if $value && array_key_exists($value, $options) && is_string($options[$value])}
                            {assign var=final_text value=$options[$value]}
                        {/if}
                    {/if}
                    {$final_text}
                {/strip}</button>
                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"{if $addons && isset($addon_left) && ($addon_left==='radiobox' || $addon_left==='checkbox') && isset($addon_left_check) && $value_left!==$addon_left_check} disabled{/if}>
                    <span class="caret"></span>
                </button>
                {if $name}<input type="hidden" name="{$name}" value="{$value}">{/if}
                <ul class="dropdown-menu"{if isset($id)} aria-labelledby="{$id}"{/if}>
                    {foreach from=$options key=kopt item=option}
                        {if isset($options_id) && array_key_exists($options_id, $option)}
                            {assign var=op_id value=$option[$options_id]}
                        {else}
                            {assign var=op_id value=$kopt}
                        {/if}
                        {if isset($options_name)}
                            {if array_key_exists($options_name, $option)}
                                {assign var=op_name value=$option[$options_name]}
                            {elseif array_key_exists('translation', $option) && is_array($option.translation) && array_key_exists($options_name, $option.translation)}
                                {assign var=op_name value=$option.translation[$options_name]}
                            {/if}
                        {elseif is_string($option)}
                            {assign var=op_name value=$option}
                        {/if}
                        <li data-id="{$op_id}"{if $op_id===$value} class="active"{/if}><a href="#">{$op_name}</a></li>
                    {/foreach}
                </ul>
            </div>
        </div>
        {if isset($addon_left) || isset($addon_right)}</div>{/if}
        {if $help}<p class="help-block">{$help}</p>{/if}
    {if $variant===horizontal}</div>{/if}
    </div>
{/strip}
