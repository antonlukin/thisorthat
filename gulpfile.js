var gulp = require('gulp');
var template = require('gulp-md-template');


gulp.task('markdown', function (done) {
  gulp.src('./docs/views/index.html')
    .pipe(template('./docs/files'))
    .pipe(gulp.dest('./docs'));

  done();
});


gulp.task('default', function(done) {
  console.log('no default task');

  done();
});
