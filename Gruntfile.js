/*
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package WPCFG
 * @author Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license GPL-2.0+
 * @see https://www.typist.tech/projects/wp-cloudflare-guard
 * @see https://wordpress.org/plugins/wp-cloudflare-guard/
 */

module.exports = function ( grunt ) {

	'use strict';

	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: '<%= pkg.name %>'
			},
			target: {
				files: {
					src: [
						'*.php',
						'**/*.php',
						'!bin/**',
						'!build/**',
						'!node_modules/**',
						'!release/**',
						'!tests/**',
						'!tmp/**',
						'!vendor/**'
					]
				}
			}
		},

		makepot: {
			target: {
				options: {
					domainPath: '/src/languages',
					exclude: ['bin/.*', 'build/*', 'node_modules/.*', 'release/*', 'tests/.*', 'tmp/.*', 'vendor/.*'],
					mainFile: '<%= pkg.name %>.php',
					potFilename: '<%= pkg.name %>.pot',
					potHeaders: {
						poedit: true,
						'Project-Id-Version': '<%= pkg.name %> <%= pkg.version %>',
						'language-team': '<%= pkg.pot.languageteam %>',
						'last-translator': '<%= pkg.pot.lasttranslator %>',
						'report-msgid-bugs-to': '<%= pkg.pot.reportmsgidbugsto %>'
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},

		// Bump version numbers
		version: {
			composer: {
				options: {
					prefix: '"version"\\:\\s+"'
				},
				src: ['composer.json']
			},
			changelog: {
				options: {
					prefix: 'future-release='
				},
				src: ['.github_changelog_generator']
			},
			php: {
				options: {
					prefix: '\\* Version:\\s+'
				},
				src: ['<%= pkg.name %>.php']
			}
		},

		// Clean the build folder
		clean: {
			"pre-build": {
				src: [
					'build/',
					'release/',
					'vendor/'
				]
			},
			"post-build": {
				src: [
					'build/',
					'vendor/'
				]
			}
		},

		// Copy to build folder
		copy: {
			build: {
				expand: true,
				src: [
					'src/**',
					'vendor/**',
					'LICENSE',
					'README.txt',
					'uninstall.php',
					'<%= pkg.name %>.php'
				],
				dest: 'build/'
			}
		},

		compress: {
			build: {
				options: {
					archive: 'release/<%= pkg.name %>.zip'
				},
				expand: true,
				dest: '<%= pkg.name %>/',
				cwd: 'build/',
				src: ['**']
			}
		},

		replace: {
			namespace_wpbs: {
				options: {
					patterns: [
						{
							match: "namespace WPBS",
							replacement: "namespace WPCFG\\Vendor"
						},
						{
							match: "use WPBS",
							replacement: "use WPCFG\\Vendor"
						}
					],
					usePrefix: false
				},
				files: [
					{
						expand: true,
						src: ['vendor/typisttech/wp-better-settings/src/**']
					}
				]
			},
			namespace_cloudflare: {
				options: {
					patterns: [
						{
							match: "namespace Cloudflare",
							replacement: "namespace WPCFG\\Vendor\\Cloudflare"
						},
						{
							match: "use Cloudflare",
							replacement: "use WPCFG\\Vendor\\Cloudflare"
						},
						{
							match: "namespace CloudFlare",
							replacement: "namespace WPCFG\\Vendor\\CloudFlare"
						},
						{
							match: "use CloudFlare",
							replacement: "use WPCFG\\Vendor\\CloudFlare"
						}
					],
					usePrefix: false
				},
				files: [
					{
						expand: true,
						src: [
							'vendor/cloudflare/cf-ip-rewrite/src/**',
							'vendor/jamesryanbell/cloudflare/src/**',
							'vendor/typisttech/cloudflare-wp-api/src/**'
						]
					}
				]
			}
		}

	} );

	require( 'load-grunt-tasks' )( grunt );
	grunt.registerTask( 'replace_namespaces', ['replace'] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'pre-tag', ['version', 'i18n'] );
	grunt.registerTask( 'pre-build', ['clean:pre-build'] );
	grunt.registerTask( 'post-build', ['clean:post-build'] );
	grunt.registerTask( 'build', ['copy:build', 'compress:build'] );

	grunt.util.linefeed = '\n';

};
