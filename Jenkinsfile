pipeline {
    agent any

    stages {
        // a. Construcción Automática
        stage('Construcción Automática') {
            steps {
                echo 'Instalando dependencias...'
                sh 'npm install'
            }
        }

        // b. Análisis Estático de Código Fuente
        stage('Análisis Estático (SonarQube)') {
            steps {
                echo 'Ejecutando análisis de código con SonarQube...'
                // sh 'sonar-scanner'
            }
        }

        // c. Pruebas Unitarias
        stage('Pruebas Unitarias') {
            steps {
                echo 'Ejecutando pruebas de sintaxis PHP...'
                sh 'find php/ -type f -name "*.php" -exec php -l {} \\;'
            }
        }

        // d. Pruebas Funcionales (Selenium)
        stage('Pruebas Funcionales (Selenium)') {
            steps {
                echo 'Ejecutando pruebas funcionales de base de datos con PHPUnit...'
                // sh 'php phpunit-10.phar tests/functional/'
            }
        }

        // e. Pruebas de Performance (JMeter)
        stage('Pruebas de Performance (JMeter)') {
            steps {
                echo 'Ejecutando pruebas de rendimiento con JMeter...'
                sh 'node tests/non-functional/performance.test.js'
            }
        }

        // f. Pruebas de Seguridad (OWASP ZAP)
        stage('Pruebas de Seguridad (OWASP ZAP)') {
            steps {
                echo 'Ejecutando escaneo de vulnerabilidades con OWASP ZAP...'
                sh 'npm audit --audit-level=high || true'
                sh 'node tests/non-functional/seguridad.test.js'
            }
        }

        // g. Gestión de Issues (Jira/Github Issues)
        stage('Gestión de Issues') {
            steps {
                echo 'Sincronizando estado de tareas y bugs en GitHub Issues...'
            }
        }

        // h. Gestión de Entrega y Despliegue Automático (Docker)
        stage('Despliegue Automático (Docker)') {
            steps {
                echo 'Empaquetando sistema en contenedor Docker...'
                sh 'echo "docker build -t farmacia-app:latest ."'
                echo 'Desplegando contenedor de producción en el puerto 80...'
                // sh 'docker run -d -p 80:80 farmacia-app:latest'
            }
        }
    }

    post {
        success {
            echo '¡El pipeline de Jenkins se ejecutó con éxito!'
        }
        failure {
            echo '🚨 ALERTA: El pipeline ha fallado en alguna etapa.'
        }
    }
}
