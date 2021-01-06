module.exports = function( grunt ) {
	'use strict';
	var sass = require( 'node-sass' );

	grunt.initConfig({

		// Setting folder templates.
		dirs: {
			dist_css: 'assets/css',
			dist_js: 'assets/js',
			src_scss: 'assets/src/scss',
			src_js: 'assets/src/js'
		},

		// Validate .js files with JSHint.
		jshint: {
			files: [
				'<%= dirs.src_js %>/admin/*.js',
				'<%= dirs.src_js %>/*.js'
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
					cwd: '<%= dirs.src_js %>/admin/',
					src: [ '*.js' ],
					dest: '<%= dirs.dist_js %>/admin/'
				}]

			},
			src: {
				files: [{
					expand: true,
					cwd: '<%= dirs.src_js %>',
					src: [ '*.js' ],
					dest: '<%= dirs.dist_js %>'
				}]
			}
		},

		// Compile all .scss files.
		sass: {
			compile: {
				options: {
					implementation: sass,
					sourceMap: 'none'
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.src_scss %>/',
					src: ['*.scss'],
					dest: '<%= dirs.dist_css %>/',
					ext: '.css'
				}]
			}
		},

		// Minify all .css files.
		cssmin: {
			minify: {
				files: [
					{
						expand: true,
						cwd: '<%= dirs.dist_css %>/',
						src: ['*.css'],
						dest: '<%= dirs.dist_css %>/',
						ext: '.css'
					}
				]
			}
		},

		// Watch changes for assets.
		watch: {
			js: {
				files: [
					'GruntFile.js',
					'<%= dirs.src_js %>/admin/*.js',
					'<%= dirs.src_js %>/*.js',
					'!<%= dirs.dest_js %>/admin/*.js',
					'!<%= dirs.dest_js %>/*.js',
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
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	// Register tasks.
	grunt.registerTask( 'default', [
		'js',
		'css'
	]);

	grunt.registerTask( 'js', [
		'jshint',
		'uglify:admin',
		'uglify:src'
	]);

	grunt.registerTask( 'css', [
		'sass','cssmin'
	]);

	grunt.registerTask( 'assets', [
		'js',
		'css'
	]);
};
