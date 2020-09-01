// ***********************************************************
// This example support/index.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'

// Alternatively you can use CommonJS syntax:
// require('./commands')

// These cookies will not be cleared before each test runs
Cypress.Cookies.defaults({
	preserve: /wordpress_.*/
})

// In case you want to disable all screenshots (useful for API testing)
Cypress.Screenshot.defaults({
	screenshotOnRunFailure: true
})
