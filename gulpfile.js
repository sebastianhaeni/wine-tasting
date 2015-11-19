var babel = require('babelify');
var browserify = require('browserify');
var gulp = require('gulp');
var gutil = require('gulp-util');
var runSequence = require('run-sequence');
var sass = require('gulp-sass');
var nunjucksRender = require('gulp-nunjucks-render');
var gls = require('gulp-live-server');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var del = require('del');

gulp.task('default', function() {
    runSequence('clean', ['sass', 'nunjucks', 'js', 'images']);
});

gulp.task('clean', function() {
    return del(['www/**/*']);
});

gulp.task('sass', function() {
  gulp.src('app/styles/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./www/css'));
});

gulp.task('nunjucks', function() {
    nunjucksRender.nunjucks.configure(['app/templates/'], {watch: false});
    return gulp.src('app/pages/**/*.html')
       .pipe(nunjucksRender())
       .on('error', function(error) {
           gutil.log(gutil.colors.red('Error (' + error.plugin + '): ' + error.message));
           this.emit('end');
       })
       .pipe(gulp.dest('./www'));
});

gulp.task('js', function() {
    var bundler = browserify('app/scripts/app.js').transform(babel, {presets: ['es2015']});

    bundler.bundle()
        .on('error', function(err) { 
        	console.error(err); 
        	this.emit('end'); 
        })
        .pipe(source('app.js'))
        .pipe(buffer())
        .pipe(gulp.dest('www/js'));

    return;
});

gulp.task('images', function() {
    gulp.src('app/images/**/*.{png,jpg,gif}')
        .pipe(gulp.dest('www/images'));
});

gulp.task('server', ['default'], function() {
    var server = gls.new('./build/server.js');
    server.start();

    // Restart the server when file changes
    gulp.watch(['app/styles/**/*.scss'], function(file){
        runSequence('sass', function() {
            server.notify.apply(server, [file]);
        });
    });
    gulp.watch(['app/**/*.html'], function(file){
        runSequence('nunjucks', function() {
            server.notify.apply(server, [file]);
        });
    });
    gulp.watch(['app/scripts/**/*.js'], function(file){
        runSequence('js', function() {
            server.notify.apply(server, [file]);
        });
    });
    gulp.watch(['app/images/**/*.{png,jpg,gif}'], function(file){
        runSequence('images', function() {
            server.notify.apply(server, [file]);
        });
    });
});
