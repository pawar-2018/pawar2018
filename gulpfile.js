// Grab our gulp packages
var gulp  = require('gulp'),
  gutil = require('gulp-util'),
  cssnano = require('gulp-cssnano'),
  plumber = require('gulp-plumber'),
  gulpLoadPlugins = require('gulp-load-plugins'),
  browserSync = require('browser-sync').create();

const $ = gulpLoadPlugins();
const wpPath = "./wp-content/themes/pawar2018/";

gulp.task('images', () => {
  return gulp.src(wpPath + 'assets/*.{svg,png,jpg,gif}')
    .pipe($.cache($.imagemin()))
    .pipe(gulp.dest(wpPath + "assets/"))
    .pipe($.size({title: 'images'}));
});

gulp.task('styles', function() {
  return gulp.src()
    .pipe(plumber(function(error) {
      gutil.log(gutil.colors.red(error.message));
      this.emit('end');
    }))
    .pipe($.sourcemaps.init())
    .pipe($.sass())
    .pipe($.postcss([autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    })]))
    .pipe(gulp.dest(wpPath))
    .pipe(cssnano())
    .pipe($.sourcemaps.write('.'))
    .pipe(gulp.dest(wpPath))
    .pipe($.size({title: 'styles'}));
});

gulp.task('scripts', () => {
  return gulp.src(wpPath + 'js/scripts/*.js')
    .pipe(plumber())
    .pipe($.sourcemaps.init())
    .pipe($.concat('main.min.js'))
    .pipe($.uglify({preserveComments: 'some'}))
    .pipe($.sourcemaps.write('.'))
    .pipe(gulp.dest(wpPath + 'js'))
    .pipe($.size({title: 'scripts'}));
});

gulp.task('serve', function() {

  browserSync.init({
    proxy: "http://localhost/8000",
  });

  gulp.watch([wpPath + 'sass/**/*.scss', wpPath + 'sass/*.scss'],
    ['styles']).on('change', browserSync.reload);
  gulp.watch(wpPath + 'js/scripts/*.js', ['scripts']).on('change', browserSync.reload);

});

gulp.task('watch', function() {
  gulp.watch('./assets/scss/**/*.scss', ['styles']);
  gulp.watch('./assets/js/scripts/*.js', ['scripts']);
});

gulp.task('default', function() {
  gulp.start('styles', 'scripts');
});
