'use strict';

// ─────────────────────────────────────────────────────────────────────────────
// Constantes
// ─────────────────────────────────────────────────────────────────────────────
const CONTROLLER_URL = (typeof window !== 'undefined' && window.CONTROLLER_URL)
    ? window.CONTROLLER_URL
    : '../../controllers/preregistro/controller_preregistro.php';
const MAX_MB         = 10;
const MAX_BYTES      = MAX_MB * 1024 * 1024;
const TIPOS_VALIDOS  = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
const EXT_VALIDAS    = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];

const REGEX_EMAIL   = /^[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
const REGEX_CURP    = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/;
const REGEX_CED     = /^[0-9]{6,8}$/;
const REGEX_ACENTO  = /[áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜâêîôûÂÊÎÔÛñÑ]/;

// ─────────────────────────────────────────────────────────────────────────────
// Helpers de UI
// ─────────────────────────────────────────────────────────────────────────────

function setError(fieldId, msg) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    el.classList.add('is-invalid');
    el.classList.remove('is-valid');
    let fb = document.getElementById('err-' + fieldId);
    if (!fb) {
        fb = document.createElement('div');
        fb.id = 'err-' + fieldId;
        fb.className = 'invalid-feedback';
        el.parentNode.appendChild(fb);
    }
    fb.textContent = msg;
    fb.style.display = 'block';
}

function clearError(fieldId) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    el.classList.remove('is-invalid');
    el.classList.add('is-valid');
    const fb = document.getElementById('err-' + fieldId);
    if (fb) { fb.textContent = ''; fb.style.display = 'none'; }
}

