'use strict';

var gulp = require('./node_modules/gulp');
var sass = require('./node_modules/gulp-sass');
var uglify = require('./node_modules/gulp-uglify');
var pump = require('./node_modules/pump');
var rename = require('./node_modules/gulp-rename');

//sass
gulp.task('sass', function () {
	gulp.src(['assets/sass/*.scss', 'assets/dist/css/**/*.scss'])
	        .pipe(sass({outputStyle: 'compressed'}))
	        .pipe(gulp.dest('assets/dist/css'));
});

//js
gulp.task('js', function (cb) {
	pump([
			gulp.src('assets/js/*.js'),
			uglify(),
			rename({
				suffix: '.min'
			}),
			gulp.dest('assets/dist/js')
		],
		cb
	);
});

// Default task
gulp.task('default', function () {
	gulp.start('sass');
	gulp.start('js');
});