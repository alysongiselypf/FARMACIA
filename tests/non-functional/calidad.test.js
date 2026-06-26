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

function obtenerArchivos(dir, ext) {
    if (!fs.existsSync(dir)) return [];
    return fs.readdirSync(dir).filter(f => f.endsWith(ext));
}

// ── PRUEBAS DE ESTRUCTURA DEL PROYECTO ────────────────────

console.log('\n📁 PRUEBAS DE ESTRUCTURA\n');

test('Existe carpeta php/', () => {
    assert(fs.existsSync(path.join(__dirname, '../../php')), 'Debe existir la carpeta php/');
});

test('Existe carpeta diseno/', () => {
    assert(fs.existsSync(path.join(__dirname, '../../diseno')), 'Debe existir la carpeta diseno/');
});

test('Existe carpeta database/', () => {
    assert(fs.existsSync(path.join(__dirname, '../../database')), 'Debe existir la carpeta database/');
});

test('Existe carpeta tests/', () => {
    assert(fs.existsSync(path.join(__dirname, '../../tests')), 'Debe existir la carpeta tests/');
});

test('Existe archivo README.md', () => {
    assert(fs.existsSync(path.join(__dirname, '../../README.md')), 'Debe existir README.md');
});

test('Existe archivo ci.yml', () => {
    const ciPath = path.join(__dirname, '../../.github/workflows/ci.yml');
    assert(fs.existsSync(ciPath), 'Debe existir el pipeline ci.yml');
});

// ── PRUEBAS DE CALIDAD DE CODIGO ──────────────────────────

console.log('\n🔍 PRUEBAS DE CALIDAD DE CODIGO\n');

test('Los archivos PHP tienen extension correcta', () => {
    const phpDir = path.join(__dirname, '../../php');
    const archivos = obtenerArchivos(phpDir, '.php');
    assert(archivos.length > 0, 'Debe haber archivos .php en la carpeta php/');
});

test('Los archivos CSS tienen extension correcta', () => {
    const disenioDir = path.join(__dirname, '../../diseno');
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
    const archivos = buscarArchivos(disenioDir, '.css');
    assert(archivos.length > 0, 'Debe haber archivos .css en la carpeta diseno/');
});

test('Existe al menos un archivo HTML', () => {
    const disenioDir = path.join(__dirname, '../../diseno');
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
    const archivos = buscarArchivos(disenioDir, '.html');
    assert(archivos.length > 0, 'Debe haber archivos .html en la carpeta diseno/');
});

test('El SQL de la base de datos existe', () => {
    const sqlPath = path.join(__dirname, '../../database/farmacia_db.sql');
    assert(fs.existsSync(sqlPath), 'Debe existir el archivo farmacia_db.sql');
});

test('El archivo SQL no esta vacio', () => {
    const sqlPath = path.join(__dirname, '../../database/farmacia_db.sql');
    if (!fs.existsSync(sqlPath)) return;
    const size = fs.statSync(sqlPath).size;
    assert(size > 100, 'El archivo SQL no debe estar vacio');
});

test('No hay archivos .exe o .bat en el proyecto', () => {
    const dirs = ['php', 'diseno'].map(d => path.join(__dirname, '../../', d));
    dirs.forEach(dir => {
        if (!fs.existsSync(dir)) return;
        const peligrosos = fs.readdirSync(dir).filter(f => f.endsWith('.exe') || f.endsWith('.bat'));
        assert(peligrosos.length === 0, `Se encontraron archivos peligrosos: ${peligrosos.join(', ')}`);
    });
});

// ── PRUEBAS DE SEGURIDAD ───────────────────────────────────

console.log('\n🔒 PRUEBAS DE SEGURIDAD\n');

test('No hay credenciales expuestas en archivos PHP', () => {
    const phpDir = path.join(__dirname, '../../php');
    if (!fs.existsSync(phpDir)) return;
    const archivos = obtenerArchivos(phpDir, '.php');
    archivos.forEach(archivo => {
        const contenido = fs.readFileSync(path.join(phpDir, archivo), 'utf8');
        const tieneCredencial = /password\s*=\s*["'][a-zA-Z0-9]{6,}["']/i.test(contenido);
        if (tieneCredencial && !archivo.includes('config') && !archivo.includes('conexion')) {
            assert(false, `Posible credencial expuesta en ${archivo}`);
        }
    });
});

test('No se usa mysql_ obsoleto en PHP', () => {
    const phpDir = path.join(__dirname, '../../php');
    if (!fs.existsSync(phpDir)) return;
    const archivos = obtenerArchivos(phpDir, '.php');
    archivos.forEach(archivo => {
        const contenido = fs.readFileSync(path.join(phpDir, archivo), 'utf8');
        assert(!/\bmysql_connect\b/.test(contenido), `${archivo} usa mysql_connect obsoleto`);
    });
});

test('Los archivos HTML tienen meta viewport', () => {
    const disenioDir = path.join(__dirname, '../../diseno');
    if (!fs.existsSync(disenioDir)) return;
    const archivos = obtenerArchivos(disenioDir, '.html');
    archivos.forEach(archivo => {
        const contenido = fs.readFileSync(path.join(disenioDir, archivo), 'utf8');
        const tieneViewport = /viewport/i.test(contenido);
        assert(tieneViewport, `${archivo} no tiene meta viewport (no es responsive)`);
    });
});

// ── PRUEBAS DE RENDIMIENTO ─────────────────────────────────

console.log('\n⚡ PRUEBAS DE RENDIMIENTO\n');

test('Ningun archivo PHP supera 300KB', () => {
    const phpDir = path.join(__dirname, '../../php');
    if (!fs.existsSync(phpDir)) return;
    obtenerArchivos(phpDir, '.php').forEach(archivo => {
        const size = fs.statSync(path.join(phpDir, archivo)).size;
        assert(size < 307200, `${archivo} supera 300KB (${Math.round(size/1024)}KB)`);
    });
});

test('Ningun archivo CSS supera 150KB', () => {
    const disenioDir = path.join(__dirname, '../../diseno');
    if (!fs.existsSync(disenioDir)) return;
    obtenerArchivos(disenioDir, '.css').forEach(archivo => {
        const size = fs.statSync(path.join(disenioDir, archivo)).size;
        assert(size < 153600, `${archivo} supera 150KB (${Math.round(size/1024)}KB)`);
    });
});

test('Ningun archivo JS supera 200KB', () => {
    const disenioDir = path.join(__dirname, '../../diseno');
    if (!fs.existsSync(disenioDir)) return;
    obtenerArchivos(disenioDir, '.js').forEach(archivo => {
        const size = fs.statSync(path.join(disenioDir, archivo)).size;
        assert(size < 204800, `${archivo} supera 200KB (${Math.round(size/1024)}KB)`);
    });
});

// ── REPORTE FINAL ──────────────────────────────────────────

const reportsDir = path.join(__dirname, '../../reports');
if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });

fs.writeFileSync(
    path.join(reportsDir, 'calidad-report.json'),
    JSON.stringify({ fecha: new Date().toISOString(), total: passed + failed, pasadas: passed, fallidas: failed, resultados: results }, null, 2)
);

console.log('\n══════════════════════════════════════');
console.log(`  RESULTADO: ${passed} pasadas / ${failed} fallidas`);
console.log('══════════════════════════════════════\n');

if (failed > 0) process.exit(1);