function resetValidation() {
    document.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// Control de visibilidad del campo CURP según nacionalidad
// ─────────────────────────────────────────────────────────────────────────────

function getNacionalidad() {
    const radio = document.querySelector('input[name="nacionalidad"]:checked');
    return radio ? radio.value : '';
}

function toggleCurp() {
    const nac        = getNacionalidad();
    const bloqueCurp = document.getElementById('bloque-curp');
    const inputCurp  = document.getElementById('professional_curp');
    const inputArch  = document.getElementById('archivo_curp');

    if (nac === 'Mexicana') {
        bloqueCurp.style.display = '';
        inputCurp.required = true;
        inputArch.required = true;
    } else {
        bloqueCurp.style.display = 'none';
        inputCurp.required = false;
        inputArch.required = false;
        inputCurp.value = '';
        clearError('professional_curp');
        clearError('archivo_curp');
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Validación de archivos individuales al seleccionarlos
// ─────────────────────────────────────────────────────────────────────────────

function validarArchivo(inputId) {
    const input = document.getElementById(inputId);
    if (!input || !input.files.length) return true;

    const file = input.files[0];
    const ext  = file.name.split('.').pop().toLowerCase();

    if (!EXT_VALIDAS.includes(ext)) {
        setError(inputId, 'Solo se aceptan archivos PDF, JPG o PNG.');
        input.value = '';
        return false;
    }
    if (file.size > MAX_BYTES) {
        setError(inputId, `El archivo no debe superar ${MAX_MB} MB.`);
        input.value = '';
        return false;
    }
    clearError(inputId);
    return true;
}

// ─────────────────────────────────────────────────────────────────────────────
// Eliminar acentos automáticamente al escribir
// ─────────────────────────────────────────────────────────────────────────────

function quitarAcentos(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

// ─────────────────────────────────────────────────────────────────────────────
// Validación completa del formulario antes de enviar
// ─────────────────────────────────────────────────────────────────────────────

function validarFormulario() {
    let valido = true;
    resetValidation();

    const hoy = new Date().toISOString().split('T')[0];

    // ── Datos personales ──────────────────────────────────────────────────
    const nombre = document.getElementById('professional_name').value.trim();
    if (!nombre) {
        setError('professional_name', 'El nombre es obligatorio.');
        valido = false;
    } else if (REGEX_ACENTO.test(nombre)) {
        setError('professional_name', 'El nombre no debe contener acentos.');
        valido = false;
    } else {
        clearError('professional_name');
    }

    const apPat = document.getElementById('professional_surname').value.trim();
    if (!apPat) {
        setError('professional_surname', 'El apellido paterno es obligatorio.');
        valido = false;
    } else if (REGEX_ACENTO.test(apPat)) {
        setError('professional_surname', 'El apellido paterno no debe contener acentos.');
        valido = false;
    } else {
        clearError('professional_surname');
    }

    const apMat = document.getElementById('professional_secondsurname').value.trim();
    if (apMat && REGEX_ACENTO.test(apMat)) {
        setError('professional_secondsurname', 'El apellido materno no debe contener acentos.');
        valido = false;
    } else {
        clearError('professional_secondsurname');
    }

    const email = document.getElementById('professional_email').value.trim();
    if (!REGEX_EMAIL.test(email)) {
        setError('professional_email', 'Ingresa un correo electrónico válido.');
        valido = false;
    } else {
        clearError('professional_email');
    }

    const nac = getNacionalidad();
    if (!nac) {
        setError('nacionalidad', 'Selecciona tu nacionalidad.');
        valido = false;
    } else {
        clearError('nacionalidad');
    }

    // CURP solo si es mexicano
    if (nac === 'Mexicana') {
        const curp = document.getElementById('professional_curp').value.trim().toUpperCase();
        if (!curp) {
            setError('professional_curp', 'El CURP es obligatorio para ciudadanos mexicanos.');
            valido = false;
        } else if (!REGEX_CURP.test(curp)) {
            setError('professional_curp', 'El CURP no tiene el formato correcto (18 caracteres válidos).');
            valido = false;
        } else {
            clearError('professional_curp');
        }

        const archCurp = document.getElementById('archivo_curp');
        if (!archCurp.files.length) {
            setError('archivo_curp', 'Debes adjuntar tu CURP.');
            valido = false;
        } else {
            if (!validarArchivo('archivo_curp')) valido = false;
        }
    }

    // ── Título que solicita ───────────────────────────────────────────────
    const gradoRadio = document.querySelector('input[name="course_type"]:checked');
    const grado = gradoRadio ? gradoRadio.value : '';
    if (!grado) { setError('course_type', 'Selecciona el grado del título.'); valido = false; }
    else clearError('course_type');

    const area = document.getElementById('course_cvecourse').value;
    if (!area) { setError('course_cvecourse', 'Selecciona el área del título.'); valido = false; }
    else clearError('course_cvecourse');

    const fInicio = document.getElementById('course_startdate').value;
    if (!fInicio) {
        setError('course_startdate', 'La fecha de inicio de grado es obligatoria.');
        valido = false;
    } else if (fInicio > hoy) {
        setError('course_startdate', 'La fecha de inicio no puede ser una fecha futura.');
        valido = false;
    } else {
        clearError('course_startdate');
    }

    const fDefensa = document.getElementById('expedition_dateprofessionalexam').value;
    if (!fDefensa) {
        setError('expedition_dateprofessionalexam', 'La fecha de defensa/examen es obligatoria.');
        valido = false;
    } else if (fDefensa > hoy) {
        setError('expedition_dateprofessionalexam', 'La fecha de defensa no puede ser una fecha futura.');
        valido = false;
    } else if (fInicio && fDefensa <= fInicio) {
        setError('expedition_dateprofessionalexam', 'La fecha de defensa debe ser posterior a la fecha de inicio.');
        valido = false;
    } else {
        clearError('expedition_dateprofessionalexam');
    }

    const modalidad = document.getElementById('expedition_iddegreemodality').value;
    if (!modalidad) { setError('expedition_iddegreemodality', 'Selecciona la modalidad de titulación.'); valido = false; }
    else clearError('expedition_iddegreemodality');

    // ── Antecedentes académicos ───────────────────────────────────────────
    const cedula = document.getElementById('antecedent_document').value.trim();
    if (!cedula) {
        setError('antecedent_document', 'La cédula profesional es obligatoria.');
        valido = false;
    } else if (!REGEX_CED.test(cedula)) {
        setError('antecedent_document', 'La cédula debe ser numérica (6 a 8 dígitos).');
        valido = false;
    } else {
        clearError('antecedent_document');
    }

    const archivosObligatorios = [
        { id: 'archivo_certificado',  label: 'el certificado del grado anterior' },
        { id: 'archivo_acta_examen',  label: 'el acta de examen del grado anterior' },
        { id: 'archivo_titulo_grado', label: 'el título de grado anterior' },
        { id: 'archivo_cedula',       label: 'la cédula profesional' },
    ];

    archivosObligatorios.forEach(({ id, label }) => {
        const inp = document.getElementById(id);
        if (!inp.files.length) {
            setError(id, `Debes adjuntar ${label}.`);
            valido = false;
        } else {
            if (!validarArchivo(id)) valido = false;
        }
    });

    return valido;
}

// ─────────────────────────────────────────────────────────────────────────────
// Envío del formulario
// ─────────────────────────────────────────────────────────────────────────────

function enviarFormulario(e) {
    e.preventDefault();

    if (!validarFormulario()) {
        const primerError = document.querySelector('.is-invalid');
        if (primerError) primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const btn    = document.getElementById('btn-enviar');
    const loader = document.getElementById('loader-envio');
    btn.disabled = true;
    if (loader) loader.style.display = '';

    const form = document.getElementById('form-preregistro');
    const fd   = new FormData(form);
    fd.append('action', 9);

    // Incluir el texto del área seleccionada
    const selectArea = document.getElementById('course_cvecourse');
    if (selectArea && selectArea.selectedIndex >= 0) {
        fd.set('course_name', selectArea.options[selectArea.selectedIndex].text);
    }

    // Capturar texto de institución
    const selectInst = document.getElementById('institution');
    if (selectInst && selectInst.selectedIndex >= 0) {
        fd.set('institution_name', selectInst.options[selectInst.selectedIndex].text);
    }

    // Capturar textos de selects de entidad federativa
    const selExpState = document.getElementById('expedition-state');
    if (selExpState && selExpState.selectedIndex >= 0) {
        fd.set('expedition_state_text', selExpState.options[selExpState.selectedIndex].text);
    }

    const selAntState = document.getElementById('antecedent-state');
    if (selAntState && selAntState.selectedIndex >= 0) {
        fd.set('antecedent_state_text', selAntState.options[selAntState.selectedIndex].text);
    }

    // Incluir course_type del radio seleccionado
    const gradoChecked = document.querySelector('input[name="course_type"]:checked');
    if (gradoChecked) fd.set('course_type', gradoChecked.value);

    // Incluir nacionalidad del radio seleccionado
    fd.set('nacionalidad', getNacionalidad());

    // Mapear campos con guiones a los nombres esperados por el backend
    [
        ['date-end', 'date_end'],
        ['date-expedition', 'date_expedition'],
        ['social-service', 'social_service'],
        ['social-service-legal', 'social_service_legal'],
        ['expedition-state', 'expedition_state'],
        ['antecedent-institution', 'antecedent_institution'],
        ['antecedent-type-study', 'antecedent_type_study'],
        ['antecedent-finich-date', 'antecedent_finich_date'],
        ['antecedent-state', 'antecedent_state']
    ].forEach(([id, name]) => {
        const el = document.getElementById(id);
        if (el && el.value !== undefined) {
            fd.set(name, el.value);
        }
    });

    fetch(CONTROLLER_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (loader) loader.style.display = 'none';
            if (data.ok) {
                // Avanzar al step-4 (pantalla de éxito)
                showStep(4);
            } else {
                btn.disabled = false;
                if (data.errores) {
                    let showed = false;
                    Object.entries(data.errores).forEach(([campo, msg]) => {
                        if (campo === 'general') return;
                        const el = document.getElementById(campo);
                        if (el) {
                            setError(campo, msg);
                            showed = true;
                        }
                    });
                    const primerError = document.querySelector('.is-invalid');
                    if (primerError) primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (data.errores.general) {
                        alert(data.errores.general);
                    } else if (!showed) {
                        alert('Ocurrió un error al enviar el formulario. Intenta de nuevo.');
                    }
                } else {
                    alert('Ocurrió un error al enviar el formulario. Intenta de nuevo.');
                }
            }
        })
        .catch(() => {
            if (loader) loader.style.display = 'none';
            btn.disabled = false;
            alert('Error de conexión. Verifica tu internet e intenta de nuevo.');
        });
}

// ─────────────────────────────────────────────────────────────────────────────
// Init
// ─────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', function () {

    // Evento de grado → las áreas están hardcodeadas, no requieren carga dinámica
    // Se mantiene el listener por si en el futuro se requiere filtrar por grado
    document.querySelectorAll('input[name="course_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            clearError('course_type');
        });
    });

    // Evento de nacionalidad → mostrar/ocultar CURP (radio buttons)
    document.querySelectorAll('input[name="nacionalidad"]').forEach(radio => {
        radio.addEventListener('change', toggleCurp);
    });

    // Convertir CURP a mayúsculas automáticamente
    const inputCurp = document.getElementById('professional_curp');
    if (inputCurp) {
        inputCurp.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    }

    // Convertir nombre y apellidos a mayúsculas y quitar acentos automáticamente
    ['professional_name', 'professional_surname', 'professional_secondsurname'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = quitarAcentos(this.value).toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }
    });

    // Validar archivos al seleccionarlos
    ['archivo_curp', 'archivo_certificado', 'archivo_acta_examen',
     'archivo_titulo_grado', 'archivo_cedula'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', () => validarArchivo(id));
    });

    // Validar fechas en tiempo real
    const fInicioEl  = document.getElementById('course_startdate');
    const fDefensaEl = document.getElementById('expedition_dateprofessionalexam');
    const hoy        = new Date().toISOString().split('T')[0];

    if (fInicioEl) {
        fInicioEl.max = hoy;
        fInicioEl.addEventListener('change', function () {
            if (this.value > hoy) {
                setError('course_startdate', 'La fecha de inicio no puede ser una fecha futura.');
            } else {
                clearError('course_startdate');
            }
            if (fDefensaEl && fDefensaEl.value) {
                fDefensaEl.dispatchEvent(new Event('change'));
            }
        });
    }

    if (fDefensaEl) {
        fDefensaEl.addEventListener('change', function () {
            const fInicio = fInicioEl ? fInicioEl.value : '';
            if (fInicio && this.value <= fInicio) {
                setError('expedition_dateprofessionalexam', 'La fecha de defensa debe ser posterior a la fecha de inicio.');
            } else {
                clearError('expedition_dateprofessionalexam');
            }
        });
    }

    // Envío del formulario
    const form = document.getElementById('form-preregistro');
    if (form) form.addEventListener('submit', enviarFormulario);
});


