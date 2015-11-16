/**
 * Builds the propel models and builds the frontend.
 */

const fs = require('fs');
const execPropel = require('./api/exec-propel');

// updating propel config
require('./api/propel-config');
// Generating propel models
execPropel(['model:build']);

// Generate static js resources
// TODO
