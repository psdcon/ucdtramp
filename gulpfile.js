var gulp = require('gulp');
var gutil = require('gulp-util');
var uglify = require('gulp-uglify'); // Js
var minifyCss = require('gulp-minify-css'); // css
var autoprefixer = require('gulp-autoprefixer'); // css
var browserSync = require('browser-sync').create();
var ftp = require( 'vinyl-ftp' );

// Default gulp task is watch. It starts browsersync for live editing
gulp.task('default', ['watch']);

// Starts static server + watches files
gulp.task('watch', ['css'], function() {
    // wamp must be running
    browserSync.init({
        proxy: "localhost/ucdtramp/"
    });

    gulp.watch("css/*.css", ['css']);
    gulp.watch("js/*.js", ['js']);
    gulp.watch("*.html").on('change', browserSync.reload);
    gulp.watch("*.php").on('change', browserSync.reload);
    gulp.watch("includes/*.*").on('change', browserSync.reload);
    gulp.watch("templates/*.*").on('change', browserSync.reload);
});

// Minify css from css folder, save it to dist/css and auto-inject into browser sync
gulp.task('css', function() {
    return gulp.src("css/*.css")
        .pipe(autoprefixer())
        // .pipe(minifyCss({compatibility: 'ie10'}))
        .pipe(gulp.dest('css'))
        .pipe(browserSync.stream());
});

// Minify js from js folder, save it to dist/js and auto-reload browser sync
gulp.task('js', function() {
  return gulp.src('js/*.js')
    // .pipe(uglify())
    // .pipe(gulp.dest('dist/js'))
    .pipe(browserSync.stream());
});

// Uploads via ftp any chnages to files on server
gulp.task( 'ftp', function() {

    // Connection details. Uses specific ftp user
    var conn = ftp.create( {
        host:     'ftp.ucdtramp.com',
        user:     'temp@ucdtramp.com',
        password: 'dontforget!',
        parallel: 8,
        log:      gutil.log
    } );

    // Selects files to sync
    var globs = [
        '*.*', // Match files in root, not folders
        // 'dist/**', // Match folders
        'css/**',
        'fonts/**',
        'js/**',
        'themes/**',
        'templates/**',
        'includes/**',
        'files/**',
        
        // Upload images folder but ignore pages folder and it's contents
        'images/**',
        '!images/pages/**',
        // Files not to include
        '!ucdtc.sublime-project',
        '!ucdtc.sublime-workspace',
    ];

    // using base = '.' will transfer everything to /public_html correctly
    // turn off buffering in gulp.src for best performance

    return gulp.src( globs, { base: '.', buffer: false } )
        .pipe( conn.newer( '' ) ) // only upload newer files
        .pipe( conn.dest( '' ) );
} );