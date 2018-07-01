var gulp = require('gulp');
// var imagemin = require('gulp-imagemin');
// var uglify = require('gulp-uglify');
// var sass = require('gulp-sass');
// var concat = require('gulp-concat');
// var plumber = require('gulp-plumber');
// var cleanCSS = require('gulp-clean-css');
// var autoprefixer = require('gulp-autoprefixer');
// var sourcemaps = require('gulp-sourcemaps');
// var rename = require('gulp-rename');
// var wait = require('gulp-wait');
// var browserSync = require('browser-sync').create();

/*
  -- TOP LEVEL FUNCTIONS--
  gulp.task - Define tasks
  gulp.src - Point to files to use
  gulp.dest - Points to folder to output
  gulp.watch - Watch files and folder for change
*/


// Logging a message
gulp.task('message', function(){
  return console.log('i am running');
});

 // Copy third party libraries from /node_modules into /vendor
gulp.task('vendor', function() {

  // Jquery & Font-Awesome
  gulp.src([
      './vendor/components/**/*'
    ])
  .pipe(gulp.dest('./core'))

  // Bootstrap
  gulp.src([
      './vendor/twbs/**/*'
    ])
  .pipe(gulp.dest('./core'))

  // Select2
  gulp.src([
      './vendor/select2/**/*'
    ])
  .pipe(gulp.dest('./core'))

  // ckeditor4
  gulp.src([
      './vendor/ckeditor/**/*'
    ])
  .pipe(gulp.dest('./core'))

   // ckeditor4
  gulp.src([
      './vendor/froala/wysiwyg-editor/**/*'
    ])
  .pipe(gulp.dest('./core/froala'))

});