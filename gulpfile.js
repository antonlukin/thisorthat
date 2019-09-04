const gulp = require('gulp');
const connect = require('gulp-connect');
const template = require('gulp-md-template');


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

gulp.task('default', gulp.parallel('connect'));

