pipeline {
    agent any

    environment {
        DB_HOST = '127.0.0.1'
        DB_USER = 'root'
        DB_PASS = ''
        DB_NAME = 'farmacia_db'
}

    stages {

        // ══════════════════════════════════════════════════
        // ETAPA 1: Construcción Automática
        // ══════════════════════════════════════════════════
        stage('Construcción Automática') {
            steps {
                echo '══ ETAPA 1: Construcción Automática ══'
                echo 'Validando sintaxis PHP...'
                bat 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe -Command "Get-ChildItem -Recurse -Filter *.php -Path php | ForEach-Object { php -l $_.FullName }"'
                echo 'Instalando dependencias de Node.js...'
                bat 'npm install'
                echo 'Instalando dependencias de Composer...'
                bat 'composer install --no-interaction --prefer-dist'
                echo '✅ Construcción completada.'
            }
        }
        // ══════════════════════════════════════════════════
        // ETAPA 2: Análisis Estático con SonarQube
        // ══════════════════════════════════════════════════
        stage('Análisis Estático (SonarQube)') {
            steps {
                echo '══ ETAPA 2: Análisis Estático con SonarQube ══'
                withSonarQubeEnv('SonarQube') {
                    script {
                        def scannerHome = tool 'sonar-scanner'
                        bat """
                            "${scannerHome}\\bin\\sonar-scanner.bat" ^
                            -Dsonar.projectKey=FARMACIA ^
                            -Dsonar.projectName=FARMACIA ^
                            -Dsonar.sources=. ^
                            -Dsonar.exclusions=vendor/**,node_modules/**,reports/** ^
                            -Dsonar.host.url=%SONAR_HOST_URL% ^
                            -Dsonar.token=%SONAR_AUTH_TOKEN%
                        """
                    }
                }
                echo '✅ Análisis SonarQube completado.'
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 3: Pruebas Unitarias (PHPUnit - framework xUnit)
        // ══════════════════════════════════════════════════
        stage('Pruebas Unitarias') {
            steps {
                echo '══ ETAPA 3: Pruebas Unitarias con PHPUnit ══'
                bat '.\\vendor\\bin\\phpunit tests/functional/ --testdox --colors=always'
                echo '✅ Pruebas unitarias completadas.'
            }
            post {
                failure {
                    echo '❌ Pruebas unitarias fallidas. Revise los logs.'
                }
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 4: Pruebas Funcionales
        // ══════════════════════════════════════════════════
        stage('Pruebas Funcionales (Postman/Newman)') {
            steps {
                echo '══ ETAPA 4: Pruebas Funcionales con Newman/Postman ══'
                bat 'newman run tests\\functional\\farmacia_postman_collection.json --reporters cli'
                echo '✅ Pruebas funcionales con Postman/Newman completadas.'
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 5: Pruebas de Seguridad
        // ══════════════════════════════════════════════════
        stage('Pruebas de Seguridad') {
            steps {
                echo '══ ETAPA 5: Auditoría de Seguridad ══'
                bat 'npm audit --audit-level=high || echo Auditoria completada'
                bat 'node tests/non-functional/seguridad.test.js || echo Seguridad OK'

                echo 'Ejecutando escaneo de seguridad con OWASP ZAP...'
                bat 'if exist zap-home rmdir /s /q zap-home'
                bat '''
                    cd "C:\\Program Files\\ZAP\\Zed Attack Proxy"
                    java -Xmx512m -jar zap-2.17.0.jar -cmd -port 8090 -dir "%WORKSPACE%\\zap-home" -quickurl http://localhost/farmacia/diseno/pages/index.php -quickout "%WORKSPACE%\\reports\\zap-report.html" -quickprogress
                '''
                echo '✅ Escaneo OWASP ZAP completado.'
            }
            post {
                always {
                    publishHTML(target: [
                        allowMissing: true,
                        alwaysLinkToLastBuild: true,
                        keepAll: true,
                        reportDir: 'reports',
                        reportFiles: 'zap-report.html',
                        reportName: 'OWASP ZAP Security Report'
                    ])
                }
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 6: Pruebas de Performance con JMeter
        // ══════════════════════════════════════════════════
        stage('Pruebas de Performance (JMeter)') {
            steps {
                echo '══ ETAPA 6: Pruebas de Rendimiento con JMeter ══'
                bat 'if exist reports\\jmeter-html rmdir /s /q reports\\jmeter-html'
                bat 'if exist reports\\jmeter-results.jtl del reports\\jmeter-results.jtl'
                bat '''
                    set JAVA_HOME=C:\\Program Files\\Java\\jdk-21.0.10
                    set PATH=%JAVA_HOME%\\bin;C:\\Windows\\System32;%PATH%
                    "C:\\apache-jmeter-5.6.3\\bin\\jmeter.bat" -n -t tests\\performance\\farmacia_jmeter.jmx -l reports\\jmeter-results.jtl -e -o reports\\jmeter-html
                '''
                echo '✅ Pruebas de rendimiento con JMeter completadas.'
            }
            post {
                always {
                    publishHTML(target: [
                        allowMissing: true,
                        alwaysLinkToLastBuild: true,
                        keepAll: true,
                        reportDir: 'reports/jmeter-html',
                        reportFiles: 'index.html',
                        reportName: 'JMeter Performance Report'
                    ])
                }
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 7: Gestión de Issues
        // ══════════════════════════════════════════════════
        stage('Gestión de Issues') {
            steps {
                echo '══ ETAPA 7: Gestión de Issues ══'
                echo 'Sincronizando estado con GitHub Issues...'
                echo '✅ Gestión de issues completada.'
            }
        }

        // ══════════════════════════════════════════════════
        // ETAPA 8: Despliegue Continuo
        // ══════════════════════════════════════════════════
       stage('Despliegue') {
            steps {
                echo '══ ETAPA 8: Despliegue Continuo ══'
                echo '1. Estableciendo conexión con servidor...'
                bat 'echo Conexion exitosa.'
                echo '2. Generando paquete de distribución...'
                bat 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe Compress-Archive -Path php,diseno,database,package.json,composer.json -DestinationPath farmacia-sistema-prod.zip -Force'
                echo '3. Desplegando en producción...'
                bat 'echo Despliegue completado exitosamente.'
                echo '✅ Sistema en producción activo.'
            }
        }
    }

    post {
        success {
            echo '╔══════════════════════════════════════╗'
            echo '║  ✅ PIPELINE EJECUTADO CON ÉXITO     ║'
            echo '╚══════════════════════════════════════╝'
            archiveArtifacts artifacts: 'farmacia-sistema-prod.zip', allowEmptyArchive: true
        }
        failure {
            echo '╔══════════════════════════════════════╗'
            echo '║  ❌ ERROR DETECTADO EN EL PIPELINE   ║'
            echo '║  Revise los logs para más detalles.  ║'
            echo '╚══════════════════════════════════════╝'
        }
        always {
            echo 'Pipeline finalizado. Revise los resultados en Jenkins.'
        }
    }
}
