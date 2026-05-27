const CONTROLLER_URL = (typeof window !== 'undefined' && window.CONTROLLER_URL)
    ? window.CONTROLLER_URL
    : '../../controllers/preregistro/controller_preregistro.php';

const MAX_MB    = 10;
const MAX_BYTES = MAX_MB * 1024 * 1024;
const EXT_VALIDAS = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];

const REGEX_EMAIL  = /^[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
const REGEX_CURP   = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/;
const REGEX_CED    = /^[0-9]{6,8}$/;
const REGEX_ACENTO = /[áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜâêîôûÂÊÎÔÛñÑ]/;

function setError(fieldId, msg) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    el.classList.add('is-invalid');
    el.classList.remove('is-valid');
    let fb = document.getElementById('err-' + fieldId);
    if (!fb) {
        fb = document.createElement('div');
        fb.id        = 'err-' + fieldId;
        fb.className = 'invalid-feedback';
        el.parentNode.appendChild(fb);
    }
    fb.textContent  = msg;
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
    document.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => { el.textContent = ''; el.style.display = 'none'; });
}

function getNacionalidad() {
    const radio = document.querySelector('input[name="nacionalidad"]:checked');
    return radio ? radio.value : '';
}

function toggleCurp() {
    const nac = getNacionalidad();
    const bloqueCurp = document.getElementById('bloque-curp');
    const inputCurp = document.getElementById('professional_curp');
    const inputArch = document.getElementById('archivo_curp');

    if (nac === 'Mexicana') {
        if (bloqueCurp) bloqueCurp.style.display = 'block';
    } else {
        if (bloqueCurp) bloqueCurp.style.display = 'none';
        if (inputCurp) { inputCurp.value = ''; }
        if (inputArch) { inputArch.value = ''; document.getElementById('nombre-archivo_curp').innerHTML = ''; }
        clearError('professional_curp');
        clearError('archivo_curp');
    }
}

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

