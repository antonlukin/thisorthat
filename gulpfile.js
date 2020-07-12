const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const cleanCss = require('gulp-clean-css');
const sassGlob = require('gulp-sass-glob');
const plumber = require('gulp-plumber');
const flatten = require('gulp-flatten');
const prefix = require('gulp-autoprefixer');
const connect = require('gulp-connect');
const template = require('gulp-md-template');


const path = {
  source: 'src/',
  assets: 'public/assets/'
}

gulp.task('styles', (done) => {
  gulp.src([path.source + '/styles/app.scss'])
    .pipe(plumber())
    .pipe(sassGlob({
      allowEmpty: true
    }))
    .pipe(sass({
      errLogToConsole: true
    }))
    .pipe(prefix())
    .pipe(concat('styles.min.css'))
    .pipe(cleanCss())
    .pipe(gulp.dest(path.assets))

  done();
});


gulp.task('watch', (done) => {
  gulp.watch('./src/**/*', gulp.series('styles'));

  done();
});


gulp.task('images', (done) => {
  gulp.src([path.source + '/images/*.{jpg,png}'])
    .pipe(flatten())
    .pipe(gulp.dest(path.assets + '/images/'));

  done();
});


gulp.task('markdown', (done) => {
  gulp.src('./docs/views/index.html')
    .pipe(template('./docs/files'))
    .pipe(gulp.dest('./docs'));

  done();
});


gulp.task('connect', (done) => {
  connect.server({
    root: './public'
  });

  done();
})

gulp.task('default', gulp.parallel('styles', 'images', 'watch', 'connect'));

