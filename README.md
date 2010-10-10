# FreshCake: A FreshBooks API Wrapper for CakePHP

## Features

* Automagically works with scaffolding!
* Access the entire Freshbooks API
  * Currently supports create, update, get, delete, list for callbacks, categories, clients, estimates, expenses, gateway, invoices, items, payments, projects, recurring, staff, tasks, and time_entries.
* Uses triggered exceptions for error handling.

## Requirements

PHP5+, CakePHP 1.3+, FreshBooks account (w/ API credentials)

## Installing

1. [Sign up](http://freshbooks.com) for a FreshBooks account, they are free!
2. Extract the contents of this repo into *app/plugins/freshbooks/* or use [git clone](http://www.kernel.org/pub/software/scm/git/docs/git-clone.html) or [git submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) in your plugins folder:
		
	git clone git://github.com/shama/freshcake.git freshbooks

3. Copy the following lines into *app/config/database.php* and add your subdomain and api token:

	var $freshbooks = array(
		'datasource' => 'freshbooks.freshbooks',
		'subdomain' => 'SUBDOMAIN-HERE',
		'token' => 'TOKEN-HERE',
	);

*Your subdomain is what you typed into the 'login page' field when you signed up or the word between http:// and .freshbooks.com in your login url.*

## Usage

Almost all of the API calls have **find, save and delete** capabilities. While this example is for the manipulating the client data the same techniques will work for the other API calls.

### QUICK I NEED INSTA-AWESOME!

If you are lazy like myself just uncomment the *var $scaffold;* in the *freshbooks_app_controller.php*. Then type in *http://yourwebsite.com/freshbooks/clients* or *http://yourwebsite.com/freshbooks/projects* and so on. Cool eh?

Although you shouldn't do that: (1) because now your freshbooks account is opened to everyone and (2) you shouldn't modify the plugin (unless you are contributing!). Instead you should extend the plugin, more on that later.

#### CHOOSING A MODEL

In your controller, use the API just like you would any other model. Here we are going to use Client:

    var $uses = array('Freshbooks.Client');

#### FIND ALL

To grab all records use (remember Freshbooks limits results to 100):

    $clients = $this->Client->find('all');

#### FIND ONE

    $client = $this->Client->find('first');

#### FIND BY USERNAME

    $client = $this->Client->findByUsername('myusername');

OR

    $client = $this->Client->find('all', array(
        'conditions' => array(
            'username' => 'myusername',
        ),
    ));

Each API has it restrictions on which fields you can find by. Please check the 'list' section of the FreshBooks API to see what fields are available to search by.

#### FIND AND LIMIT RESULTS (YES IT PAGINATES!)

    $client = $this->Client->find('all', array(
        'limit' => 15,
        'page' => 2,
    ));

#### SAVE

    $this->Client->save(array(
        'first_name' => 'Test',
        'last_name' => 'Person',
        'organization' => 'Test Inc.',
        'email' => 'test@example.com',
    ));

#### UPDATE

    $this->Client->id = 13;
    $this->Client->save(array(
        'first_name' => 'Test',
        'last_name' => 'Person',
        'organization' => 'Test Inc.',
        'email' => 'test@example.com',
    ));

OR

    $this->Client->save(array(
        'client_id' => 13,
        'first_name' => 'Test',
        'last_name' => 'Person',
        'organization' => 'Test Inc.',
        'email' => 'test@example.com',
    ));

Please check the FreshBooks API for available fields to save and which are required (although this plugin does its best to validate!)

#### DELETE

    $this->Client->delete(13);

#### ERROR HANDLING

It is highly recommended that you put a try catch statement around your code. Any errors returned from the API or this plugin will throw an error for you to deal with. Here is an example:

    try {
        $clients = $this->Client->find('all');
    } catch (Exception $e) {
        debug($e->getMessage());
    }

#### EXTENDING THE MODELS

Please avoid modifying the plugin source code (unless you are contributing!). Here is how to extend and modify the models:

Create your own model in *app/models/* lets call it *my_client.php*

    App::import('Model', 'Freshbooks.Client');
    class MyClient extends Client {
        var $name = 'MyClient';
    }

Now you are free to extend the model however you like without editing the plugin!

## Issues

Please report any issues you have with the plugin to the [issue tracker](http://github.com/shama/freshcake/issues) on github. **This is a beta release so please be gentle!**

## License

FreshCake is offered under an [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Copyright

2010 Kyle Robinson Young, [kyletyoung.com](http://kyletyoung.com)

## Roadmap and Known Issues

* MORE TESTING! MORE TESTS!
* Fix issue with some text fields showing up as Array - v0.2
* Format lines/xml on invoice, estimate and recurring afterFind (so they dont break scaffold) - v0.2
* Uppercases returned results if array, it should not - v0.2
* Returns blank arrays when it should return '' - v0.2
* Test support for linking models together (hopefully already does automatically) - v0.2
* Implement OAuth support - v0.3
* i18n - v0.3

## Changelog

### 0.2

* Implemented system.current and added test case
* Implemented staff.current and fixed response from staff.list
* Implemented recurring autobill and added test cases
* Added invoice.sendByEmail and invoice.sendBySnailMail and test cases
* Added estimate.sendByEmail and test case
* Fields can be set to custom XML now (needed for fixing tasks in project)
* Added test cases for callback.verify, callback.resendToken
* Added support for callback.verify, callback.resendToken
* Added methods to return last request and response xml
* Built a better method for sending xml directly to freshbooks
* Added test case for expense, invoice, item, payment, project, recurring, staff, task, time_entry models
* Added test case for category, client, gateway, estimate models
* Added support for project tasks
* Added custom XML override to datasource
* Added support for invoice and estimate lines

### 0.1

* Added schema and validation for models
* Added folder structure for i18n and tests
* Added controllers for most api calls
* Added models for each api call
* Added freshbooks datasource to handle most of the API
* Setup app model and app controller
* Setup basic plugin
