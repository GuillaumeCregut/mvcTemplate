{extends file="_base.tpl"}
{block name=body}
<h1>Session</h1>.
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$routes key=name item=value}
            <tr>
                <td>{$key}</td>
                <td>
                    {if is_array($value)}
                        {foreach from=$value item=subValue}
                            <p>{$subValue}</p>
                        {/foreach}
                    {else}
                        {$value}
                    {/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    {$debug}
{/block}