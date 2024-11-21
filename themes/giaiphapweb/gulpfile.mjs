'use strict';
// .env
import dotenv from 'dotenv';
// Gulp
import { task, watch, src, parallel, dest } from 'gulp';
// Utilities
import rename from 'gulp-rename';
import sourcemaps from 'gulp-sourcemaps';
import fancyLog from 'fancy-log';
import sftp from 'gulp-sftp-up4';
import changed from 'gulp-changed';
// Css related
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import autoprefixer from 'gulp-autoprefixer';
// JS related
import browserify from 'browserify';
import source from 'vinyl-source-stream';
import watchify from 'watchify';
import buffer from 'vinyl-buffer';
import terser from 'gulp-terser';
import Babelify from 'babelify';
// Browser-sync
// import browserSync from 'browser-sync';

// Initialize .env
// dotenv.config();

// SFTP Config
// const sftpConfig = {
//   host: process.env.HOST,
//   user: process.env.USER,
//   pass: process.env.PASSWORD,
//   port: process.env.PORT,
//   remotePath: process.env.REMOTE_PATH,
// };

const sass = gulpSass(dartSass);
const paths = {
  style: {
    src: 'src/scss/export',
    export: 'src/scss/export/*.scss',
    dest: './assets/css',
    watch: 'src/scss/**/*.scss',
    // sftp: { ... sftpConfig, remotePath: `${sftpConfig.remotePath}/assets/css` },
  },
  script: {
    // src: 'src/ts/export/',
    src: 'src/js/export/',
    entries: [],
    dest: './assets/js',
    // watch: 'src/ts/**/*.ts',
    watch: 'src/js/**/*.js',
    // sftp: { ... sftpConfig, remotePath: `${sftpConfig.remotePath}/assets/js` },
  },
  php: {
    watch: '**/*.php',
  }
};
// Browser-sync
// function initBrowserSync() {
//   browserSync.init({
//     open: false,
//     injectChanges: true,
//     proxy: 'https://hosttot.vn/',
//     listen: '103.21.220.71',
//   });
// }

// function reload(done) {
//   browserSync.reload();
//   done();
// }

function compileScss() {
  return src(paths.style.export)
    .pipe(changed(paths.style.dest, {extension: '.min.css'}))
    .pipe(sourcemaps.init()) // Initialize sourcemaps before any transformations
    .pipe(
      sass({
        errorLogToConsole: true,
        outputStyle: 'compressed',
      })
    )
    .on('log', fancyLog)
    .pipe(
      autoprefixer({
        cascade: false,
      })
    )
    .pipe(rename({ suffix: '.min' }))
    .pipe(
      sourcemaps.write('', {
        includeContent: false,
        sourceRoot: `../../${paths.style.src}`, // This is the path to the source file in the sourcemap
      })
    ) // Write sourcemaps after transformations, before saving the output
    .pipe(dest(paths.style.dest))
    // .pipe(sftp(paths.style.sftp));
    // .pipe(browserSync.stream());
}
function bundleJS() {
  paths.script.entries.map(entry => {
    const watchedBrowserify = watchify(
      browserify({
        basedir: paths.script.src,
        debug: true,
        // entries: [`${entry}.ts`],
        entries: [`${entry}.js`],
        cache: {},
        packageCache: {},
      })
    )
      .on('log', fancyLog)
      // .plugin('tsify')
      .transform(
        Babelify.configure({
          // extensions: ['.ts'],
          extensions: ['.js'],
          presets: ['@babel/preset-env'],
        })
      ); // Use tsify plugin to compile TypeScript
    function reBundle() {
      return watchedBrowserify
        .bundle()
        .pipe(source(`${entry}.min.js`))
        .pipe(buffer())
        .pipe(changed(paths.script.dest, {extension: '.min.js'}))
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(terser()) // Minify the output
        .pipe(
          sourcemaps.write('', {
            includeContent: false,
            sourceRoot: `../../${paths.script.src}`, // This is the path to the source file in the sourcemap
          })
        )
        .pipe(dest(paths.script.dest))
        // .pipe(sftp(paths.script.sftp));
        // .pipe(browserSync.stream());
    }
    watchedBrowserify.on('update', reBundle);
    return reBundle();
  });
}

function watchFiles() {
  watch(paths.style.watch, compileScss);
  bundleJS();

  // run command sftp sync local->remote of vscode
  // watch(paths.php.watch, { events: 'change' }, sftpSync); // Reload on PHP file changes
}
// Define gulp tasks
// task('browser-sync', initBrowserSync);
task('style', compileScss);
task('script', bundleJS);
task('watch', watchFiles);
task('default', parallel(/*'browser-sync',*/ 'style', 'script', 'watch'));
