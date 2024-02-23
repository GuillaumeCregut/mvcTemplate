<section style="
    margin-top: 10px;
    bottom : 0;
    display: flex;
    width : 100vw;
    padding: 5px;
    background-color: red;
">
    <p style=" padding : 5px; margin-right: 5px; border: 1px solid black; ">Powered by Smarty {$smarty.version}</p>
{foreach from=$menus item=menu}
<p style="
padding : 5px;
margin-right: 5px;
border: 1px solid black;
">
{if $menu.link!=''}
<a href="{$menu.link}">{$menu.display}</a>
{else}
{$menu.display}
{/if}
</p>
{/foreach}
</section>