pipeline {
    agent none

    stages {
        stage('Notify') {
            steps {
                slackSend (color: '#FFFF00', message: "STARTED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
            }
        }

        stage('test') {
            parallel {
                stage('php:5.5') {
                    agent {
                        docker {
                            image 'php:5.5'
                            args '-u root:sudo -v /var/lib/dokku/data/storage/caches/composer:/vendor'
                        }
                    }

                    steps {
                        withCredentials([
                          file(credentialsId: 'ssh_private_key_file', variable: 'SSH_PRIVATE_KEY_FILE')
                          ]) {
                            sh '''
                                bash -x jenkins-ci.sh
                                php vendor/bin/phpunit
                                php vendor/bin/phpcs --standard=PSR2 -n lib tests *.php
                             '''
                        }
                    }
                }

                stage('php:5.6') {
                    agent {
                        docker {
                            image 'php:5.6'
                            args '-u root:sudo -v /var/lib/dokku/data/storage/caches/composer:/vendor'
                        }
                    }

                    steps {
                        withCredentials([
                          file(credentialsId: 'ssh_private_key_file', variable: 'SSH_PRIVATE_KEY_FILE')
                          ]) {
                            sh '''
                                bash -x jenkins-ci.sh
                                php vendor/bin/phpunit
                                php vendor/bin/phpcs --standard=PSR2 -n lib tests *.php
                             '''
                        }
                    }
                }
            }
        }
    }

    post {
        success {
          slackSend (color: '#00FF00', message: "SUCCESSFUL: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }

        failure {
          slackSend (color: '#FF0000', message: "FAILED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }
    }
}
