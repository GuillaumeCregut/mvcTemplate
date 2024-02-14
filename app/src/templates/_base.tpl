<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon"  href="assets/favicon.ico" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name=title}{/block}</title>
    <link rel="stylesheet" href="assets/styles/flash.css">
    <link rel="stylesheet" href="assets/styles/general.css">
    {block name=styles}
    {/block}
    <script src="assets/scripts/flash.js" defer></script>
    {block name=script}
    {/block}
</head>
<body>
    {if isset($flash)}
      {include file="partials/_flash.tpl"}
    {/if}
    {block name=body}
      Hello ! 
    {/block}
</body>
</html>