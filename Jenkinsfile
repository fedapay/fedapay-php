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
                            args '-u root:sudo'
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

                    post {
                        always {
                          sh 'rm -R vendor/'
                        }
                    }
                }

                stage('php:5.6') {
                    agent {
                        docker {
                            image 'php:5.6'
                            args '-u root:sudo'
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

                    post {
                        always {
                          sh 'rm -R vendor/'
                        }
                    }
                }

                stage('php:7.0') {
                    agent {
                        docker {
                            image 'php:7.0'
                            args '-u root:sudo'
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

                    post {
                        always {
                          sh 'rm -R vendor/'
                        }
                    }
                }

                stage('php:7.1') {
                    agent {
                        docker {
                            image 'php:7.1'
                            args '-u root:sudo'
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

                    post {
                        always {
                          sh 'rm -R vendor/'
                        }
                    }
                }

                stage('php:7.2') {
                    agent {
                        docker {
                            image 'php:7.2'
                            args '-u root:sudo'
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

                    post {
                        always {
                          sh 'rm -R vendor/'
                        }
                    }
                }
            }
        }

        stage('Trigger') {
            agent any

            when {
                anyOf {
                    branch 'develop'
                    branch 'master'
                }
            }

            steps {
                withCredentials([usernamePassword(credentialsId: 'auth_access', usernameVariable: 'USERNAME', passwordVariable: 'PASSWORD')]) {
                    sh '''
                        URL='https://jenkins.fedapay.com/crumbIssuer/api/xml?xpath=concat(//crumbRequestField,":",//crumb)'
                        CRUMB=$(curl -s $URL --user ${USERNAME}:${PASSWORD})
                        curl -X POST -H "$CRUMB" --user ${USERNAME}:${PASSWORD} https://jenkins.fedapay.com/job/fedapay-checkout/job/${BRANCH_NAME}/build
                    '''
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
