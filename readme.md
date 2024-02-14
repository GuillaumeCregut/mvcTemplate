# What is mvcTemplate

Hum, in fact, what it is not ?

It is not a production framework, as if it seems to be.

In fact, this framework is nearly form production use, but it it more a pedagogical projet to understand how Symfony should work.

And it is still in developpement

## Functionnality

- As not Symfony, the framework uses [smarty](https://www.smarty.net/) for templating, and contain already it.
- Dynamic routing using attributes. No need to maintain a list of routes. Same, routes can be used in templates
- Debug template using [Whoops](https://github.com/filp/whoops)
- Flash system to alert users
- Form generator : you can create a form with required field. Then in templates, you can generate forms directly.
- Support error loggging
- Support straight mail sending using PHP function directly, with templates
- And more to come.
- Works in docker for more flexibility
- Use .env file and env datas, so you can directly pass your env datas with Docker

So, for the moment, no instruction on how to use it.

