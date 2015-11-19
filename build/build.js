/**
 * Builds the propel models and builds the frontend.
 */

const fs = require('fs');
const execPropel = require('./api/exec-propel');
const childProcess = require('child_process');

// updating propel config
require('./api/propel-config');
// Generating propel models
console.log('Generating models...');
execPropel(['model:build']);
// Generate static js resources
console.log('Generating frontend...');
childProcess.execFileSync('.\\node_modules\\.bin\\gulp.cmd');

console.log('Done!');
