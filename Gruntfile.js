module.exports = function( grunt ) {
	'use strict';

	grunt.initConfig({

		// Setting folder templates.
		dirs: {
			js: 'assets/js'
		},

		// Validate .js files with JSHint.
		jshint: {
			files: [
				'<%= dirs.js %>/src/admin/*.js',
				'<%= dirs.js %>/src/*.js'
			],
			options: {
				expr: true,
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true
				}
			}
		},

		// Minify .js files.
		uglify: {
			admin: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/src/',
					src: [ '*.js' ],
					dest: '<%= dirs.js %>'
				}]
			},
			src: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/src/admin/',
					src: [ '*.js' ],
					dest: '<%= dirs.js %>/admin/'
				}]
			}
		},

		// Watch changes for assets.
		watch: {
			js: {
				files: [
					'GruntFile.js',
					'<%= dirs.js %>/src/admin/*.js',
					'<%= dirs.js %>/src/*.js',
					'!<%= dirs.js %>/admin/*.js',
					'!<%= dirs.js %>/*.js',
				],
				tasks: ['jshint','uglify']
			}
		},

		// PHP Code Sniffer.
		phpcs: {
			options: {
				bin: 'vendor/bin/phpcs'
			},
			dist: {
				src:  [
					'**/*.php', // Include all php files.
					'!node_modules/**',
					'!tmp/**',
					'!vendor/**'
				]
			}
		}
	});

	// Load NPM tasks to be used here.
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	// Register tasks.
	grunt.registerTask( 'default', [ 'js' ]);

	grunt.registerTask( 'js', [
		'jshint',
		'uglify:admin',
		'uglify:src'
	]);
};
