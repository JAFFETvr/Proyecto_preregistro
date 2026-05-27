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

const instituciones = [
    { id: "1", nombre: "INAOE (Instituto Nacional de Astrofísica, Óptica y Electrónica)" }
];

const entidadesFederativas = [
    { id: "1", nombre: "Aguascalientes" }, { id: "2", nombre: "Baja California" }, { id: "3", nombre: "Baja California Sur" },
    { id: "4", nombre: "Campeche" }, { id: "5", nombre: "Coahuila" }, { id: "6", nombre: "Colima" },
    { id: "7", nombre: "Chiapas" }, { id: "8", nombre: "Chihuahua" }, { id: "9", nombre: "Ciudad de México" },
    { id: "10", nombre: "Durango" }, { id: "11", nombre: "Guanajuato" }, { id: "12", nombre: "Guerrero" },
    { id: "13", nombre: "Hidalgo" }, { id: "14", nombre: "Jalisco" }, { id: "15", nombre: "México" },
    { id: "16", nombre: "Michoacán" }, { id: "17", nombre: "Morelos" }, { id: "18", nombre: "Nayarit" },
    { id: "19", nombre: "Nuevo León" }, { id: "20", nombre: "Oaxaca" }, { id: "21", nombre: "Puebla" },
    { id: "22", nombre: "Querétaro" }, { id: "23", nombre: "Quintana Roo" }, { id: "24", nombre: "San Luis Potosí" },
    { id: "25", nombre: "Sinaloa" }, { id: "26", nombre: "Sonora" }, { id: "27", nombre: "Tabasco" },
    { id: "28", nombre: "Tamaulipas" }, { id: "29", nombre: "Tlaxcala" }, { id: "30", nombre: "Veracruz" },
    { id: "31", nombre: "Yucatán" }, { id: "32", nombre: "Zacatecas" }
];

function cargarDatosFijos() {
    const selectArea = document.getElementById('course_cvecourse');
    if (selectArea) {
        areasInaoep.forEach(area => {
            let option = document.createElement('option');
            option.value = area.id;
            option.text = area.nombre;
            selectArea.add(option);
        });
    }

    const selectInstitucion = document.getElementById('institution_cveinstitution');
    if (selectInstitucion) {
        instituciones.forEach(inst => {
            let option = document.createElement('option');
            option.value = inst.id;
            option.text = inst.nombre;
            selectInstitucion.add(option);
        });
    }

    const selectExpeditionState = document.getElementById('expedition_idstate');
    const selectAntecedentState = document.getElementById('antecedent_idstate');
    
    entidadesFederativas.forEach(entidad => {
        if (selectExpeditionState) {
            let optionExp = document.createElement('option');
            optionExp.value = entidad.id;
            optionExp.text = entidad.nombre;
            selectExpeditionState.add(optionExp);
        }

        if (selectAntecedentState) {
            let optionAnt = document.createElement('option');
            optionAnt.value = entidad.id;
            optionAnt.text = entidad.nombre;
            selectAntecedentState.add(optionAnt);
        }
    });
}

document.addEventListener("DOMContentLoaded", cargarDatosFijos);