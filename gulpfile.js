const gulp = require('gulp');
const template = require('gulp-md-template');

const sass = require('gulp-sass');
const concat = require('gulp-concat');
const cleanCss = require('gulp-clean-css');
const plumber = require('gulp-plumber');
const prefix = require('gulp-autoprefixer');
const connect = require('gulp-connect');
const sassGlob = require('gulp-sass-glob');


gulp.task('markdown', function (done) {
  gulp.src('./docs/views/index.html')
    .pipe(template('./docs/files'))
    .pipe(gulp.dest('./docs'));

  done();
});


gulp.task('public-styles', function (done) {
  gulp.src(['./src/scss/app.scss'])
    .pipe(plumber())
    .pipe(sassGlob())
    .pipe(sass({
      errLogToConsole: true
    }))
    .pipe(prefix(['ie >= 10', 'ff >= 30', 'chrome >= 34', 'safari >= 7', 'opera >= 23', 'ios >= 7', 'android >= 4.4']))
    .pipe(concat('styles.min.css'))
    .pipe(cleanCss({
      compatibility: 'ie9'
    }))
    .pipe(gulp.dest('./public/assets/'))

  done();
})


gulp.task('watch', function (done) {
  gulp.watch('./src/**/*', gulp.series('public-styles'));

  done();
})

gulp.task('connect', function (done) {
  connect.server({
    root: 'public'
  });

  done();
});

gulp.task('default', gulp.parallel('public-styles', 'connect', 'watch'));