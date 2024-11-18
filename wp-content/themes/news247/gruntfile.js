/* globals module, require,
 |
 |------------------------------------------------------------------------------
 | Grunt Setup
 |------------------------------------------------------------------------------
 |
 | Define Grunt settings and tasks.
 |
 */

 const sass = require('node-sass');

module.exports = function (grunt) {
    "use strict";

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            options: {
                outputStyle: 'compact',
                implementation: sass,
                sourceMap: true,
            },
            browse: {
                files: {
                    // destination : // Source
                    'assets/css/style.css': 'assets/scss/style.scss'
                    
                }
            }
        },
        postcss: {
            options: {
                // Inline sourcemaps
                map: false,
                processors: [
                    require('autoprefixer')
                ]
            },
            dist: {
                // destination : // Source
                src: ['*.css', '*.css']
            }
        },
        concat: {
          options: {
            separator: ';',
            stripBanners: true
          },
          dist: {
            src: [
                  'assets/js/misc/site-search/siteSearch.min.js',
                  'assets/js/misc/bodymovin.js',
                  'assets/js/misc/animations.min.js',
                  ],

            dest: 'assets/js/misc/frontend-misc.min.js',
          }
        },
        watch: {
            styles: {
                files: [ 'assets/scss/*.scss', 'assets/scss/*/*.scss','assets/scss/*/*/*.scss'  ],
                tasks: ['sass', 'postcss'],
                options: {
                    spawn: false
                }
            }
          }
        //}
    });

    // Load the plugins
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');

};
