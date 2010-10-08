# FreshCake: A Freshbooks API Wrapper for CakePHP

## Features

* Automagically works with scaffolding!
* Access the entire Freshbooks API (almost)
 * Currently supports create, update, get, delete, list for callbacks, categories, clients, estimates, expenses, gateway, invoices, items, payments, projects, recurring, staff, tasks, and time_entries.
* Uses triggered exceptions for error handling.

## Requirements

PHP5+, CakePHP 1.3+, Freshbooks account (w/ API credentials)

## Installing

  1. [Sign up](http://freshbooks.com) for a Freshbooks account, they are free!
  2. Extract the contents of this repo into *app/plugins/freshbooks/* or use git clone or git submodule in your plugins folder:
		
	git clone git://github.com/shama/cakefresh.git freshbooks

  3. Copy the following lines into *app/config/database.php* and add your subdomain and api token:

	var $freshbooks = array(
		'datasource' => 'freshbooks.freshbooks',
		'subdomain' => 'SUBDOMAIN-HERE',
		'token' => 'TOKEN-HERE',
	);

## Usage

TODO: Write this... until then view the [Freshbooks API](http://developers.freshbooks.com/)

Take a gander at controllers/examples_controller.php for examples with the TimeEntries API.

## Issues

Please report any issues you have with the plugin to the [issue tracker](http://github.com/shama/freshcake/issues) on github. **This is a beta release so please be gentle!**

## License

FreshCake is offered under an [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Copyright

2010 Kyle Robinson Young, [kyletyoung.com](http://kyletyoung.com)

### Roadmap and Known Issues

* MORE TESTING! MORE TESTS!
* Fix issue with some text fields showing up as Array
* Format lines/xml on invoice, estimate and recurring afterFind (so they dont break scaffold)
* Implement better custom xml override (not schema based)
* Implement callback.verify, callback.resendToken
* Implement estimate.sendByEmail
* Implement invoice.sendByEmail, invoice.sendBySnailMail
* Implement recurring.auto_bill
* Implement staff.current
* Implement OAuth support
* Add support for staff (needs custom afterFind)
* i18n

### Changelog

#### 0.2

* Added test case for expense, invoice, item, payment, project, recurring, staff, task, time_entry models
* Added test case for category, client, gateway, estimate models
* Added support for project tasks
* Added custom XML override to datasource
* Added support for invoice and estimate lines

#### 0.1

* Added schema and validation for models
* Added folder structure for i18n and tests
* Added controllers for most api calls
* Added models for each api call
* Added freshbooks datasource to handle most of the API
* Setup app model and app controller
* Setup basic plugin
