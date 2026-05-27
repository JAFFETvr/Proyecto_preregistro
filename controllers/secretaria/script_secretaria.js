$(document).ready(function () {

    var registrosData = [];
    var idSeleccionado = null;
    var tabActual = 'preregistrados';
    var filtroActual = '';
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

    $('#search-alumno').on('input keyup change', function () {
        filtroActual = ($(this).val() || '').trim().toLowerCase();
        aplicarFiltro();
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

    // Seleccionar fila
    $(document).on('click', '#tbody-registros tr', function (e) {
        if ($(e.target).closest('button').length) return;
        var id = $(this).data('id');
        if (!id) return;
        seleccionarRegistro(id);
    });

    // Escuchar tanto al botón de VER como al de EDITAR
    $(document).on('click', '.btn-editar, .btn-ver', function (e) {
        e.stopPropagation();
        var id = $(this).data('id');
        if (id) seleccionarRegistro(id);
        abrirDetalle(id);
    });

    $('#btn-nuevo-registro').on('click', function () {
        if (!idSeleccionado) {
            bootbox.alert({ title: 'Selecciona un alumno', message: 'Da clic en un alumno de la lista para continuar.' });
            return;
        }
        var registro = obtenerRegistroPorId(idSeleccionado);
        if (!registro || registro.status != 1) {
            bootbox.alert({ title: 'Registro no válido', message: 'Selecciona un alumno en Pre-registrados para completar su registro.' });
            return;
        }
        confirmarAccion('crear');
    });

    $(document).on('click', '.btn-view-pdf', function() {
        $('.btn-view-pdf').removeClass('active');
        $(this).addClass('active');
        
        var fileUrl = $(this).data('file');
        $('#pdf-placeholder').hide();
        $('#pdf-frame').attr('src', fileUrl).show();
    });

    $('#btn-promover').on('click', function () {
        if (!idSeleccionado) return;
        var actionType = $(this).data('action-type');
        confirmarAccion(actionType);
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
            $('#stat-pre').text(pre.length);
            
            aplicarFiltro();
            
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
            $('#count-total').text('0 registros');
            return;
        }

        var html = '';
        $.each(datos, function (i, r) {
            var nombre = ((r.professional_name || '') + ' ' + (r.professional_surname || '') + ' ' + (r.professional_secondsurname || '')).trim().toUpperCase();
            var programa = (r.course_name || '').toUpperCase();
            var fecha = (r.fecha_registro || '').split(' ')[0]; 
            var iniciales = obtenerIniciales(r.professional_name, r.professional_surname);

            // Determinar Ícono y Clase dependiendo del Estatus
            var btnIcon = (r.status == 1) ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-pen-to-square"></i>';
            var btnClass = (r.status == 1) ? 'btn-ver' : 'btn-editar';
            var titleTooltip = (r.status == 1) ? 'Ver pre-registro' : 'Editar registro';

            html += '<tr data-id="' + r.id_titledata + '" data-status="' + r.status + '">' +
                '<td><span class="avatar-iniciales">' + iniciales + '</span>' + nombre + '</td>' +
                '<td>' + programa + '</td>' +
                '<td class="text-center"><span class="chip-fecha">' + fecha + '</span></td>' +
                '<td class="text-center">' +
                    '<button class="' + btnClass + '" data-id="' + r.id_titledata + '" title="' + titleTooltip + '">' +
                    btnIcon + '</button>' +
                '</td>' +
            '</tr>';
        });

        $('#tbody-registros').html(html);
        $('#count-total').text(datos.length + ' registros');
    }

    function aplicarFiltro() {
        var datos = obtenerDatosTab();
        if (!datos.length) {
            renderTabla([]);
            return;
        }

        if (!filtroActual) {
            renderTabla(datos);
            return;
        }

        var filtrados = datos.filter(function (r) {
            var nombreCompleto = ((r.professional_name || '') + ' ' + (r.professional_surname || '') + ' ' + (r.professional_secondsurname || '')).toLowerCase();
            var programa = (r.course_name || '').toLowerCase();
            var folio = (r.controlinvoice || '').toString().toLowerCase();
            return (
                nombreCompleto.includes(filtroActual) ||
                programa.includes(filtroActual) ||
                folio.includes(filtroActual)
            );
        });

        renderTabla(filtrados);
    }

    function seleccionarRegistro(id) {
        idSeleccionado = id;
        $('#tbody-registros tr').removeClass('row-selected');
        $('#tbody-registros tr[data-id="' + id + '"]').addClass('row-selected');
    }

    function obtenerRegistroPorId(id) {
        if (!registrosData || !registrosData.length) return null;
        return registrosData.find(function (r) { return String(r.id_titledata) === String(id); }) || null;
    }

    function confirmarAccion(actionType) {
        var titleMsg = (actionType === 'crear') ? "Completar Registro" : "Editar Registro";
        var bodyMsg = (actionType === 'crear')
            ? "¿Deseas ir al formulario para completar los datos administrativos de este alumno?"
            : "¿Deseas modificar los datos administrativos de este registro?";

        bootbox.confirm({
            title: titleMsg,
            message: bodyMsg,
            buttons: {
                confirm: { label: 'Sí, continuar', className: (actionType === 'crear') ? 'btn-success' : 'btn-warning' },
                cancel:  { label: 'Cancelar', className: 'btn-secondary' }
            },
            callback: function (result) {
                if (result) {
                    window.location.href = 'completar_registro.php?id=' + idSeleccionado;
                }
            }
        });
    }

    function obtenerDatosTab() {
        if (tabActual === 'preregistrados') {
            return registrosData.filter(function(r) { return r.status == 1; });
        }
        if (tabActual === 'creados') {
            return registrosData.filter(function(r) { return r.status == 2; });
        }
        return [];
    }

    function obtenerIniciales(nombre, apellido) {
        var ini = '';
        if (nombre) {
            ini += nombre.trim().charAt(0);
        }
        if (apellido) {
            ini += apellido.trim().charAt(0);
        }
        return ini.toUpperCase();
    }

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

            var html =
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
            
            // Lógica para el botón del Modal según el status
            if (data.status == 1) {
                $('#btn-promover')
                    .html('<i class="fa-solid fa-arrow-right"></i> Pasar a Creados')
                    .removeClass('btn-warning').addClass('btn-success')
                    .data('action-type', 'crear')
                    .show();
            } else if (data.status == 2) {
                $('#btn-promover')
                    .html('<i class="fa-solid fa-pen-to-square"></i> Editar Registro')
                    .removeClass('btn-success').addClass('btn-warning')
                    .data('action-type', 'editar')
                    .show();
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
