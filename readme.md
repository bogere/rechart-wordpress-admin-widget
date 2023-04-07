![WordPress Plugin Active Installs](https://img.shields.io/wordpress/plugin/installs/seo-by-rank-math?color=%234098d7&style=for-the-badge) ![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/seo-by-rank-math?color=%234098d7&style=for-the-badge)

Recharts Admin Dashboard widget is the wordpress plugin that is built using the Reactjs 
framework and PHP/wordpress

## Getting Started

These instructions will help you to get the plugin up and running on your local machine for development and testing purposes.

## ⚠️ Prerequisites

We recommend using these tools for the development of Recharts Admin.

- Make sure [PHPCS & WPCS](https://rajaamanullah.com/how-to-install-wordpress-coding-standards/) are installed and working properly

### Required WP Plugins:
- [Query Monitor](https://wordpress.org/plugins/query-monitor/)

Rexhart Admin Dashboard plugin also requires [Node.js](https://nodejs.org/). The Project is built using the Node v16.0.0 and the latest version of NPM.

Refer to [this tutorial](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) to download and install Node.js and NPM

After installing Node, run the below command in the plugin directory to install all the required dependencies.

`npm install `

Then proceed to install the php libraries using the composer tool
`composer install `

## 🏗️ Development
`npm start` - Starting the reactjs frontend app

`npm run lint:php` - After you have set up the PHPCodeSniffer and setting up the wordpress 
coding standard files and then go ahead to run the above command

Go ahead and activate the plugin and then visit the admin dashboard while the client 
is running in the development mode

**Production:**

`npm run build` - It creates a permanent built frontend app and the go ahead and activate 
the plugin


### Pull Request Formatting
 - Before creating a new branch for any issue, make sure the issue is created, if not please create it before creating a new branch and PR
 - Branch slug format should be `fixed-issueid`
 - PR Title should start with the `Fixed #issueid: Issue Title`
 - Make sure to close the issue from the PR description area [ `Closes/Fixes #issueid`]