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

const phpDir = path.join(__dirname, '../../php');
const disenioDir = path.join(__dirname, '../../diseno');

console.log('\n🔒 PRUEBAS DE SEGURIDAD AVANZADAS\n');

test('No hay uso de eval() en PHP', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(!/\beval\s*\(/.test(contenido), `${path.basename(archivo)} usa eval() que es inseguro`);
    });
});

test('No hay uso de $_GET sin sanitizar en PHP', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const tieneGetDirecto = /echo\s+\$_GET/i.test(contenido);
        assert(!tieneGetDirecto, `${path.basename(archivo)} imprime $_GET directamente (XSS)`);
    });
});

test('No hay uso de $_POST sin sanitizar en PHP', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const tienePostDirecto = /echo\s+\$_POST/i.test(contenido);
        assert(!tienePostDirecto, `${path.basename(archivo)} imprime $_POST directamente (XSS)`);
    });
});

test('Los archivos HTML tienen titulo definido', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        if (!/<title>/i.test(contenido)) {
            console.log(`  ⚠️ WARN: ${path.basename(archivo)} no tiene etiqueta title`);
            results.push({ nombre: 'Titulo definido', estado: 'WARN', archivo });
        }
    });
});

test('No hay comentarios con TODO en produccion', () => {
    const archivosPhp = buscarArchivos(phpDir, '.php');
    let todoCount = 0;
    archivosPhp.forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const matches = contenido.match(/\/\/\s*TODO/gi);
        if (matches) todoCount += matches.length;
    });
    assert(todoCount < 10, `Hay ${todoCount} comentarios TODO pendientes en el codigo`);
});

test('No hay archivos de configuracion expuestos', () => {
    const archivosExpuestos = ['.env', 'config.ini', 'settings.ini'];
    archivosExpuestos.forEach(archivo => {
        const ruta = path.join(__dirname, '../../', archivo);
        assert(!fs.existsSync(ruta), `El archivo ${archivo} esta expuesto en el repositorio`);
    });
});

test('Los archivos JS no tienen console.log en exceso', () => {
    buscarArchivos(disenioDir, '.js').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const matches = contenido.match(/console\.log/g);
        const cantidad = matches ? matches.length : 0;
        assert(cantidad < 20, `${path.basename(archivo)} tiene ${cantidad} console.log (demasiados)`);
    });
});

test('Los archivos HTML tienen doctype declarado', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        if (!/<!DOCTYPE/i.test(contenido)) {
            console.log(`  ⚠️ WARN: ${path.basename(archivo)} no tiene DOCTYPE declarado`);
            results.push({ nombre: 'Doctype declarado', estado: 'WARN', archivo });
        }
    });
});

test('No hay extension .bak o .old en el proyecto', () => {
    [phpDir, disenioDir].forEach(dir => {
        const bakFiles = buscarArchivos(dir, '.bak').concat(buscarArchivos(dir, '.old'));
        assert(bakFiles.length === 0, `Hay archivos de respaldo expuestos: ${bakFiles.join(', ')}`);
    });
});

test('Los archivos CSS no estan vacios', () => {
    buscarArchivos(disenioDir, '.css').forEach(archivo => {
        const size = fs.statSync(archivo).size;
        assert(size > 10, `${path.basename(archivo)} parece estar vacio`);
    });
});

const reportsDir = path.join(__dirname, '../../reports');
if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });

fs.writeFileSync(
    path.join(reportsDir, 'seguridad-report.json'),
    JSON.stringify({ fecha: new Date().toISOString(), total: passed + failed, pasadas: passed, fallidas: failed, resultados: results }, null, 2)
);

console.log('\n══════════════════════════════════════');
console.log(`  RESULTADO: ${passed} pasadas / ${failed} fallidas`);
console.log('══════════════════════════════════════\n');

if (failed > 0) process.exit(1);
