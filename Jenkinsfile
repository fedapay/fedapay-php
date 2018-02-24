pipeline {
    agent {
        docker {
            image 'php:5.5'
            args '-u root:sudo'
        }
    }

    environment {

    }

    stages {
        stage('php:5.5') {
            steps {
                slackSend (color: '#FFFF00', message: "STARTED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")

                withCredentials([
                  file(credentialsId: 'ssh_private_key_file', variable: 'SSH_PRIVATE_KEY_FILE')
                  ]) {
                    sh '''
                        apt-get update -yqq
                        apt-get install git libcurl4-gnutls-dev libicu-dev libmcrypt-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev libldap2-dev unixodbc-dev libpq-dev libsqlite3-dev libaspell-dev libsnmp-dev libpcre3-dev libtidy-dev -yqq
                        docker-php-ext-install mbstring curl json intl gd xml zip bz2 opcache
                        curl -sS https://getcomposer.org/installer | php
                        php composer.phar install
                        php vendor/bin/phpunit
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
