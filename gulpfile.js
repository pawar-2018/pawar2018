// Grab our gulp packages
var gulp  = require('gulp'),
  autoprefixer = require('autoprefixer'),
  gulpLoadPlugins = require('gulp-load-plugins'),
  yargs = require('yargs'),
  browserSync = require('browser-sync').create();

const $ = gulpLoadPlugins();
const wpPath = "./wp-content/themes/pawar2018/";
const PRODUCTION = !!(yargs.argv.production);

gulp.task('images', function() {
  return gulp.src(wpPath + 'images/*.{svg,png,jpg,gif}')
    .pipe($.debug({title: 'image:'}))
    .pipe($.imagemin())
    .pipe(gulp.dest(wpPath + "assets"))
    .pipe($.size({title: 'images'}));
});

gulp.task('styles', function() {
  return gulp.src(wpPath + "sass/style.scss")
    .pipe($.debug({title: 'style:'}))
    .pipe($.if(!PRODUCTION, $.sourcemaps.init()))
    .pipe($.sass({outputStyle: 'compressed'
    }).on('error', $.sass.logError))
    .pipe($.postcss([autoprefixer({
      cascade: false
    })]))
    .pipe($.cssnano())
    .pipe($.if(!PRODUCTION, $.sourcemaps.write('.')))
    .pipe(gulp.dest(wpPath))
    .pipe($.size({title: 'styles'}));
});

gulp.task('scripts', function() {
  return gulp.src(wpPath + 'js/scripts/*.js')
    .pipe($.if(!PRODUCTION, $.sourcemaps.init()))
    .pipe($.babel())
    .pipe($.concat('main.min.js'))
    // .pipe($.uglify({preserveComments: 'some'}))
    .pipe($.if(!PRODUCTION, $.sourcemaps.write('.')))
    .pipe(gulp.dest(wpPath + 'js'))
    .pipe($.size({title: 'scripts'}));
});

gulp.task('serve', function() {

  browserSync.init({
    proxy: "localhost:8000",
  });

  gulp.watch(wpPath + 'sass/**/*.scss',  gulp.series('styles')).on('change', browserSync.reload);
  // gulp.watch(wpPath + 'js/scripts/*.js', gulp.series('scripts')).on('change', browserSync.reload);
  gulp.watch(wpPath + 'images/*.{svg,png,jpg,gif}',  gulp.series('images')).on('change', browserSync.reload);
});

gulp.task('start', gulp.series(
  'styles',
  'scripts',
  'images',
  'serve'));

gulp.task('default', gulp.series(
  'styles',
  'scripts',
  'images'));