function quitarAcentos(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function validarFormulario() {
    let valido = true;
    resetValidation();

    const hoy = new Date().toISOString().split('T')[0];

    const nombre = document.getElementById('professional_name').value.trim();
    if (!nombre) { setError('professional_name', 'El nombre es obligatorio.'); valido = false; }
    else if (REGEX_ACENTO.test(nombre)) { setError('professional_name', 'El nombre no debe contener acentos.'); valido = false; }
    else { clearError('professional_name'); }

    const apPat = document.getElementById('professional_surname').value.trim();
    if (!apPat) { setError('professional_surname', 'El apellido paterno es obligatorio.'); valido = false; }
    else if (REGEX_ACENTO.test(apPat)) { setError('professional_surname', 'El apellido paterno no debe contener acentos.'); valido = false; }
    else { clearError('professional_surname'); }

    const apMat = document.getElementById('professional_secondsurname').value.trim();
    if (apMat && REGEX_ACENTO.test(apMat)) { setError('professional_secondsurname', 'El apellido materno no debe contener acentos.'); valido = false; }
    else { clearError('professional_secondsurname'); }

    const email = document.getElementById('professional_email').value.trim();
    if (!REGEX_EMAIL.test(email)) { setError('professional_email', 'Ingresa un correo electrónico válido.'); valido = false; }
    else { clearError('professional_email'); }

    const nac = getNacionalidad();
    if (!nac) { setError('nacionalidad', 'Selecciona tu nacionalidad.'); valido = false; }
    else { clearError('nacionalidad'); }

    if (nac === 'Mexicana') {
        const curp = document.getElementById('professional_curp').value.trim().toUpperCase();
        if (!curp) { setError('professional_curp', 'El CURP es obligatorio para ciudadanos mexicanos.'); valido = false; }
        else if (!REGEX_CURP.test(curp)) { setError('professional_curp', 'El CURP no tiene el formato correcto (18 caracteres válidos).'); valido = false; }
        else { clearError('professional_curp'); }

        const archCurp = document.getElementById('archivo_curp');
        if (!archCurp || !archCurp.files.length) { setError('archivo_curp', 'Debes adjuntar tu CURP.'); valido = false; }
        else { if (!validarArchivo('archivo_curp')) valido = false; }
    }

    const gradoRadio = document.querySelector('input[name="course_type"]:checked');
    const grado = gradoRadio ? gradoRadio.value : '';
    if (!grado) { setError('course_type', 'Selecciona el grado del título.'); valido = false; }
    else { clearError('course_type'); }

    const area = document.getElementById('course_cvecourse').value;
    if (!area || area === 'null') { setError('course_cvecourse', 'Selecciona el área del título.'); valido = false; }
    else { clearError('course_cvecourse'); }

    const fInicio = document.getElementById('course_startdate').value;
    if (!fInicio) { setError('course_startdate', 'La fecha de inicio de grado es obligatoria.'); valido = false; }
    else if (fInicio > hoy) { setError('course_startdate', 'La fecha de inicio no puede ser una fecha futura.'); valido = false; }
    else { clearError('course_startdate'); }

    const fDefensa = document.getElementById('expedition_dateprofessionalexam').value;
    if (!fDefensa) { setError('expedition_dateprofessionalexam', 'La fecha de defensa/examen es obligatoria.'); valido = false; }
    else if (fDefensa > hoy) { setError('expedition_dateprofessionalexam', 'La fecha de defensa no puede ser una fecha futura.'); valido = false; }
    else if (fInicio && fDefensa <= fInicio) { setError('expedition_dateprofessionalexam', 'La fecha de defensa debe ser posterior a la fecha de inicio.'); valido = false; }
    else { clearError('expedition_dateprofessionalexam'); }

    const modalidad = document.getElementById('expedition_iddegreemodality').value;
    if (!modalidad || modalidad === 'null') { setError('expedition_iddegreemodality', 'Selecciona la modalidad de titulación.'); valido = false; }
    else { clearError('expedition_iddegreemodality'); }

    const cedula = document.getElementById('antecedent_document').value.trim();
    if (!cedula) { setError('antecedent_document', 'La cédula profesional es obligatoria.'); valido = false; }
    else if (!REGEX_CED.test(cedula)) { setError('antecedent_document', 'La cédula debe ser numérica (6 a 8 dígitos).'); valido = false; }
    else { clearError('antecedent_document'); }

    const archivosObligatorios = [
        { id: 'archivo_certificado' },
        { id: 'archivo_acta_examen' },
        { id: 'archivo_titulo_grado' },
        { id: 'archivo_cedula' }
    ];

    archivosObligatorios.forEach(function({ id }) {
        const inp = document.getElementById(id);
        if (!inp || !inp.files.length) { setError(id, `Debes adjuntar este documento.`); valido = false; }
        else { if (!validarArchivo(id)) valido = false; }
    });

    return valido;
}

function updateProgress(step) {
    if (step > 4) return;
    const fill = document.getElementById('progress-fill');
    const text = document.getElementById('progress-text');
    const percent = (step / 4) * 100;
    if(fill) fill.style.width = percent + '%';
    if(text) text.innerHTML = 'Paso ' + step + ' / 4';
}

function showStep(step) {
    document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
    const target = document.getElementById('step-' + step);
    if(target) target.classList.add('active');
    updateProgress(step);
    window.scrollTo(0, 0);
}

function avanzarPaso(actual, siguiente) {
    showStep(siguiente);
}

function retrocederPaso(anterior) {
    showStep(anterior);
}

function finalizarFormulario() {
    document.getElementById('form-preregistro').dispatchEvent(new Event('submit', { cancelable: true }));
}

function enviarFormulario(e) {
    e.preventDefault();

    if (!validarFormulario()) {
        const primerError = document.querySelector('.is-invalid');
        if (primerError) {
            const stepPadre = primerError.closest('.form-step');
            if (stepPadre) {
                const stepNum = stepPadre.id.split('-')[1];
                if (typeof showStep === 'function') showStep(stepNum);
            }
            primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
    }

    const btn = document.getElementById('btn-enviar');
    const loader = document.getElementById('loader-envio');

    if(btn) btn.disabled = true;
    if (loader) loader.style.display = '';

    const form = document.getElementById('form-preregistro');
    const fd = new FormData(form);
    fd.append('action', 9);

    const selectArea = document.getElementById('course_cvecourse');
    if (selectArea && selectArea.selectedIndex >= 0) {
        fd.set('course_name', selectArea.options[selectArea.selectedIndex].text);
    }

    const gradoChecked = document.querySelector('input[name="course_type"]:checked');
    if (gradoChecked) fd.set('course_type', gradoChecked.value);

    fd.set('nacionalidad', getNacionalidad());

    fetch(CONTROLLER_URL, { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (loader) loader.style.display = 'none';

            if (data.ok) {
                showStep(4);
            } else {
                if(btn) btn.disabled = false;
                if (data.errores) {
                    let showed = false;
                    Object.entries(data.errores).forEach(function([campo, msg]) {
                        if (campo === 'general') return;
                        const el = document.getElementById(campo);
                        if (el) { setError(campo, msg); showed = true; }
                    });

                    const primerError = document.querySelector('.is-invalid');
                    if (primerError) {
                        const stepPadre = primerError.closest('.form-step');
                        if (stepPadre) {
                            const stepNum = stepPadre.id.split('-')[1];
                            if (typeof showStep === 'function') showStep(stepNum);
                        }
                        primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    if (data.errores.general) alert(data.errores.general);
                    else if (!showed) alert('Ocurrió un error al enviar el formulario.');
                } else {
                    alert('Ocurrió un error al enviar el formulario.');
                }
            }
        })
        .catch(function() {
            if (loader) loader.style.display = 'none';
            if(btn) btn.disabled = false;
            alert('Error de conexión.');
        });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[name="course_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() { clearError('course_type'); });
    });

    document.querySelectorAll('input[name="nacionalidad"]').forEach(function(radio) {
        radio.addEventListener('change', toggleCurp);
    });

    const inputCurp = document.getElementById('professional_curp');
    if (inputCurp) {
        inputCurp.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    ['professional_name', 'professional_surname', 'professional_secondsurname'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() {
                const pos = this.selectionStart;
                this.value = quitarAcentos(this.value).toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }
    });

    ['archivo_curp', 'archivo_certificado', 'archivo_acta_examen', 'archivo_titulo_grado', 'archivo_cedula'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function() { validarArchivo(id); });
    });

    const fInicioEl = document.getElementById('course_startdate');
    const fDefensaEl = document.getElementById('expedition_dateprofessionalexam');
    const hoy = new Date().toISOString().split('T')[0];

    if (fInicioEl) {
        fInicioEl.max = hoy;
        fInicioEl.addEventListener('change', function() {
            if (this.value > hoy) setError('course_startdate', 'La fecha no puede ser futura.');
            else clearError('course_startdate');
            if (fDefensaEl && fDefensaEl.value) fDefensaEl.dispatchEvent(new Event('change'));
        });
    }

    if (fDefensaEl) {
        fDefensaEl.max = hoy;
        fDefensaEl.addEventListener('change', function() {
            const fInicio = fInicioEl ? fInicioEl.value : '';
            if (this.value > hoy) setError('expedition_dateprofessionalexam', 'La fecha no puede ser futura.');
            else if (fInicio && this.value <= fInicio) setError('expedition_dateprofessionalexam', 'Debe ser posterior a inicio.');
            else clearError('expedition_dateprofessionalexam');
        });
    }

    const cedulaEl = document.getElementById('antecedent_document');
    if (cedulaEl) {
        cedulaEl.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    ['course_cvecourse', 'expedition_iddegreemodality'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function() { clearError(id); });
    });

    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const labelEl = document.getElementById('nombre-' + this.id);
            if (!labelEl) return;
            if (this.files && this.files.length) {
                const file = this.files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                const icono = (ext === 'pdf') ? '<i class="fa-solid fa-file-pdf"></i> ' : '<i class="fa-solid fa-file-image"></i> ';
                const kb = (file.size / 1024).toFixed(0);
                labelEl.innerHTML = icono + file.name + ' <span style="color:var(--texto-suave)">(' + kb + ' KB)</span>';
                labelEl.classList.add('tiene-archivo');
                this.classList.remove('is-invalid');
                this.closest('.upload-area').classList.remove('is-invalid-file');
            } else {
                labelEl.textContent = '';
                labelEl.classList.remove('tiene-archivo');
            }
        });
    });

    document.querySelectorAll('.upload-area').forEach(function(area) {
        area.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('drag-over'); });
        area.addEventListener('dragleave', function() { this.classList.remove('drag-over'); });
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            const input = this.querySelector('input[type="file"]');
            if (input && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    const form = document.getElementById('form-preregistro');
    if (form) form.addEventListener('submit', enviarFormulario);
});