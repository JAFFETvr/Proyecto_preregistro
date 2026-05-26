const areasInaoep = [
    { id: "1", nombre: "Astrofísica" },
    { id: "2", nombre: "Óptica" },
    { id: "3", nombre: "Electrónica" },
    { id: "4", nombre: "Ciencias Computacionales" },
    { id: "5", nombre: "Ciencia y Tecnología del Espacio" },
    { id: "6", nombre: "Ciencias y Tecnologías Biomédicas" },
    { id: "7", nombre: "Ciencias y Tecnologías de Seguridad" },
    { id: "8", nombre: "Enseñanza de Ciencias Exactas" }
];

const modalidadesInaoep = [
    { id: "1", nombre: "Tesis (Astrofísica, Óptica, Electrónica, C. Computacionales, Espacio, Biomédicas, Seguridad, MECE)" },
    { id: "3", nombre: "Tesina (Solo MECE)" },
    { id: "2", nombre: "Promedio (Solo MECE)" },
    { id: "4", nombre: "Presentación y Defensa de intervención con Material Didáctico (Solo MECE)" },
    { id: "5", nombre: "Portafolio de Evidencias" }
];

const instituciones = [
    { id: "1", nombre: "INAOE (Instituto Nacional de Astrofísica, Óptica y Electrónica)" }
];

const entidadesFederativas = [
    { id: "21", nombre: "Puebla" }
];

function cargarDatosFijos() {
    const selectArea = document.getElementById('course_cvecourse');
    areasInaoep.forEach(area => {
        let option = document.createElement('option');
        option.value = area.id;
        option.text = area.nombre;
        selectArea.add(option);
    });

    const selectModalidad = document.getElementById('expedition_iddegreemodality');
    modalidadesInaoep.forEach(mod => {
        let option = document.createElement('option');
        option.value = mod.id;
        option.text = mod.nombre;
        selectModalidad.add(option);
    });

    const selectInstitucion = document.getElementById('institution');
    instituciones.forEach(inst => {
        let option = document.createElement('option');
        option.value = inst.id;
        option.text = inst.nombre;
        selectInstitucion.add(option);
    });

    const selectExpeditionState = document.getElementById('expedition-state');
    const selectAntecedentState = document.getElementById('antecedent-state');
    
    entidadesFederativas.forEach(entidad => {
        let optionExp = document.createElement('option');
        optionExp.value = entidad.id;
        optionExp.text = entidad.nombre;
        selectExpeditionState.add(optionExp);

        let optionAnt = document.createElement('option');
        optionAnt.value = entidad.id;
        optionAnt.text = entidad.nombre;
        selectAntecedentState.add(optionAnt);
    });
}

document.addEventListener("DOMContentLoaded", cargarDatosFijos);