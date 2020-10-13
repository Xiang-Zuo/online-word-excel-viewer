const { src, dest } = require('gulp');
const sass = require('gulp-sass');

//Todo gulp watch, minify
exports.default = function() {
    return src('assets/scss/self.scss')
        .pipe(sass({
            errorLogToConsole: true
        })).on('error', sass.logError)
        .pipe(dest('www/assets/css/'));
}
