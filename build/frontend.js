/**
 * Builds the frontend and starts a server with live reload.
 */
const childProcess = require('child_process');

console.log("Running app at http://localhost:3000");
childProcess.execFileSync('gulp', []);

if(process.env.npm_config_argv.indexOf('--open') >= 0){
  require('open')('http://localhost:3000');
}
