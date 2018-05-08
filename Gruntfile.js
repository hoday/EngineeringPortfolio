'use strict';

module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({

		// Load grunt project configuration
		pkg: grunt.file.readJSON('package.json'),
        deployTargetLocal: 'C:/xampp/htdocs/wp-hodaystearnsdev/wp-content/plugins/excellent-engineering-portfolio',

		// Configure less CSS compiler
		less: {
			build: {
				options: {
					ieCompat: true
				},
				files: {
					'assets/css/admin.css': 'assets/css/less/admin.less',
				}
			},
		},

        sass: {
			build: {
				files: {
                    /*
                    expand: true,
                    cwd: 'assets/css/sass',
                    src: ['*.scss'],
                    dest: 'assets/css',
                    ext: '.css',
                    */
                    'assets/css/base.css': 'assets/css/sass/base.scss',
                    'assets/css/admin.css': 'assets/css/sass/admin.scss',

				},
			},
		},

		// Configure JSHint
		jshint: {
			test: {
				src: 'assets/js/src/**/*.js'
			}
		},

		// Concatenate scripts
		concat: {
			build: {
				files: {
					'assets/js/admin.js': [
						'assets/js/src/admin.js',
					],
				}
			}
		},

		// Watch for changes on some files and auto-compile them
		watch: {
			less: {
				files: 'assets/css/less/*.less',
				tasks: ['less'],
			},
			js: {
				files: 'assets/js/src/*.js',
				tasks: ['jshint', 'concat'],
			}
		},

		// Create a .pot file
		makepot: {
			target: {
				options: {
					domainPath: 'languages',
					potHeaders: {
	                    poedit: true,
	                    'x-poedit-keywordslist': true
	                },
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'https://';
						return pot;
					},
					type: 'wp-plugin',
				}
			}
		},

		// Build a package for distribution
		compress: {
			main: {
				options: {
					archive: './release/excellent-engineering-portfolio-<%= pkg.version %>.zip'
				},
				files: [
					{
						src: [
							'*',
                            '**/*',
							'!.*',
                            '!Gruntfile.js',
                            '!package.json',
                            '!package-lock.json',
                            '!node_modules',
                            '!node_modules/**/*',
							'!assets/css/less',
                            '!assets/css/less/**/*',
							'!assets/js/src',
                            '!assets/js/src/**/*',
                            '!release',
                            '!release/**/*'
						],
					}
				]
			}
		},

        copy: {
            releasepackage: {
                files: [{
                    expand: true,
                    cwd: './release/',
                    src: ['excellent-engineering-portfolio-<%= pkg.version %>.zip'],
                    dest: '<%= deployTargetLocal %>/'
                }],
            },
            src: {
                files: [{
                      expand: true,
                      cwd: './',
                      src: [
                              '*.php',
                              '**/*.php',
                              './assets/js/admin.js',
                              './assets/css/*.css',
                      ],
                      dest: '<%= deployTargetLocal %>/'
                  }],
              },
        },

        unzip: {
          '<%= deployTargetLocal %>': '<%= deployTargetLocal %>/excellent-engineering-portfolio-<%= pkg.version %>.zip'
        },

        clean: {
            releasepackage: {
                options: { force: true },
                files: [{
                    expand: true,
                    cwd: '<%= deployTargetLocal %>/',
                    src: ['excellent-engineering-portfolio-<%= pkg.version %>.zip'],
                }],
            },
        },

	});


    grunt.registerTask('build', [], function() {

    	grunt.loadNpmTasks('grunt-contrib-concat');
    	grunt.loadNpmTasks('grunt-contrib-jshint');
    	//grunt.loadNpmTasks('grunt-contrib-less');
        grunt.loadNpmTasks('grunt-contrib-sass');
    	grunt.loadNpmTasks('grunt-wp-i18n');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'newer:sass',
            'newer:jshint',
            'newer:concat',
            'newer:makepot',
        );
    });

    grunt.registerTask('quickbuild', [], function() {

        grunt.loadNpmTasks('grunt-contrib-concat');
        //grunt.loadNpmTasks('grunt-contrib-less');
        grunt.loadNpmTasks('grunt-contrib-sass');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'sass',
            'newer:concat',
        );
    });

    grunt.registerTask('package', [], function() {

        grunt.loadNpmTasks('grunt-contrib-compress');

        grunt.task.run(
            'build',
            'newer:compress',
        );
    });

    grunt.registerTask('deploylocal', [], function() {

        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'quickbuild',
            'newer:copy:src',
        );
    });

    grunt.registerTask('copyphp', [], function() {

        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'newer:copy:src',
        );
    });

/*
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-zip');
    grunt.loadNpmTasks('grunt-contrib-clean');
    */

	//grunt.registerTask('build', ['less', 'jshint', 'concat', 'makepot']);
	//grunt.registerTask('package', ['build', 'compress']);
    //grunt.registerTask('deploy', ['compress', 'copy:releasepackage', 'unzip', 'clean:releasepackage']);
    //grunt.registerTask('deploylocal', ['copy:src']);

};
