$(document).ready(function () {

    var registrosData = [];
    var idSeleccionado = null;
    var tabActual = 'preregistrados';
    var CONTROLLER = '../../controllers/preregistro/controller_preregistro.php';
    var LOGIN_CONTROLLER = '../../controllers/signin/controller_login.php';

    cargarDatos(1);

    $('#tabsSecretaria .nav-link').on('click', function() {
        $('#tabsSecretaria .nav-link').removeClass('active');
        $(this).addClass('active');
        tabActual = $(this).data('tab');
        
        if (tabActual === 'preregistrados') {
            cargarDatos(1);
        } else if (tabActual === 'creados') {
            cargarDatos(2);
        } else {
            $('#tbody-registros').html('<tr><td colspan="4" class="text-center text-muted py-4">Sin registros en esta sección.</td></tr>');
        }
    });

    $('#btnLogout').on('click', function () {
        bootbox.confirm({
            title: "Cerrar Sesión",
            message: "¿Deseas cerrar sesión?",
            buttons: {
                confirm: { label: 'Sí', className: 'btn-danger' },
                cancel:  { label: 'No', className: 'btn-secondary' }
            },
            callback: function (result) {
                if (result) {
                    $.post(LOGIN_CONTROLLER, { action: 99 }, function () {
                        location.href = '../../index.php';
                    }).fail(function () {
                        location.href = '../../index.php';
                    });
                }
            }
        });
    });

    $(document).on('click', '.btn-accion-naranja', function () {
        var id = $(this).data('id');
        abrirDetalle(id);
    });

    // Evento arreglado: Usa las nuevas clases btn-doc para evitar choques con Bootstrap
    $(document).on('click', '.btn-view-pdf', function() {
        $('.btn-view-pdf').removeClass('active');
        $(this).addClass('active');
        
        var fileUrl = $(this).data('file');
        $('#pdf-placeholder').hide();
        $('#pdf-frame').attr('src', fileUrl).show();
    });

    $('#btn-promover').on('click', function () {
        if (!idSeleccionado) return;
        bootbox.confirm({
            title: "Confirmar acción",
            message: "¿Deseas pasar este pre-registro a <strong>Creados</strong> para iniciar el proceso de titulación?",
            buttons: {
                confirm: { label: 'Sí, crear registro', className: 'btn-success' },
                cancel:  { label: 'Cancelar', className: 'btn-secondary' }
            },
            callback: function (result) {
                if (!result) return;
                mostrarLoading(true);
                $.post(CONTROLLER, { action: 12, prereg_id: idSeleccionado, id_titledata: idSeleccionado }, function (resp) {
                    mostrarLoading(false);
                    $('#modalDetalle').modal('hide');
                    if (resp && resp.ok) {
                        cargarDatos(1);
                        actualizarConteoTabs();
                    } else {
                        bootbox.alert({ title: 'Error', message: 'No se pudo actualizar el estado.' });
                    }
                }, 'json').fail(function () {
                    mostrarLoading(false);
                    bootbox.alert({ title: 'Error', message: 'Error de conexión con el servidor.' });
                });
            }
        });
    });

    function cargarDatos(status) {
        mostrarLoading(true);
        $('#tbody-registros').html('<tr><td colspan="4" class="text-center text-muted py-4"><i class="fa-solid fa-spinner fa-spin"></i> Cargando...</td></tr>');

        $.post(CONTROLLER, { action: 10 }, function (data) {
            mostrarLoading(false);
            registrosData = data || [];
            
            var pre = registrosData.filter(function(r) { return r.status == 1; });
            var creados = registrosData.filter(function(r) { return r.status == 2; });
            
            $('#count-pre').text(pre.length);
            $('#count-creados').text(creados.length);
            
            if (status === 1) renderTabla(pre);
            if (status === 2) renderTabla(creados);
            
        }, 'json').fail(function () {
            mostrarLoading(false);
            $('#tbody-registros').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar los datos.</td></tr>');
        });
    }

    function actualizarConteoTabs() {
        $.post(CONTROLLER, { action: 10 }, function (data) {
            if(data) {
                var pre = data.filter(function(r) { return r.status == 1; });
                var creados = data.filter(function(r) { return r.status == 2; });
                $('#count-pre').text(pre.length);
                $('#count-creados').text(creados.length);
            }
        }, 'json');
    }

    function renderTabla(datos) {
        if (!datos || datos.length === 0) {
            $('#tbody-registros').html('<tr><td colspan="4" class="text-center text-muted py-4">No hay registros en esta sección.</td></tr>');
            return;
        }

        var html = '';
        $.each(datos, function (i, r) {
            var nombre = ((r.professional_name || '') + ' ' + (r.professional_surname || '') + ' ' + (r.professional_secondsurname || '')).trim().toUpperCase();
            var programa = (r.course_name || '').toUpperCase();
            var fecha = (r.fecha_registro || '').split(' ')[0]; 

            html += '<tr>' +
                '<td style="color:#333;">' + nombre + '</td>' +
                '<td style="color:#333;">' + programa + '</td>' +
                '<td class="text-center" style="color:#4b6cb7;">' + fecha + '</td>' +
                '<td class="text-center">' +
                    '<button class="btn-accion-naranja" data-id="' + r.id_titledata + '">' +
                    '<i class="fa-solid fa-pen-to-square"></i></button>' +
                '</td>' +
            '</tr>';
        });

        $('#tbody-registros').html(html);
    }

    // Botones rediseñados para no depender de Bootstrap
    function getFileLink(filename, label) {
        if (!filename) return '<div class="doc-missing"><i class="fa-solid fa-xmark"></i> Sin ' + label + '</div>';
        return '<button type="button" class="btn-doc btn-view-pdf" data-file="../../uploads/preregistro/' + filename + '"><i class="fa-solid fa-file-pdf"></i> ' + label + '</button>';
    }

    function abrirDetalle(id) {
        mostrarLoading(true);
        idSeleccionado = id;

        $.post(CONTROLLER, { action: 11, id: id }, function (data) {
            mostrarLoading(false);
            if (!data || data.error) {
                bootbox.alert({ title: 'Error', message: 'No se encontró el registro.' });
                return;
            }

            var nombre = ((data.professional_name || '') + ' ' + (data.professional_surname || '') + ' ' + (data.professional_secondsurname || '')).trim().toUpperCase();

            // NUEVO LAYOUT: Datos arriba (2 col), Botones en medio (horizontal), Visor abajo
            var html =
                '' +
                '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<div class="card-detalle">' +
                            '<h6><i class="fa-solid fa-id-card"></i> Datos Personales</h6>' +
                            '<p class="mb-1"><strong>Nombre completo:</strong> ' + nombre + '</p>' +
                            '<p class="mb-1"><strong>CURP:</strong> ' + (data.professional_curp || '-').toUpperCase() + '</p>' +
                            '<p class="mb-1"><strong>Correo:</strong> ' + (data.professional_email || '-') + '</p>' +
                            '<p class="mb-0"><strong>Folio:</strong> ' + (data.controlinvoice || '-') + '</p>' +
                        '</div>' +
                    '</div>' +
                    
                    '<div class="col-md-6">' +
                        '<div class="card-detalle">' +
                            '<h6><i class="fa-solid fa-graduation-cap"></i> Datos Académicos</h6>' +
                            '<p class="mb-1"><strong>Programa:</strong> ' + (data.course_name || '-').toUpperCase() + '</p>' +
                            '<p class="mb-1"><strong>Fecha de inicio:</strong> ' + (data.course_startdate || '-') + '</p>' +
                            '<p class="mb-1"><strong>Modalidad:</strong> ' + (data.expedition_degreemodality || '-') + '</p>' +
                            '<p class="mb-1"><strong>Defensa/Examen:</strong> ' + (data.expedition_dateprofessionalexam || '-') + '</p>' +
                            '<p class="mb-0"><strong>Cédula Anterior:</strong> ' + (data.antecedent_document || '-') + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +

                '' +
                '<div class="mt-2 mb-3">' +
                    '<h6 style="color:#0c315e; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-folder-open"></i> Documentos Adjuntos</h6>' +
                    '<div class="d-flex flex-wrap">' +
                        getFileLink(data.archivo_curp, 'CURP') +
                        getFileLink(data.archivo_certificado, 'Certificado') +
                        getFileLink(data.archivo_acta_examen, 'Acta de Examen') +
                        getFileLink(data.archivo_titulo_grado, 'Título de Grado') +
                        getFileLink(data.archivo_cedula, 'Cédula') +
                    '</div>' +
                '</div>' +
                
                '' +
                '<div class="row">' +
                    '<div class="col-12">' +
                        '<div class="pdf-viewer-container shadow-sm">' +
                            '<iframe id="pdf-frame" src="" width="100%" height="100%" style="border:none; display:none;"></iframe>' +
                            '<div id="pdf-placeholder" class="text-center" style="color: #adb5bd;">' +
                                '<i class="fa-solid fa-file-pdf" style="font-size: 4rem; margin-bottom:15px;"></i>' +
                                '<h5>Visor de Documentos</h5>' +
                                '<p>Haz clic en uno de los documentos de arriba para visualizarlo.</p>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            $('#modal-body-detalle').html(html);
            
            if (data.status == 1) {
                $('#btn-promover').data('id', id).show();
            } else {
                $('#btn-promover').hide();
            }
            
            $('#modalDetalle').modal('show');

        }, 'json').fail(function () {
            mostrarLoading(false);
            bootbox.alert({ title: 'Error', message: 'Error al obtener el detalle.' });
        });
    }

    function mostrarLoading(show) {
        $('#loading-overlay').css('display', show ? 'block' : 'none');
    }
});