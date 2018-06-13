'use strict';

module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({

		// Load grunt project configuration
		pkg: grunt.file.readJSON('package.json'),
        deployTargetLocal: 'C:/xampp/htdocs/wp-hodaystearnsdev2/wp-content/plugins/electrifying-engineering-portfolio',

        sass: {
			build: {
                options: {
                  sourcemap: 'none'
                },
				files: [{
                    expand: true,
                    cwd: 'assets/css/sass/',
                    src: '**/*.scss',
                    dest: 'assets/css/',
                    ext: '.css'
				}],
			},
		},

		// Configure JSHint
		jshint: {
			build: {
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

		// Create a .pot file
		makepot: {
			build: {
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
							'!assets/js/src',
                            '!assets/js/src/**/*',
                            '!release',
                            '!release/**/*'
						],
					}
				]

			}
		},

		// Build a package for distribution
		compress: {
            build: {
                options: {
                    archive: 'electrifying-engineering-portfolio-<%= pkg.version %>.zip'
                },
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: '**/*'
                }]
            }
		},

        copy: {
            build: {
                files: [{
                    expand: true,
                    cwd: './',
                    src: [
                        '*.php',
                        'readme.txt',
                        'views/**/*.php',
                        'includes/**/*.php',
                        'eep-templates/**/*.php',
                        'languages/**/*',
                        'assets/**/*',
                        '!assets/js/src',
                        '!assets/js/src/**/*',
                        '!assets/css/sass',
                        '!assets/css/sass/**/*'
                    ],
                    dest: 'build/'
                }],
            },
            deployphp: {
                expand: true,
                cwd: './',
                src: [
                    '*.php',
                    'views/*.php',
                    'views/**/*.php',
                    'includes/*.php',
                    'includes/**/*.php',
                    'eep-templates/*.php',
                    'eep-templates/**/*.php'
                ],
                dest: '<%= deployTargetLocal %>/'
            },
            deploycss: {
                files: [{
                    expand: true,
                    cwd: './',
                    src: './assets/css/**/*.css',
                    dest: '<%= deployTargetLocal %>/'
                }],
            },
            deployother: {
                files: [{
                    expand: true,
                    cwd: './',
                    src: [
                        'readme.txt',
                        'languages/**/*',
                        'assets/**/*',
                        '!assets/js/src',
                        '!assets/js/src/**/*',
                        '!assets/css/sass',
                        '!assets/css/sass/**/*'
                    ],
                    dest: '<%= deployTargetLocal %>/'
                }]
            }
        },

        unzip: {
          '<%= deployTargetLocal %>': '<%= deployTargetLocal %>/electrifying-engineering-portfolio-<%= pkg.version %>.zip'
        },

        clean: {
            releasepackage: {
                options: { force: true },
                files: [{
                    expand: true,
                    cwd: '<%= deployTargetLocal %>/',
                    src: ['electrifying-engineering-portfolio-<%= pkg.version %>.zip'],
                }],
            },
        },

        deploy: {
          php: [1, 2, 3],
          css: 'hello world',
        }

	});

    grunt.registerTask('makepot', 'run makepot', function() {

        grunt.loadNpmTasks('grunt-wp-i18n');

        grunt.task.run(
            'makepot:build',
        );
    });

    grunt.registerTask('build', 'build project into the directory "build"', function() {

        grunt.loadNpmTasks('grunt-contrib-sass');
    	grunt.loadNpmTasks('grunt-contrib-concat');
    	grunt.loadNpmTasks('grunt-contrib-jshint');
    	grunt.loadNpmTasks('grunt-wp-i18n');
        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'newer:sass:build',
            'newer:jshint:build',
            'newer:makepot:build',
            'newer:copy:build'
        );
    });

    grunt.registerTask('quickbuild', 'build without running jshint or makepot', function() {

        grunt.loadNpmTasks('grunt-contrib-concat');
        grunt.loadNpmTasks('grunt-contrib-sass');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'newer:sass:build',
        );
    });

    grunt.registerTask('package', 'create zip file of build directory', function() {

        grunt.loadNpmTasks('grunt-contrib-compress');

        grunt.task.run(
            'build',
            'compress:build',
        );
    });

    grunt.registerTask('deploylocal', 'build and deploy to test server', function() {

        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'quickbuild',
            'newer:copy:deployphp',
            'newer:copy:deploycss',
            'newer:copy:deployother',

        );
    });

    grunt.registerTask('copyphp', 'copy php only to test server', function() {

        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');

        grunt.task.run(
            'copy:deployphp',
        );
    });

    grunt.registerTask('sasscopycss', 'run sass and copy sass only to test server', function() {

        grunt.loadNpmTasks('grunt-contrib-copy');
        grunt.loadNpmTasks('grunt-newer');
        grunt.loadNpmTasks('grunt-contrib-sass');


        grunt.task.run(
            'newer:sass:build',
            'newer:copy:deploycss',
        );
    });

    grunt.task.registerMultiTask('deploy', 'Deploy to the local server. deploy:php or deploy:css or just deploy', function() {
        if (this.target === 'css') {

            grunt.loadNpmTasks('grunt-contrib-sass');
            grunt.loadNpmTasks('grunt-contrib-copy');
            grunt.loadNpmTasks('grunt-newer');

            grunt.task.run(
                'newer:sass:build',
                'newer:copy:deploycss',
            );

        } else if (this.target === 'php' ) {

            grunt.loadNpmTasks('grunt-contrib-copy');
            grunt.loadNpmTasks('grunt-newer');

            grunt.task.run(
                'newer:copy:deployphp',
            );
        }
    });



};
