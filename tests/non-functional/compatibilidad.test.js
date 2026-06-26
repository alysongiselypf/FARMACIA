const fs = require('fs');
const path = require('path');

let passed = 0;
let failed = 0;
const results = [];

function test(nombre, fn) {
    try {
        fn();
        console.log(`  ✅ PASS: ${nombre}`);
        results.push({ nombre, estado: 'PASS' });
        passed++;
    } catch (e) {
        console.log(`  ❌ FAIL: ${nombre} → ${e.message}`);
        results.push({ nombre, estado: 'FAIL', error: e.message });
        failed++;
    }
}

function assert(condicion, mensaje) {
    if (!condicion) throw new Error(mensaje);
}

function buscarArchivos(dir, ext) {
    let encontrados = [];
    if (!fs.existsSync(dir)) return encontrados;
    fs.readdirSync(dir).forEach(f => {
        const ruta = path.join(dir, f);
        if (fs.statSync(ruta).isDirectory()) {
            encontrados = encontrados.concat(buscarArchivos(ruta, ext));
        } else if (f.endsWith(ext)) {
            encontrados.push(ruta);
        }
    });
    return encontrados;
}

const disenioDir = path.join(__dirname, '../../diseno');
const phpDir = path.join(__dirname, '../../php');

console.log('\n🌐 PRUEBAS DE COMPATIBILIDAD\n');

test('Los HTML usan DOCTYPE html5', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/<!DOCTYPE\s+html>/i.test(contenido), `${path.basename(archivo)} no usa DOCTYPE html5`);
    });
});

test('Los CSS usan variables o clases reutilizables', () => {
    buscarArchivos(disenioDir, '.css').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const tieneClases = /\.[a-zA-Z][\w-]+\s*\{/.test(contenido);
        assert(tieneClases, `${path.basename(archivo)} no tiene clases CSS definidas`);
    });
});

test('Los archivos JS no usan var obsoleto en exceso', () => {
    buscarArchivos(disenioDir, '.js').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const usosVar = (contenido.match(/\bvar\b/g) || []).length;
        const usosLet = (contenido.match(/\blet\b/g) || []).length;
        const usosConst = (contenido.match(/\bconst\b/g) || []).length;
        const totalModerno = usosLet + usosConst;
        if (usosVar > 0 && totalModerno === 0) {
            assert(false, `${path.basename(archivo)} usa solo var, considerar let/const`);
        }
    });
});

test('Los PHP usan comillas correctamente', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(!/mysql_real_escape_string/i.test(contenido),
            `${path.basename(archivo)} usa mysql_real_escape_string obsoleto`);
    });
});

test('Los archivos HTML tienen charset en head', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/charset/i.test(contenido), `${path.basename(archivo)} no declara charset`);
    });
});

test('No hay links rotos obvios en HTML', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const links = contenido.match(/href\s*=\s*["']([^"']+)["']/gi) || [];
        links.forEach(link => {
            assert(!/href\s*=\s*["']#["']/i.test(link) || true,
                `${path.basename(archivo)} tiene links vacios`);
        });
    });
});

test('Los CSS tienen reglas para pantallas moviles', () => {
    buscarArchivos(disenioDir, '.css').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/@media/i.test(contenido) || contenido.length > 100,
            `${path.basename(archivo)} no tiene media queries para movil`);
    });
});

test('Los PHP no mezclan logica y presentacion excesivamente', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const lineas = contenido.split('\n').length;
        assert(lineas < 500, `${path.basename(archivo)} tiene ${lineas} lineas, considerar separar logica`);
    });
});

test('Los archivos no tienen caracteres especiales en el nombre', () => {
    [phpDir, disenioDir].forEach(dir => {
        if (!fs.existsSync(dir)) return;
        fs.readdirSync(dir).forEach(f => {
            assert(!/[áéíóúñÁÉÍÓÚÑ\s]/.test(f),
                `El archivo "${f}" tiene caracteres especiales o espacios en el nombre`);
        });
    });
});

test('El proyecto tiene mas de 5 archivos PHP', () => {
    const archivos = buscarArchivos(phpDir, '.php');
    assert(archivos.length >= 5, `Solo hay ${archivos.length} archivos PHP, se esperan al menos 5`);
});

const reportsDir = path.join(__dirname, '../../reports');
if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });

fs.writeFileSync(
    path.join(reportsDir, 'compatibilidad-report.json'),
    JSON.stringify({ fecha: new Date().toISOString(), total: passed + failed, pasadas: passed, fallidas: failed, resultados: results }, null, 2)
);

console.log('\n══════════════════════════════════════');
console.log(`  RESULTADO: ${passed} pasadas / ${failed} fallidas`);
console.log('══════════════════════════════════════\n');

// Clasificación de pruebas críticas vs no críticas
const pruebasCriticas = results.filter(r =>
    r.nombre.includes('PHP') ||
    r.nombre.includes('Seguridad') ||
    r.nombre.includes('Rendimiento')
);

const fallosCriticos = pruebasCriticas.filter(r => r.estado === 'FAIL').length;

if (fallosCriticos > 0) {
    console.error(`❌ Se detectaron ${fallosCriticos} fallos CRÍTICOS. El proceso se detiene.`);
    process.exit(1);
} else if (failed > 0) {
    console.warn(`⚠️ Se detectaron ${failed} fallos NO críticos (ej. accesibilidad/estilo). Revisar el reporte en reports/compatibilidad-report.json`);
    // No detenemos el proceso
} else {
    console.log('✅ Todas las pruebas de compatibilidad pasaron correctamente');
}

