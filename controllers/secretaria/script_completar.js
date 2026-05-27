$(document).ready(function() {
    var CONTROLLER = '../../controllers/preregistro/controller_preregistro.php';
    var id = $('#id_titledata').val();

    cargarDatosAlumno();

    $('#btn-cancelar').on('click', function() {
        window.location.href = 'secretaria.php';
    });

    $('#form-completar').on('submit', function(e) {
        e.preventDefault();
        
        var fd = new FormData(this);
        fd.append('action', 15); 
        
        fd.append('institution_nameinstitution', $('#institution_cveinstitution option:selected').text());
        fd.append('course_name', $('#course_cvecourse option:selected').text());
        fd.append('course_reconnaissanceauthorization', $('#course_idreconnaissanceauthorization option:selected').text());
        fd.append('expedition_legalbasissocialservice', $('#expedition_idlegalbasissocialservice option:selected').text());
        fd.append('expedition_state', $('#expedition_idstate option:selected').text());
        fd.append('antecedent_typestudy', $('#antecedent_idtypestudy option:selected').text());
        fd.append('antecedent_state', $('#antecedent_idstate option:selected').text());

        $.ajax({
            url: CONTROLLER,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (resp && resp.ok) {
                    bootbox.alert({
                        title: 'Éxito',
                        message: 'El registro se ha completado y pasado a Creados.',
                        callback: function() {
                            window.location.href = 'secretaria.php';
                        }
                    });
                } else {
                    bootbox.alert('Error: ' + (resp.error || 'No se pudo guardar.'));
                }
            },
            error: function() {
                bootbox.alert('Error de conexión con el servidor.');
            }
        });
    });

    function cargarDatosAlumno() {
        $.post(CONTROLLER, { action: 11, id: id }, function(data) {
            if(data && !data.error) {
                $('#professional_curp').val(data.professional_curp);
                $('#professional_name').val(data.professional_name);
                $('#professional_surname').val(data.professional_surname);
                $('#professional_secondsurname').val(data.professional_secondsurname);
                $('#professional_email').val(data.professional_email);
                $('#expedition_degreemodality').val(data.expedition_degreemodality);
                $('#antecedent_document').val(data.antecedent_document);

                if (data.course_startdate) {
                    $('#course_startdate').val(data.course_startdate.split(' ')[0]);
                }
                
                if (data.expedition_dateprofessionalexam) {
                    $('#expedition_dateprofessionalexam').val(data.expedition_dateprofessionalexam.split(' ')[0]);
                }

                if (data.institution_cveinstitution) {
                    $('#institution_cveinstitution').val(data.institution_cveinstitution);
                }

                if (data.course_cvecourse) {
                    $('#course_cvecourse').val(data.course_cvecourse);
                }

                if (data.expedition_idstate) {
                    $('#expedition_idstate').val(data.expedition_idstate);
                }

                if (data.antecedent_idstate) {
                    $('#antecedent_idstate').val(data.antecedent_idstate);
                }
            }
        }, 'json');
    }
});