'use strict';

var autoprefixer = require('gulp-autoprefixer'),
    browserSync = require('browser-sync').create(),
    cached = require('gulp-cached'),
    changed = require('gulp-changed'),
    concat = require('gulp-concat'),
    copy = require('gulp-copy'),
    filter = require('gulp-filter'),
    gulp = require('gulp'),
    gulpif = require('gulp-if'),
    minifyCss = require('gulp-minify-css'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    reload = browserSync.reload,
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch');


gulp.task('css', function() {
    return gulp.src('./app/src/styles/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulpif(global.isWatching, cached('css')))
        .pipe(autoprefixer({
            browsers: ['and_chr > 42', 'and_uc 9.9',
                'chrome > 30', 'Firefox > 26',
                'ie > 8', 'ie_mob 11',
                'opera > 20', 'safari > 6'
            ],
            cascade: false
        }))
        .pipe(gulp.dest('./app/dist/styles/'));
});


gulp.task('cssMin', function() {
    return gulp.src('./app/src/styles/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(minifyCss({
            compatibility: 'ie8'
        }))
        .pipe(autoprefixer({
            browsers: ['and_chr > 42', 'and_uc 9.9',
                'chrome > 30', 'Firefox > 26',
                'ie > 8', 'ie_mob 11',
                'opera > 20', 'safari > 6'
            ],
            cascade: false
        }))
        .pipe(gulpif(global.isWatching, cached('cssMin')))
        .pipe(rename({
            extname: ".min.css"
        }))
        .pipe(gulp.dest('./app/dist/styles/'));
});


gulp.task('js', function() {
    return gulp.src('./app/src/scripts/**/*.js')
        .pipe(uglify())
        .pipe(gulpif(global.isWatching, cached('js')))
        .pipe(rename({
            extname: ".min.js"
        }))
        .pipe(gulp.dest('./app/dist/scripts/'));
});


gulp.task('jsCopy', function() {
    return gulp.src(['./app/src/scripts/**/*.js'])
        .pipe(gulpif(global.isWatching, cached('jsCopy')))
        .pipe(gulp.dest('./app/dist/scripts/'));
});


gulp.task('imgCopy', function() {
    return gulp.src(['./app/src/images/**/*'])
        .pipe(gulpif(global.isWatching, cached('imgCopy')))
        .pipe(gulp.dest('./app/dist/images/'));
});


gulp.task('phpCopy', function() {
    return gulp.src(['./app/src/**/*.php'])
        .pipe(gulpif(global.isWatching, cached('phpCopy')))
        .pipe(gulp.dest('./app/dist/'));
});


gulp.task('fontCopy', function() {
    return gulp.src(['./app/src/styles/fonts/*.ttf'])
        .pipe(gulpif(global.isWatching, cached('fontCopy')))
        .pipe(gulp.dest('./app/dist/styles/fonts/'));
});


gulp.task('htaccessCopy', function() {
    return gulp.src(['./app/src/.htaccess'])
        .pipe(gulpif(global.isWatching, cached('htaccessCopy')))
        .pipe(gulp.dest('./app/dist/'));
});


gulp.task('sqlCopy', function() {
    return gulp.src(['./app/src/.sql'])
        .pipe(gulpif(global.isWatching, cached('sqlCopy')))
        .pipe(gulp.dest('./app/dist/'));
});


gulp.task('bowerCopy', function() {
    return gulp.src(['./app/src/bower_components/**/*'], {
            dot: true
        })
        .pipe(gulp.dest('./app/dist/bower_components/'));
});


gulp.task('serve', ['css', 'cssMin', 'js', 'jsCopy', 'imgCopy', 'phpCopy', 'fontCopy', 'htaccessCopy', 'sqlCopy', 'bowerCopy'], function() {
    browserSync.init({
        notify: false,
        logPrefix: 'DVSG',
        server: ['./app/dist']
    });

    gulp.watch(['app/src/styles/**/*.scss'], ['css', reload]);
    gulp.watch(['app/src/styles/**/*.scss'], ['cssMin', reload]);
    gulp.watch(['app/src/scripts/**/*.js'], ['js', reload]);
    gulp.watch(['app/src/scripts/**/*.js'], ['jsCopy', reload]);
    gulp.watch(['app/src/images/**/*'], ['imgCopy', reload]);
    gulp.watch(['app/src/**/*.php'], ['phpCopy', reload]);
    gulp.watch(['app/src/styles/fonts/*.ttf'], ['fontCopy', reload]);
    gulp.watch(['app/src/.htaccess'], ['htaccessCopy', reload]);
    gulp.watch(['app/src/.sql'], ['sqlCopy', reload]);
    gulp.watch(['app/src/bower_components/**/*'], ['bowerCopy', reload]);

});


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//																																//
//		--CONCAT--																												//
//		gulp.task('js', function() {																							//
//			gulp.src(['./js/1.js', './js/2.js', './js/3.js'])																	//
//				.pipe(concat('libs.js'))																						//
//				.pipe(gulp.dest('./dist/js/'))																					//
//		});																														//																			
//																																//
// 		CORRER VARIAS TAREAS AL MISMO TIEMPO																					//
//		gulp.task('tareas varias o default (solo escribo GULP)', ['concat-js', 'css']);											//
// 		js/source/1.js coincide exactamente con el archivo.																		//
// 		js/source/*.js coincide con los archivos que terminen en .js dentro de la carpeta js/source.							//
// 		js/**/*.js coincide con los archivos que terminen en .js dentro de la carpeta js y dentro de todas sus sub-carpetas.	//
// 		!js/source/3.js Excluye especificamente el archivo 3.js.																//
// 		static/*.+(js|css) coincide con los archivos que terminen en .js ó .css dentro de la carpeta static/					//
//																																//
//																																//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////