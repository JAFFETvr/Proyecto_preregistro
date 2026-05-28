const areasInaoep = [
    { id: "1", nombre: "Astrofísica",                        valor: "Astrofisica" },
    { id: "2", nombre: "Óptica",                             valor: "Optica" },
    { id: "3", nombre: "Electrónica",                        valor: "Electronica" },
    { id: "4", nombre: "Ciencias Computacionales",           valor: "Ciencias Computacionales" },
    { id: "5", nombre: "Ciencia y Tecnología del Espacio",   valor: "Ciencia y Tecnologia del Espacio" },
    { id: "6", nombre: "Ciencias y Tecnologías Biomédicas",  valor: "Ciencias y Tecnologias Biomedicas" },
    { id: "7", nombre: "Ciencias y Tecnologías de Seguridad",valor: "Ciencias y Tecnologias de Seguridad" },
    { id: "8", nombre: "Enseñanza de Ciencias Exactas",      valor: "Ensenanza de Ciencias Exactas" },
    { id: "9", nombre: "Especialidad en Tecnologias  de Semiconductores",      valor: "Especialidad en Tecnologias  de Semiconductores" }

];

/*
 * IMPORTANTE: el value de cada opción de modalidad debe coincidir
 * exactamente con los valores que valida el PHP en validarPreregistro()
 * y que mapea $mapMod en insertPreregistro():
 *   'Tesis', 'Tesina', 'Promedio', 'Presentacion y Defensa', 'Portafolio de Evidencias'
 */
const modalidadesInaoep = [
    { valor: "Tesis",                  nombre: "Tesis (Astrofísica, Óptica, Electrónica, C. Computacionales, Espacio, Biomédicas, Seguridad, MECE)" },
    { valor: "Tesina",                 nombre: "Tesina (Solo MECE)" },
    { valor: "Promedio",               nombre: "Promedio (Solo MECE)" },
    { valor: "Presentacion y Defensa", nombre: "Presentación y Defensa de intervención con Material Didáctico (Solo MECE)" },
    { valor: "Portafolio de Evidencias", nombre: "Portafolio de Evidencias" }
];

const instituciones = [
    { id: "1", nombre: "INAOE (Instituto Nacional de Astrofísica, Óptica y Electrónica)" }
];

const entidadesFederativas = [
    { id: "21", nombre: "Puebla" }
];

function cargarDatosFijos() {

    /* ── Área del título ──────────────────────────────────────────────
       Se vacía el select (conservando solo el placeholder) antes de
       llenarlo para evitar duplicados con las opciones que ya trae el HTML.
    ----------------------------------------------------------------- */
    const selectArea = document.getElementById('course_cvecourse');
    if (selectArea) {
        // Eliminar todas las opciones excepto la primera (placeholder)
        while (selectArea.options.length > 1) {
            selectArea.remove(1);
        }
        areasInaoep.forEach(function(area) {
            const option = document.createElement('option');
            // value = texto sin acentos → lo que espera el mapAreas del controlador PHP
            option.value = area.valor;
            option.text  = area.nombre;
            selectArea.add(option);
        });
    }

    /* ── Modalidad de titulación ──────────────────────────────────────
       Mismo tratamiento anti-duplicados.
       value = texto exacto que valida PHP ('Tesis', 'Tesina', etc.)
    ----------------------------------------------------------------- */
    const selectModalidad = document.getElementById('expedition_iddegreemodality');
    if (selectModalidad) {
        while (selectModalidad.options.length > 1) {
            selectModalidad.remove(1);
        }
        modalidadesInaoep.forEach(function(mod) {
            const option = document.createElement('option');
            option.value = mod.valor;   // valor exacto para PHP
            option.text  = mod.nombre;  // texto visible con acentos
            selectModalidad.add(option);
        });
    }

    /* ── Institución (si el select existe en alguna vista) ────────── */
    const selectInstitucion = document.getElementById('institution');
    if (selectInstitucion) {
        while (selectInstitucion.options.length > 1) {
            selectInstitucion.remove(1);
        }
        instituciones.forEach(function(inst) {
            const option = document.createElement('option');
            option.value = inst.id;
            option.text  = inst.nombre;
            selectInstitucion.add(option);
        });
    }

    /* ── Estado de expedición y antecedentes (si existen) ────────── */
    const selectExpeditionState = document.getElementById('expedition-state');
    if (selectExpeditionState) {
        while (selectExpeditionState.options.length > 1) {
            selectExpeditionState.remove(1);
        }
        entidadesFederativas.forEach(function(entidad) {
            const option = document.createElement('option');
            option.value = entidad.id;
            option.text  = entidad.nombre;
            selectExpeditionState.add(option);
        });
    }

    const selectAntecedentState = document.getElementById('antecedent-state');
    if (selectAntecedentState) {
        while (selectAntecedentState.options.length > 1) {
            selectAntecedentState.remove(1);
        }
        entidadesFederativas.forEach(function(entidad) {
            const option = document.createElement('option');
            option.value = entidad.id;
            option.text  = entidad.nombre;
            selectAntecedentState.add(option);
        });
    }
}

document.addEventListener("DOMContentLoaded", cargarDatosFijos);