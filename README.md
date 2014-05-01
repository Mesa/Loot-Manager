Commander
=========

PHP App based on command design pattern.

My intention was to create a flat file App, where my blog entries are stored in files.
The App should parse, cache and deliver my markdown files.

So i came up with this approach to handle web and cli scripts in one app. I like the Symfony2
cli commands to create your DB, clear your cache, etc... An app should organizing itself, or at least
support you as much as possible.

What i don't want, is all this new stuff, which is so irrelevant to php because we have all what we need already.
Yaml looks nice, but where is the difference to an array? Yaml is converted to an array.

This was an example and is reduced to one "magic" we don't need, but it is not limited to this one. I apologize to all
who worked on Yaml and spend their free time for it.

* Service Locator (don't hide your dependencies, use setters and getters in your classes)
* Singleton (the php lifecycle is to short to get any benefits from a singleton and you hide your dependencies)
* Registry
* ....

It tried to ignore all that black magic and do it the way of least surprise. When your code is to complex for your
ide, it wont be easier for an human to understand.


### Your Command is my wish ###

The main Part is the command class. In the DemoApp/Command folder you can see, the FrontPage.php


    use Mesa\Commander\System\CommandInterface;

    class FrontPage implements CommandInterface
    {
        public function execute(array $args = [])
        {
            return new ResponseService("Welcome to Commander.");
        }

        public function getRoute()
        {
            return new Route("FrontPage", '/\/*/');
        }
    }

#### getRoute() ####

create a new Route, give it a name and a regex, the app can match the request url against. The matched result will
added as param in your ***execute($args)*** method.


#### execute() #####

This method is executed, when your regex matches the request url. It has to return a class which implements
the ResponseInterface or throw an Exception, so the app tries to handle that exception. (This part is in progress
and more Exceptions will be added maybe)

But wait, what is when i need a different class in my command? Well, you create a ClosureService and in your command you create
a method prefixed with **set**&lt;ServiceName&gt;() and the app will call that method before it executes the "execute" method.

### Install ###

Get Commander via Packagist. Create the composer.json in your project and add the following content to your **composer.json**

    {
         "require": {
             "mesa/commander": "*@dev"
         }
    }

install all dependencies with

    ./composer.phar install

create your public folder and point your web root to that folder.

    mkdir public

create your own index.php or copy the default file from Commander package to your public folder.

    cp vendor/mesa/commander/public/index.php public/index
    cp vendor/mesa/commander/main.php main.php

[https://packagist.org/packages/mesa/commander](Packagist)