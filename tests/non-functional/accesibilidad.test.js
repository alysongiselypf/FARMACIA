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

console.log('\n♿ PRUEBAS DE ACCESIBILIDAD\n');

test('Los archivos HTML tienen atributo lang', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/<html[^>]+lang=/i.test(contenido), `${path.basename(archivo)} no tiene atributo lang en html`);
    });
});

test('Las imagenes tienen atributo alt en HTML', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const imagenes = contenido.match(/<img[^>]+>/gi) || [];
        imagenes.forEach(img => {
            assert(/alt=/i.test(img), `${path.basename(archivo)} tiene imagen sin atributo alt`);
        });
    });
});

test('Los formularios HTML tienen labels', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        if (/<form/i.test(contenido)) {
            assert(/<label/i.test(contenido), `${path.basename(archivo)} tiene formulario sin labels`);
        }
    });
});

test('Los botones tienen texto descriptivo', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const botones = contenido.match(/<button[^>]*>([^<]*)<\/button>/gi) || [];
        botones.forEach(boton => {
            const texto = boton.replace(/<[^>]+>/g, '').trim();
            assert(texto.length > 0, `${path.basename(archivo)} tiene un boton sin texto`);
        });
    });
});

test('Los archivos HTML tienen meta description', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/meta[^>]+description/i.test(contenido) || /<title>/i.test(contenido),
            `${path.basename(archivo)} no tiene meta description ni title`);
    });
});

test('No hay tablas HTML sin encabezados th', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        if (/<table/i.test(contenido)) {
            assert(/<th/i.test(contenido), `${path.basename(archivo)} tiene tabla sin encabezados th`);
        }
    });
});

test('Los inputs tienen atributo type definido', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const inputs = contenido.match(/<input[^>]+>/gi) || [];
        inputs.forEach(input => {
            if (!/type\s*=\s*["']hidden["']/i.test(input)) {
                assert(/type=/i.test(input), `${path.basename(archivo)} tiene input sin atributo type`);
            }
        });
    });
});

test('Los archivos HTML tienen estructura head y body', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/<head>/i.test(contenido) || /<head /i.test(contenido), `${path.basename(archivo)} no tiene etiqueta head`);
        assert(/<body>/i.test(contenido) || /<body /i.test(contenido), `${path.basename(archivo)} no tiene etiqueta body`);
    });
});

test('No hay estilos inline excesivos en HTML', () => {
    buscarArchivos(disenioDir, '.html').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        const estilosInline = (contenido.match(/style\s*=/gi) || []).length;
        assert(estilosInline < 30, `${path.basename(archivo)} tiene ${estilosInline} estilos inline (demasiados)`);
    });
});

test('Los PHP tienen estructura basica correcta', () => {
    buscarArchivos(phpDir, '.php').forEach(archivo => {
        const contenido = fs.readFileSync(archivo, 'utf8');
        assert(/^<\?php/m.test(contenido), `${path.basename(archivo)} no comienza con <?php`);
    });
});

const reportsDir = path.join(__dirname, '../../reports');
if (!fs.existsSync(reportsDir)) fs.mkdirSync(reportsDir, { recursive: true });

fs.writeFileSync(
    path.join(reportsDir, 'accesibilidad-report.json'),
    JSON.stringify({ fecha: new Date().toISOString(), total: passed + failed, pasadas: passed, fallidas: failed, resultados: results }, null, 2)
);

console.log('\n══════════════════════════════════════');
console.log(`  RESULTADO: ${passed} pasadas / ${failed} fallidas`);
console.log('══════════════════════════════════════\n');

if (failed > 0) process.exit(1);
