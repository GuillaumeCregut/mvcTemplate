{extends file="_base.tpl"}
{block name=body}
<h1>liste des routes.</h1>
    <table>
        <thead>
            <tr>
                <th>Nom de la route</th>
                <th>Route</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$routes key=name item=route}
            <tr>
                <td>{$name}</td>
                <td>{$route}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    {$debug}
{/block}