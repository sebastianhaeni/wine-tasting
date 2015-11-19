/**
 * Builds the frontend and starts a server with live reload.
 */
const childProcess = require('child_process');

console.log("Running app at http://localhost:3000");
var child = childProcess.execFile('.\\node_modules\\.bin\\gulp.cmd', ['server'], function(error, stdout, stderr) {
    console.log(stdout);
});
child.stdout.on('data', function(data) {
    console.log(data);
});

if(process.env.npm_config_argv.indexOf('--open') >= 0){
    require('open')('http://localhost:3000');
}
