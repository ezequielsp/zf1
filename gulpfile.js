var gulp    = require('gulp');
var es      = require('event-stream');
var gutil   = require('gulp-util');

var plugins = require("gulp-load-plugins")({
    pattern: ['gulp-*', 'gulp.*'],
    replaceString: /\bgulp[\-.]/
});

// Allows gulp --dev to be run for a more verbose output
var isProduction = true;
var sassStyle    = 'compressed';
var cssStyle     = 'compressed';
var sourceMap    = false;

if(gutil.env.dev === true) {
    sassStyle    = 'expanded';
    sourceMap    = true;
    isProduction = false;
}

var basePaths = {
    src: 'public/src/',
    assets: 'public/assets/',
    dest: 'public/dist/',
    bower: 'bower_components/'
};
var paths = {
    images: {
        src: basePaths.src + 'images/',
        dest: basePaths.dest + 'images/'
    },
    scripts: {
        src: basePaths.src + 'js/',
        dest: basePaths.dest + 'js/'
    },
    sass: {
        src: basePaths.src + 'sass/',
        dest: basePaths.src + 'css/'
    },
    css: {
        src: basePaths.src + 'css/',
        dest: basePaths.dest + 'css/'
    },
    sprite: {
        src: basePaths.src + 'sprite/*'
    }
};

var appFiles = {
    sass: paths.sass.src + '**/*.scss',
    css: paths.css.src + '**/*.css',
    scripts: [
        basePaths.assets + 'jquery.min.js', 
        basePaths.assets + 'bootstrap.min.js',
        paths.scripts.src + '*.js'
    ]
};

var vendorFiles = {
    sass: '',
    css: '',
    scripts: ''
};

var spriteConfig = {
    imgName: 'sprite.png',
    cssName: '_sprite.scss',
    imgPath: paths.images.dest + 'sprite.png' // Gets put in the css
};

var changeEvent = function(evt) {
    gutil.log('File', gutil.colors.cyan(evt.path.replace(new RegExp('/.*(?=/' + basePaths.src + ')/'), '')), 'was', gutil.colors.magenta(evt.type));
};

gulp.task('sass', function(){

    var sassFiles = gulp.src(appFiles.sass)
    .pipe(plugins.sass({
        style: sassStyle, sourcemap: sourceMap, precision: 2
    }))
    .on('error', function(err){
        new gutil.PluginError('SASS', err, {showStack: true});
    });

    return es.concat(gulp.src(vendorFiles.sass), sassFiles)
        .pipe(plugins.concat('style.min.css'))
        .pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(isProduction ? plugins.combineMediaQueries({
            log: true
        }) : gutil.noop())
        .pipe(isProduction ? plugins.cssmin() : gutil.noop())
        .pipe(plugins.size())
        .pipe(gulp.dest(paths.sass.dest));
});

gulp.task('css', function() {
    var cssFiles = gulp.src(appFiles.css)
                        .pipe(plugins.csslint())
                        .pipe(plugins.csslint.reporter())
                        .on('error', function(err){
                            new gutil.PluginError('CSS', err, {showStack: true});
                        });
    
    return es.concat(gulp.src(basePaths.assets + 'bootstrap.min.css'), cssFiles)
               .pipe(plugins.concat('style.min.css'))
               .pipe(plugins.cssmin())
               .pipe(gulp.dest(paths.css.dest));
});

gulp.task('scripts', function(){
    gulp.src(appFiles.scripts)
        .pipe(plugins.uglify())
        .on('error', function(err){
            new gutil.PluginError('CSS', err, {showStack: true});
        })
        .pipe(plugins.concat('app.js'))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(plugins.uglify())
        .pipe(plugins.size())
        .pipe(gulp.dest(paths.scripts.dest));      

});

/*
    Sprite Generator
*/
gulp.task('sprite', function () {
    var spriteData = gulp.src(paths.sprite.src).pipe(plugins.spritesmith({
        imgName: spriteConfig.imgName,
        cssName: spriteConfig.cssName,
        imgPath: spriteConfig.imgPath,
        cssVarMap: function (sprite) {
            sprite.name = 'sprite-' + sprite.name;
        }
    }));
    spriteData.img.pipe(gulp.dest(paths.images.dest));
    spriteData.css.pipe(gulp.dest(paths.css.src));
});

gulp.task('watch', ['sprite', 'css', 'scripts'], function(){
    gulp.watch(appFiles.sass, ['sass']).on('change', function(evt) {
        changeEvent(evt);
    });
    gulp.watch(appFiles.css, ['css']).on('change', function(evt) {
        changeEvent(evt);
    });
    gulp.watch(paths.scripts.src + '*.js', ['scripts']).on('change', function(evt) {
        changeEvent(evt);
    });
});

gulp.task('default', ['css', 'scripts']);