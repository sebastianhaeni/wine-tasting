var app = require('connect')();
var path = require('path');
var connect_livereload = require('connect-livereload');
var serve_static = require('serve-static');
var url = require('url');
var proxy = require('proxy-middleware');

var root = 'www/';
var port = 3000;

app.use(connect_livereload());

app.use('/api', proxy(url.parse('http://localhost:3001')));

root.split(",").forEach(function(r){
    app.use(serve_static(path.join(process.cwd(), r)));
});

app.listen(port, function () {
    var host = 'localhost';
    console.log('folder "%s" serving at http://%s:%s', root, host, port);
});
