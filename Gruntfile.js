/*
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package WPCFG
 *
 * @author Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license GPL-2.0+
 *
 * @see https://typist.tech/projects/wp-cloudflare-guard
 * @see https://wordpress.org/plugins/wp-cloudflare-guard/
 */

module.exports = function (grunt) {

    'use strict';

    // Project configuration
    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        addtextdomain: {
            options: {
                textdomain: '<%= pkg.name %>',
                updateDomains: true
            },
            target: {
                files: {
                    src: [
                        '*.php',
                        'lib/**/*.php',
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
                        'lib/.*',
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

        clean: {
            cloudflare: [
                'vendor/jamesryanbell/cloudflare/src/**/*.*',
                '!vendor/jamesryanbell/cloudflare/src/CloudFlare/Exception/**',
                '!vendor/jamesryanbell/cloudflare/src/CloudFlare/*Api.php',
                '!vendor/jamesryanbell/cloudflare/src/CloudFlare/Zone/Firewall/AccessRules.php'
            ],
            "container-interop": [
                'vendor/container-interop/container-interop/docs/**'
            ],
            "cf-ip-rewrite": [
                'vendor/cloudflare/cf-ip-rewrite/tests/**'
            ],
            imposter: [
                'vendor/typisttech/imposter*'
            ],
            vendor: {
                nocase: true,
                src: [
                    'vendor/**/.gitignore',
                    'vendor/**/.gitkeep',
                    'vendor/**/*.xml',
                    'vendor/**/*.yml',
                    'vendor/**/*.dist',
                    'vendor/**/*.md',
                    'vendor/**/*.lock',
                    '!**/*license*',
                    '!vendor/composer/**/*.*',
                    '!vendor/squizlabs/php_codesniffer/**/*.*',
                    '!vendor/wp-coding-standards/wpcs/**/*.*'
                ]
            }
        },

        cleanempty: {
            options: {
                noJunk: true
            },
            vendor: {
                src: ['vendor/**/*']
            }
        }
    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('clean:install', ['clean:cloudflare', 'clean:cf-ip-rewrite', 'clean:container-interop', 'clean:vendor']);

    grunt.util.linefeed = '\n';

};
