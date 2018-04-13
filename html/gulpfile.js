/**
 * Created by gugr on 08.06.2017.
 */

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass'),
    rigger = require('gulp-rigger'),
    cssmin = require('gulp-minify-css'),
    spritesmith = require('gulp.spritesmith');

var path = {
    build: {
        js: "js",
        css: "css",
        img: "img"
    },
    src: {
        js: "source/js/main.js",
        css: "source/scss/*.scss",
        spriteScss: "source/scss",
        img: "source/images/**/*.*",
        icons: "source/images/icons/*.*"
    },
    watch: {
        js: "source/js/*.js",
        css: "source/scss/*.scss",
        img: "source/images"
    }
}

var sassOpts = {
    outputStyle: "nested",
    precision: 3,
    errLogToConsole: true,
    includePaths: ['./node_modules/compass-sass-mixins/lib']
}

var config = {
    server: {
        baseDir: "/",
        host: 'getbike.local'
    }
}

gulp.task('sprite', function() {
    var spriteData =
        gulp.src(path.src.icons) // путь, откуда берем картинки для спрайта
            .pipe(spritesmith({
                imgName: 'sprite.png',
                cssName: 'sprite.scss',
                padding: 5,
            }));

    spriteData.img.pipe(gulp.dest(path.build.css)); // путь, куда сохраняем картинку
    spriteData.css.pipe(gulp.dest(path.src.spriteScss)); // путь, куда сохраняем стили
});

gulp.task('sass_s', function () {
    return gulp.src(path.src.css)
        .pipe(sass(sassOpts))
        .on('error', function(err){
            console.log(err.toString());
            this.emit('end');
        })
        .pipe(prefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(path.build.css));
});

// Задача "js". Запускается командой "gulp js"
gulp.task('js', function() {
    gulp.src(path.src.js) // файлы, которые обрабатываем
        .pipe(uglify()) // получившуюся "портянку" минифицируем
        .pipe(gulp.dest(path.build.js)) // результат пишем по указанному адресу
});

// Задача "images". Запускается командой "gulp images"
gulp.task('images', function() {
    gulp.src(path.src.img) // берем любые файлы в папке и ее подпапках
        // .pipe(imagemin()) // оптимизируем изображения для веба
        .pipe(gulp.dest(path.build.img)) // результат пишем по указанному адресу

});

// Задача "watch". Запускается командой "gulp watch"
// Она следит за изменениями файлов и автоматически запускает другие задачи
gulp.task('watch', function () {
    // При изменение файлов *.scss в папке "styles" и подпапках запускаем задачу sass
    gulp.watch(path.watch.css, ["sass_s"]);
    // При изменение файлов *.js папке "javascripts" и подпапках запускаем задачу js
    // gulp.watch(path.watch.js, ["js"]);
    // При изменение любых файлов в папке "images" и подпапках запускаем задачу images
    // gulp.watch(path.watch.img, ['images']);
});

gulp.task('default', ['watch']);
