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
				textdomain: '<%= pkg.name %>',
				updateDomains: true
			},
			target: {
				files: {
					src: [
						'*.php',
						'src/**/*.php',
						'vendor/**/*.php'
					]
				}
			}
		},

		makepot: {
			target: {
				options: {
					include: [
						'.*.php',
						'src/.*',
						'vendor/.*'
					],
					mainFile: '<%= pkg.name %>.php',
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
			},
			readme: {
				options: {
					prefix: 'Stable tag:\\s+'
				},
				src: ['README.txt']
			}
		},

		replace: {
			namespace_yoast_i18n: {
				options: {
					patterns: [
						{
							match: /<\?php\s+(?!namespace WPCFG\\Vendor;\n)/g,
							replacement: "<?php namespace WPCFG\\Vendor;\n"
						}
					],
					usePrefix: false
				},
				files: [
					{
						expand: true,
						src: ['vendor/yoast/i18n-module/src/**']
					}
				]
			}
		},

		wp_deploy: {
			deploy: {
				options: {
					plugin_slug: '<%= pkg.name %>',
					plugin_main_file: '<%= pkg.name %>.php',
					svn_user: 'tangrufus',
					build_dir: 'build',
					assets_dir: 'assets',
					skip_confirmation: true
				}
			}
		}

	} );

	require( 'load-grunt-tasks' )( grunt );

	grunt.util.linefeed = '\n';

};
