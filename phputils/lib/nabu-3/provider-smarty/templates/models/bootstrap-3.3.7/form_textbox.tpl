{strip}
    {if isset($horizontal)}
        {assign var=variant value=horizontal}
        {assign var=rel value="/:/"|preg_split:$horizontal}
    {elseif isset($inline)}
        {assign var=variant value=inline}
        {assign var=rel value=false}
    {elseif isset($form_layout)}
        {assign var=variant value=$form_layout[0]}
        {assign var=rel value=$form_layout}
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
    {if !isset($placeholder)}{assign var=placeholder value=false}{/if}
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
    {if !isset($type)}{assign var=type value="text"}{/if}
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
                {elseif $addon_left==='dropdown' && isset($addon_left_options) && is_array($addon_left_options) && count($addon_left_options)>0}
                    <div class="input-group-btn" data-toggle="nabu-select">
                        {assign var=final_text value='&nbsp;'}
                        {if isset($addon_left_name)}
                            {if $value_left && array_key_exists($value_left, $addon_left_options)}
                                {if is_array($addon_left_options[$value_left])}
                                    {if array_key_exists($addon_left_name, $addon_left_options[$value_left])}
                                        {assign var=final_text value=$addon_left_options[$value_left][$addon_left_name]}
                                    {elseif array_key_exists('translation', $addon_left_options[$value_left]) && is_array($addon_left_options[$value_left]['translation']) && array_key_exists($addon_left_name, $addon_left_options[$value_left]['translation'])}
                                        {assign var=final_text value=$addon_left_options[$value_left]['translation'][$addon_left_name]}
                                    {/if}
                                {elseif is_string($addon_left_options[$value_left])}
                                    {assign var=final_text value=$addon_left_options[$value_left]}
                                {/if}
                            {elseif isset($addon_left_options_default_name) && is_string($addon_left_options_default_name)}
                                {assign var=final_text value=$addon_left_options_default_name}
                            {/if}
                        {else}
                            {if $value_left && array_key_exists($value_left, $addon_left_options) && is_string($addon_left_options[$value_left])}
                                {assign var=final_text value=$addon_left_options[$value_left]}
                            {/if}
                        {/if}
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$final_text}</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                        {if $addon_left_name}<input type="hidden" name="{$addon_left_name}" value="{$value_left}">{/if}
                        <ul class="dropdown-menu">
                            {if isset($addon_left_options) && is_array($addon_left_options) && count($addon_left_options)>0}
                                {foreach from=$addon_left_options key=kopt item=option}
                                    {if isset($addon_left_options_id) && array_key_exists($addon_left_options_id, $option)}
                                        {assign var=op_id value=$option[$addon_left_options_id]}
                                    {else}
                                        {assign var=op_id value=$kopt}
                                    {/if}
                                    {if isset($addon_left_options_name)}
                                        {if array_key_exists($addon_left_options_name, $option)}
                                            {assign var=op_name value=$option[$addon_left_options_name]}
                                        {elseif array_key_exists('translation', $option) && is_array($option.translation) && array_key_exists($addon_left_options_name, $option.translation)}
                                            {assign var=op_name value=$option.translation[$addon_left_options_name]}
                                        {/if}
                                    {elseif is_string($option)}
                                        {assign var=op_name value=$option}
                                    {/if}
                                    <li data-id="{$op_id}"{if $op_id===$value_left} class="active"{/if}><a href="#">{$op_name}</a></li>
                                {/foreach}
                            {/if}
                        </ul>
                    </div>
                {/if}
                {if isset($addon_left_label) && is_string($addon_left_label) && strlen($addon_left_label) > 0}
                    <span class="input-group-addon">{$addon_left_label}</span>
                {/if}
            {/if}
            {if isset($type) && $type===textarea}
                <textarea class="form-control"
                          {if isset($autocomplete)} autocomplete="{if $autocomplete===true}on{else}off{/if}"{/if} type="{$type}"
                          {if isset($id)} id="{$id}"{/if}
                          {if $name} name="{$name}"{/if}
                          {if $addons && isset($addon_left) && ($addon_left==='radiobox' || $addon_left==='checkbox') && isset($addon_left_check) && $value_left!==$addon_left_check} disabled{/if}
                          {if $placeholder} placeholder="{$placeholder}"{/if}
                          {if isset($autofocus)} autofocus{/if}
                          {if isset($rows) && is_numeric($rows)} rows="{$rows}"{/if}>{/strip}{if is_string($value)}{$value|escape:"html"}{/if}{strip}</textarea>
            {else}
                <input class="form-control"
                       {if isset($autocomplete)} autocomplete="{if $autocomplete===true}on{else}off{/if}"{/if} type="{$type}"
                       {if isset($id)} id="{$id}"{/if}
                       {if $name} name="{$name}"{/if}
                       {if $addons && isset($addon_left) && ($addon_left==='radiobox' || $addon_left==='checkbox') && isset($addon_left_check) && $value_left!==$addon_left_check} disabled{/if}
                       {if $placeholder} placeholder="{$placeholder}"{/if}
                       {if is_string($value)} value="{$value|escape:"html"}"{/if}
                       {if isset($autofocus)} autofocus{/if}
                       {if isset($maxlength) && is_numeric($maxlength)} maxlength="{$maxlength}"{/if}>
            {/if}
            {if $addons}</div>{/if}
            {if $help}<p class="help-block">{$help}</p>{/if}
        {if $variant===horizontal}</div>{/if}
    </div>
{/strip}
