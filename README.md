Wine Tasting Party App
===================================

## Environment Dependencies
* [nodejs](https://nodejs.org/) `^0.12`
* [PHP](https://php.net) `^5.5`
* [Composer](https://getcomposer.org/)

## Install instructions

1. Run `npm install`
2. Configure the created files `config/api.yml` and `config/propel.yml` to your needs.

## Running

Run `npm start` to execute the application. Run `npm start --open` to make it automatically open in your browser.

## Building

* Execute `npm run build` to manually compile.

## Branching & Deployment

The stable branch is 'prod'.
Changes are first to be tested on stage before being merged into 'prod' branch.
'master' and 'stage' are deployed automatically to the respective server.

Production deployment is done manually by the person in charge of releases.
