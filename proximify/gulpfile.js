const { src, dest } = require('gulp');
const sass = require('gulp-sass');


//Todo gulp watch, minify
exports.default = function() {
    return src('assets/css/self.scss')
        .pipe(sass({
            errorLogToConsole: true
        })).on('error', sass.logError)
        .pipe(dest('www/assets/css/'));
}

//我把鼠标权限给你了哈，我也去上个卫生间