{strip}
{assign var=form_layout value='vertical'}
{assign var=form_class value='form-vertical'}
{if isset($layout)}
    {assign var=form_layout value="/:/"|preg_split:$layout scope=parent}
    {if count($form_layout) > 0}
        {if $form_layout[0]==='horizontal'}
            {assign var=form_class value="form-horizontal"}
        {elseif $form_layout[0]==='inline'}
            {assign var=form_class value="form-inline"}
        {/if}
    {/if}
{/if}
{if isset($class) && is_string($class)}
    {assign var=class value="{$form_class} {$class}"}
    {assign var=class value=$form_class|trim}
{else}
    {assign var=class value=$form_class}
{/if}
{assign var=use_ajax value=false}
{if isset($method)}
    {assign var=method value={$method|lower}}
    {if $method==='ajax-get'}
        {assign var=use_ajax value=true}
        {assign var=method value='get'}
    {elseif $method==='ajax-post'}
        {assign var=use_ajax value=true}
        {assign var=method value='post'}
    {/if}
{/if}
<form data-toggle="nabu-form"{include file="general-attrs.tpl"}
     {if isset($name) && is_string($name) && strlen($name)>0} name="{$name}"{/if}
     {if isset($method) && ($method==='get' || $method==='post')} method="{$method}"{/if}
     {if isset($action) && is_string($action) && strlen($action)>0} action="{$action}"{/if}
     {if isset($charset) && is_string($charset) && strlen($charset)>0} accept-charset="{$charset}"{/if}
     {if isset($enctype) && is_string($enctype) && strlen($enctype)>0} enctype="{$enctype}" data-enctype="{$enctype}"{/if}
     {if isset($target) && is_string($target) && strlen($target)>0} target="{$target}"{/if}
     {if isset($autocomplete) && ($autocomplete==='on' || $autocomplete==='off')} autocomplete="{$autocomplete}"{/if}
     {if isset($evaluate) && ($evaluate==='all' || $evaluate==='visible')} data-evaluate="{$evaluate}"{/if}
     {if isset($validation) && ($validation==='onchange' || $validation==='live')} data-validation="{$validation}"{/if}
     {if isset($reflection) && is_string($reflection) && strlen($reflection)>0} data-reflection="{$reflection}" data-reflection-success="has-success" data-reflection-warning="has-warning" data-reflection-error="has-error"{/if}
     {if $use_ajax} data-ajax="true" {if isset($ajax_target) && is_string($ajax_target) && strlen($ajax_target)>0} data-ajax-target="{$ajax_target}"{/if}{/if}
>
{/strip}
