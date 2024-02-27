# What is mvcTemplate

Hum, in fact, what it is not ?

It is not a production framework, as if it seems to be.

In fact, this framework is nearly from production use, but it is more a pedagogical projet to understand how Symfony should work.

And it is still in developpement

## Functionnality

- As not Symfony, the framework uses [smarty](https://www.smarty.net/) for templating, and contain already it.
- Dynamic routing using attributes. No need to maintain a list of routes. Same, routes can be used in templates
- Debug template using [Whoops](https://github.com/filp/whoops)
- Flash system to alert users
- Form generator : you can create a form with required field. Then in templates, you can generate forms directly.
- Support error loggging
- globals arrays ($_POST, $_GET, $_SERVER, etc...) are wrapped in class.
- Responses are wrapped in class.
- Entities uses validators (or constraints) to deal with forms.
- Managers are used to link entities and database.
- Debug footer exist in dev mode to help debbugging
- Support straight mail sending using PHP function directly, with templates
- Kernel classes are tested with phpUnit (tests are included)
- Works in docker for more flexibility
- Use .env file and env datas, so you can directly pass your env datas with Docker
- Clean Code with PHPStan, PHPCS and PHPMD
- And more to come.

So, for the moment, no instruction on how to use it.

