/**
 * @project:   Simple Newsletter
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2023 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

var packageName = "bitter_theme";

module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        version: {
            php: {
                options: {
                    pkg: {
                        version: function () {
                            var s = grunt.file.read('controller.php');
                            var re = /\$pkgVersion[\s*]=[\s*][\'|\"](.*)[\'|\"]/g
                            var m = re.exec(s);

                            if (m.length) {
                                return m[1];
                            }

                            return false;
                        }()
                    },
                    prefix: '@version\\s*'
                },
                src: [
                    'dist/*.php', 'dist/**/*.php', 'dist/**/**/*.php', 'dist/**/**/**/*.php', 'dist/**/**/**/**/*.php'
                ]
            }
        },
        composer: {
            options: {
                usePhp: true,
                composerLocation: './node_modules/getcomposer/composer.phar'
            },
            dev: {
                options: {
                    flags: ['ignore-platform-reqs']
                }
            },
            release: {
                options: {
                    flags: ['no-dev']
                }
            }
        },
        copy: {
            main: {
                files: [
                    {src: ['blocks/**'], dest: "dist/"},
                    {src: ['content_files/**'], dest: "dist/"},
                    {src: ['controllers/**'], dest: "dist/"},
                    {src: ['css/**'], dest: "dist/"},
                    {src: ['elements/**'], dest: "dist/"},
                    {src: ['images/**'], dest: "dist/"},
                    {src: ['js/**'], dest: "dist/"},
                    {src: ['languages/**'], dest: "dist/"},
                    {src: ['routes/**'], dest: "dist/"},
                    {src: ['single_pages/**'], dest: "dist/"},
                    {src: ['src/**'], dest: "dist/"},
                    {src: ['themes/**'], dest: "dist/"},
                    {src: ['views/**'], dest: "dist/"},
                    {src: ['controller.php'], dest: "dist/", filter: 'isFile'},
                    {src: ['icon.png'], dest: "dist/", filter: 'isFile'},
                    {src: ['INSTALL.TXT'], dest: "dist/", filter: 'isFile'},
                    {src: ['LICENSE.TXT'], dest: "dist/", filter: 'isFile'},
                    {src: ['CHANGELOG'], dest: "dist/", filter: 'isFile'},
                    {src: ['install.xml'], dest: "dist/", filter: 'isFile'}
                ]
            }
        },
        compress: {
            main: {
                options: {
                    archive: 'release/' + packageName + '.zip'
                },
                files: [
                    {src: ['**'], dest: packageName, expand: true, cwd: 'dist/'}
                ]
            }
        },
        clean: {
            dist: ['dist'],
            composer: ['vendor', 'composer.lock']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-composer');
    grunt.loadNpmTasks('grunt-version');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['clean:dist', 'copy', 'version', 'clean:composer', 'composer:dev:install', 'compress:main', 'clean:dist']);
};
