/**
 * Pruebas No Funcionales - Sistema Farmacia
 * Verifica rendimiento, seguridad y calidad del código
 */

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

// ── PRUEBAS DE RENDIMIENTO ─────────────────────────────────

console.log('\n📊 PRUEBAS DE RENDIMIENTO\n');

test('Los archivos PHP no superan 500KB', () => {
  const phpDir = path.join(__dirname, '../../php');
  if (!fs.existsSync(phpDir)) return;
  const archivos = fs.readdirSync(phpDir).filter(f => f.endsWith('.php'));
  archivos.forEach(archivo => {
    const size = fs.statSync(path.join(phpDir, archivo)).size;
    assert(size < 512000, `${archivo} supera 500KB (${Math.round(size/1024)}KB)`);
  });
});

test('Los archivos CSS no superan 200KB', () => {
  const disenioDir = path.join(__dirname, '../../diseno');
  if (!fs.existsSync(disenioDir)) return;
  const archivos = fs.readdirSync(disenioDir).filter(f => f.endsWith('.css'));
  archivos.forEach(archivo => {
    const size = fs.statSync(path.join(disenioDir, archivo)).size;
    assert(size < 204800, `${archivo} supera 200KB (${Math.round(size/1024)}KB)`);
  });
});

test('Las imágenes no superan 1MB', () => {
  const dirs = ['diseno', 'php'].map(d => path.join(__dirname, '../../', d));
  const exts = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
  dirs.forEach(dir => {
    if (!fs.existsSync(dir)) return;
    fs.readdirSync(dir)
      .filter(f => exts.includes(path.extname(f).toLowerCase()))
      .forEach(archivo => {
        const size = fs.statSync(path.join(dir, archivo)).size;
        assert(size < 1048576, `${archivo} supera 1MB (${Math.round(size/1024)}KB)`);
      });
  });
});

// ── PRUEBAS DE SEGURIDAD ───────────────────────────────────

console.log('\n🔒 PRUEBAS DE SEGURIDAD\n');

test('No hay contraseñas hardcodeadas en PHP', () => {
  const phpDir = path.join(__dirname, '../../php');
  if (!fs.existsSync(phpDir)) return;
  const patronesPeligrosos = [
    /password\s*=\s*["'][^"']{4,}["']/i,
    /passwd\s*=\s*["'][^"']{4,}["']/i,
  ];
  const archivos = fs.readdirSync(phpDir).filter(f => f.endsWith('.php'));
  archivos.forEach(archivo => {
    const contenido = fs.readFileSync(path.join(phpDir, archivo), 'utf8');
    patronesPeligrosos.forEach(patron => {
      // Permitimos 'root' solo en archivos de config
      if (patron.test(contenido) && !archivo.includes('config')) {
        assert(false, `Posible contraseña expuesta en ${archivo}`);
      }
    });
  });
});

test('No hay uso de mysql_ obsoleto (usar mysqli o PDO)', () => {
  const phpDir = path.join(__dirname, '../../php');
  if (!fs.existsSync(phpDir)) return;
  const archivos = fs.readdirSync(phpDir).filter(f => f.endsWith('.php'));
  archivos.forEach(archivo => {
    const contenido = fs.readFileSync(path.join(phpDir, archivo), 'utf8');
    const usoObsoleto = /\bmysql_connect\b|\bmysql_query\b|\bmysql_fetch/g.test(contenido);
    assert(!usoObsoleto, `${archivo} usa funciones mysql_ obsoletas e inseguras`);
  });
});

test('Los archivos HTML tienen charset UTF-8', () => {
  const disenioDir = path.join(__dirname, '../../diseno');
  if (!fs.existsSync(disenioDir)) return;
  const archivos = fs.readdirSync(disenioDir).filter(f => f.endsWith('.html'));
  archivos.forEach(archivo => {
    const contenido = fs.readFileSync(path.join(disenioDir, archivo), 'utf8');
    const tieneCharset = /charset\s*=\s*["']?utf-8["']?/i.test(contenido);
    assert(tieneCharset, `${archivo} no declara charset UTF-8`);
  });
});

// ── PRUEBAS DE CALIDAD ─────────────────────────────────────

console.log('\n📋 PRUEBAS DE CALIDAD\n');

test('Existe el archivo README.md', () => {
  const readme = path.join(__dirname, '../../README.md');
  assert(fs.existsSync(readme), 'El proyecto debe tener README.md');
});

test('Existe la carpeta database con el SQL', () => {
  const dbDir = path.join(__dirname, '../../database');
  assert(fs.existsSync(dbDir), 'Debe existir la carpeta database/');
});

test('Existen archivos PHP en la carpeta php/', () => {
  const phpDir = path.join(__dirname, '../../php');
  if (!fs.existsSync(phpDir)) {
    assert(false, 'La carpeta php/ no existe');
  }
  const archivos = fs.readdirSync(phpDir).filter(f => f.endsWith('.php'));
  assert(archivos.length > 0, 'La carpeta php/ debe contener archivos .php');
});

// ── REPORTE FINAL ──────────────────────────────────────────

const reportsDir = path.join(__dirname, '../../reports');
if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });

const reporte = {
  fecha: new Date().toISOString(),
  total: passed + failed,
  pasadas: passed,
  fallidas: failed,
  resultados: results
};

fs.writeFileSync(
  path.join(reportsDir, 'non-functional-report.json'),
  JSON.stringify(reporte, null, 2)
);

console.log('\n══════════════════════════════════════');
console.log(`  RESULTADO FINAL: ${passed} pasadas / ${failed} fallidas`);
console.log('══════════════════════════════════════\n');

if (failed > 0) process.exit(1);
