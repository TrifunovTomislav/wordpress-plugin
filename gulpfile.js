// Load Gulp...of course
var gulp = require("gulp");

// CSS related plugins
var sass = require("gulp-sass");
var autoprefixer = require("gulp-autoprefixer");
var minifycss = require("gulp-uglifycss");

// JS related plugins
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var babelify = require("babelify");
var browserify = require("browserify");
var source = require("vinyl-source-stream");
var buffer = require("vinyl-buffer");
var stripDebug = require("gulp-strip-debug");

// Utility plugins
var rename = require("gulp-rename");
var sourcemaps = require("gulp-sourcemaps");
var notify = require("gulp-notify");
var plumber = require("gulp-plumber");
var options = require("gulp-options");
var gulpif = require("gulp-if");

// Browers related plugins
var browserSync = require("browser-sync").create();
var reload = browserSync.reload;

// Project related variables
var projectURL = "https://test.dev";

var styleSRC = "src/scss/sparkyStyle.scss";
var styleForm = "src/scss/form.scss";
var styleSlider = "src/scss/slider.scss";
var styleAuth = "src/scss/auth.scss";
var styleURL = "./assets/";
var mapURL = "./";

var jsSRC = "src/js/";
var jsAdmin = "sparkyScripts.js";
var jsForm = "form.js";
var jsSlider = "slider.js";
var jsAuth = "auth.js";
var jsFiles = [jsAdmin, jsForm, jsSlider, jsAuth];
var jsURL = "./assets/";

var styleWatch = "src/scss/**/*.scss";
var jsWatch = "src/js/**/*.js";
var phpWatch = "./**/*.php";

// Tasks
// gulp.task("browser-sync", function() {
//   browserSync.init({
//     server: {
//       baseDir: "./"
//     },

// proxy: projectURL,
// https: {
//   key: "/Users/alecaddd/.valet/Certificates/test.dev.key",
//   cert: "/Users/alecaddd/.valet/Certificates/test.dev.crt"
// },
// injectChanges: true,
// open: false
//   });
// });

function a() {
  return gulp
    .src([styleSRC, styleForm, styleSlider, styleAuth])
    .pipe(sourcemaps.init())
    .pipe(
      sass({
        errLogToConsole: true,
        outputStyle: "compressed"
      })
    )
    .on("error", console.error.bind(console))
    .pipe(
      autoprefixer({ browsers: ["last 2 versions", "> 5%", "Firefox ESR"] })
    )
    .pipe(sourcemaps.write(mapURL))
    .pipe(gulp.dest(styleURL))
    .pipe(browserSync.stream());
}

gulp.task("styles", gulp.series(a));

gulp.task("js", function() {
  jsFiles.map(function(entry) {
    return browserify({
      entries: [jsSRC + entry]
    })
      .transform(babelify, { presets: ["env"] })
      .bundle()
      .pipe(source(entry))
      .pipe(buffer())
      .pipe(gulpif(options.has("production"), stripDebug()))
      .pipe(sourcemaps.init({ loadMaps: true }))
      .pipe(uglify())
      .pipe(sourcemaps.write("."))
      .pipe(gulp.dest(jsURL))
      .pipe(browserSync.stream());
  });
});

function triggerPlumber(src, url) {
  return gulp
    .src(src)
    .pipe(plumber())
    .pipe(gulp.dest(url));
}

//gulp.task("default", gulp.series("styles", "js"));

// "browser-sync",

// gulp.task(
//   "watch",
//   gulp.series("default", function() {
//     gulp.watch(phpWatch, reload);
//     gulp.watch(styleWatch, ["styles"]);
//     gulp.watch(jsWatch, ["js", reload]);
//     gulp
//       .src(jsURL + "sparkyScripts.js")
//       .pipe(notify({ message: "Gulp is Watching, Happy Coding!" }));
//   })
// );

gulp.task("watch", function() {
  gulp.watch(phpWatch, reload);
  gulp.watch(styleWatch, gulp.series("styles"));
  gulp.watch(jsWatch, gulp.series("js"));
});