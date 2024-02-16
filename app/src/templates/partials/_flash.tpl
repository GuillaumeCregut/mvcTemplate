<div>

<ul class="toast_notifications" id="toast_notifications">
    {foreach from=$flash key=k item=v}
    {if $v.flashType=="success"}
        {include file="partials/_flash_success.tpl" message=$v.message}
    {/if}
    {if $v.flashType=="error"}
        {include file="partials/_flash_error.tpl" message=$v.message}
    {/if}
    {if $v.flashType=="warning"}
        {include file="partials/_flash_warning.tpl" message=$v.message}
    {/if}
    {if $v.flashType=="info"}
        {include file="partials/_flash_info.tpl" message=$v.message}
    {/if}
{/foreach}
</ul>
</div>