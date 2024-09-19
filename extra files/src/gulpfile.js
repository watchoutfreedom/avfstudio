const gulp = require('gulp');
const prop = require('./package.json');
const minify = require('gulp-clean-css');
const sass = require('gulp-sass');
const pug = require('gulp-pug');
const uglify = require('gulp-uglify');
const image = require('gulp-image');
const $ = require('gulp-load-plugins')();
const concat = require('gulp-concat');
const head =
    '/*\r\n* Cirrus ' +
    prop.version +
    `\r\n* Stanley Lim, Copyright ${new Date().getFullYear()}\r\n* https://spiderpig86.github.io/Cirrus\r\n*/\r\n`;

// COMIPLE CSS

gulp.task('compile', () => {
    return gulp
        .src(['scss/core/default.scss', './scss/**/*.scss'])
        .pipe(sass.sync().on('error', sass.logError))
        .pipe($.concat('styles.css'))
        .pipe($.header(head))
        .pipe($.size())
        .pipe(gulp.dest('../dist/css/'));
});

// MINIMISE AND COMPRESS SYSTEM IMAGES

gulp.task('image', async function() {
    gulp.src('img/**/*')
      .pipe(image())
      .pipe(gulp.dest('../dist/img/'));
});

//  LOOKS FOR ERRORS IN JS ---------

// gulp.task('jshint', function(){
//     gulp.src('js/*.js')
//       .pipe(jshint())
//       .pipe(jshint.reporter('default'));
// });

//  COMPILES JS ---------


gulp.task('scripts', () => {
    return gulp.src('js/**/*.js')
        .pipe(concat('scripts.js'))
        // .pipe(uglify()) 
        .pipe(gulp.dest('../dist/js/')); 
        // [...]
});

// COMPILE HTML

gulp.task('html', () => {
    return gulp.src('html/**/*.pug')
    .pipe(
        pug({
            pretty: true
        })
    )
    .pipe(gulp.dest('../dist/html/'));
});


gulp.task(
    'minify',
    gulp.series('compile', () => {
        return gulp
            .src(['../dist/css/styles.css'])
            .pipe(
                minify(
                    {
                        level: {
                            1: {
                                all: true,
                                normalizeUrls: false,
                            },
                            2: {
                                all: false,
                                removeDuplicateRules: true,
                                reduceNonAdjacentRules: true,
                                removeDuplicateFontRules: true,
                                removeDuplicateMediaBlocks: true,
                                mergeAdjacentRules: true,
                                mergeIntoShorthands: true,
                                mergeMedia: true,
                                mergeNonAdjacentRules: true,
                                mergeSemantically: false,
                                removeEmpty: true,
                            },
                        },
                    },
                    (details) => {
                        console.log('FULL');
                        console.log(details.name + ': ' + details.stats.originalSize);
                        console.log(details.name + '-min: ' + details.stats.minifiedSize);
                    }
                )
            )
            .pipe($.header(head))
            .pipe($.size())
            .pipe($.concat('styles.min.css'))
            .pipe(gulp.dest('../dist/css/'));
    })
);

gulp.task('core', () => {
    return gulp
        .src(['./scss/core/*.scss', './scss/utils/*.scss'])
        .pipe(sass.sync().on('error', sass.logError))
        .pipe($.concat('cirrus-core.css'))
        .pipe($.header(head))
        .pipe($.size())
        .pipe(gulp.dest('../dist/css/'));
});

gulp.task(
    'minify-core',
    gulp.series('core', () => {
        return gulp
            .src(['../dist/css/cirrus-core.css'])
            .pipe(
                minify(
                    {
                        level: {
                            1: {
                                all: true,
                                normalizeUrls: false,
                            },
                            2: {
                                all: false,
                                removeDuplicateRules: true,
                                reduceNonAdjacentRules: true,
                                removeDuplicateFontRules: true,
                                removeDuplicateMediaBlocks: true,
                                mergeAdjacentRules: true,
                                mergeIntoShorthands: true,
                                mergeMedia: true,
                                mergeNonAdjacentRules: true,
                                mergeSemantically: false,
                                removeEmpty: true,
                            },
                        },
                    },
                    (details) => {
                        console.log('CORE');
                        console.log(details.name + ': ' + details.stats.originalSize);
                        console.log(details.name + '-min: ' + details.stats.minifiedSize);
                    }
                )
            )
            .pipe($.header(head))
            .pipe($.size())
            .pipe($.concat('cirrus-core.min.css'))
            .pipe(gulp.dest('../dist/css/'));
    })
);

// gulp.task('watch', () => gulp.watch('./scss/**/*.scss', './js/**/*.js', './html/**/*.pug', gulp.parallel('minify', 'minify-core', 'scripts', 'html')));

gulp.task('watch', function() {
    gulp.watch('./scss/**/*.scss', gulp.series('compile'));
    gulp.watch('./js/**/*.js', gulp.series('scripts'));
    gulp.watch('./html/**/*.pug', gulp.series('html'));
    gulp.watch('./img/*', gulp.series('image'));
});


gulp.task('default', gulp.parallel('minify', 'scripts', 'html', 'image'));
