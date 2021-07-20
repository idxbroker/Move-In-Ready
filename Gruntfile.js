module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        includePaths: ['scss/partials', '../equity/foundation/scss', '../equity/foundation/scss/foundation/components']
      },
      dist: {
        options: {
          outputStyle: 'expanded',
          lineNumbers: true
        },
        files: {
          'style-editor.css': 'scss/style-editor.css.scss',
          'style.css': 'scss/style.css.scss'
        }
      }
    },

    wpcss: {
        target: {
            options: {
                commentSpacing: true, // Whether to clean up newlines around comments between CSS rules.
                config: 'default',    // Which CSSComb config to use for sorting properties.
            },
            files: {
              src: 'style.css', dest: 'style-clean.css'
            }
        }
    },

    makepot: {
        target: {
            options: {
                cwd: '',                          // Directory of files to internationalize.
                domainPath: '/languages',         // Where to save the POT file.
                potComments: '',                  // The copyright at the beginning of the POT file.
                potFilename: '',                  // Name of the POT file.
                potHeaders: {
                    poedit: true,                 // Includes common Poedit headers.
                    'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                },                                // Headers to add to the generated POT file.
                processPot: null,                 // A callback function for manipulating the POT file.
                type: 'wp-theme',                 // Type of project (wp-plugin or wp-theme).
                updateTimestamp: true             // Whether the POT-Creation-Date should be updated without other changes.
            }
        }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['sass']
      }
    }

  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-wp-css');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['sass', 'wpcss', 'makepot']);
  grunt.registerTask('default', ['build','watch']);
};