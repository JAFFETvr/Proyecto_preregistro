$(document).ready(function () {

    // ── Variables globales ──────────────────────────────────────────
    var preregistros = [];
    var idSeleccionado = null;
    var CONTROLLER = '../../controllers/preregistro/controller_preregistro.php';

    // ── Cargar pre-registros al iniciar ─────────────────────────────
    cargarPendientes();

    // ── Botón actualizar ────────────────────────────────────────────
    $('#btn-refresh').on('click', function () {
        cargarPendientes();
    });

    // ── Logout (mismo patrón que el resto del proyecto) ─────────────
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
                    $.post(CONTROLLER, { action: 99 }, function () {
                        location.href = '../../index.php';
                    }).fail(function () {
                        location.href = '../../index.php';
                    });
                }
            }
        });
    });

    // ── Botón "Ver detalle" (delegado) ──────────────────────────────
    $(document).on('click', '.btn-ver', function () {
        var id = $(this).data('id');
        abrirDetalle(id);
    });

    // ── Botón "Marcar como atendido" ────────────────────────────────
    $('#btn-promover').on('click', function () {
        if (!idSeleccionado) return;
        bootbox.confirm({
            title: "Confirmar acción",
            message: "¿Marcar este pre-registro como <strong>atendido</strong>? Se cambiará su estado.",
            buttons: {
                confirm: { label: 'Sí, atender', className: 'btn-success' },
                cancel:  { label: 'Cancelar',    className: 'btn-secondary' }
            },
            callback: function (result) {
                if (!result) return;
                mostrarLoading(true);
                $.post(CONTROLLER, { action: 12, prereg_id: idSeleccionado }, function (resp) {
                    mostrarLoading(false);
                    $('#modalDetalle').modal('hide');
                    if (resp && resp.ok) {
                        bootbox.alert({
                            title: 'Éxito',
                            message: '<i class="fa-solid fa-check text-success"></i> Pre-registro marcado como atendido.'
                        });
                        cargarPendientes();
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

    // ── FUNCIONES ────────────────────────────────────────────────────

    function cargarPendientes() {
        mostrarLoading(true);
        $('#tbody-preregistros').html(
            '<tr><td colspan="6" class="text-center text-muted py-4">' +
            '<i class="fa-solid fa-spinner fa-spin"></i> Cargando...</td></tr>'
        );

        $.post(CONTROLLER, { action: 10 }, function (data) {
            mostrarLoading(false);
            preregistros = data || [];
            renderTabla(preregistros);
            $('#count-pendientes').text(preregistros.length);
        }, 'json').fail(function () {
            mostrarLoading(false);
            $('#tbody-preregistros').html(
                '<tr><td colspan="6" class="text-center text-danger">Error al cargar los datos.</td></tr>'
            );
        });
    }

    function renderTabla(datos) {
        if (!datos || datos.length === 0) {
            $('#tbody-preregistros').html(
                '<tr><td colspan="6" class="text-center text-muted py-4">' +
                '<i class="fa-solid fa-inbox"></i> No hay pre-registros pendientes en este momento.</td></tr>'
            );
            return;
        }

        var html = '';
        $.each(datos, function (i, r) {
            var nombre = (r.professional_name || '') + ' ' +
                         (r.professional_surname || '') + ' ' +
                         (r.professional_secondsurname || '');
            nombre = nombre.trim().toUpperCase();
            var programa = (r.course_name || '').toUpperCase();
            var correo   = r.professional_email || '-';
            var fecha    = r.fecha_registro || '-';

            html += '<tr>' +
                '<td class="text-center">' + (i + 1) + '</td>' +
                '<td><strong>' + nombre + '</strong></td>' +
                '<td>' + programa + '</td>' +
                '<td>' + correo + '</td>' +
                '<td class="text-center">' +
                    '<span style="color:#162DBF;font-weight:600;">' + fecha + '</span>' +
                '</td>' +
                '<td class="text-center">' +
                    '<button class="btn-ver" data-id="' + r.id_titledata + '" title="Ver detalle">' +
                    '<i class="fa-solid fa-pen-to-square"></i></button>' +
                '</td>' +
            '</tr>';
        });

        $('#tbody-preregistros').html(html);
    }

    function abrirDetalle(id) {
        mostrarLoading(true);
        idSeleccionado = id;

        $.post(CONTROLLER, { action: 11, id_titledata: id }, function (data) {
            mostrarLoading(false);
            if (!data) {
                bootbox.alert({ title: 'Error', message: 'No se encontró el registro.' });
                return;
            }

            var nombre = ((data.professional_name || '') + ' ' +
                          (data.professional_surname || '') + ' ' +
                          (data.professional_secondsurname || '')).trim().toUpperCase();

            var html =
                '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<p><strong>Nombre completo:</strong><br>' + nombre + '</p>' +
                        '<p><strong>CURP:</strong><br>' + (data.professional_curp || '-').toUpperCase() + '</p>' +
                        '<p><strong>Correo electrónico:</strong><br>' + (data.professional_email || '-') + '</p>' +
                        '<p><strong>Folio del título:</strong><br>' + (data.controlinvoice || '-') + '</p>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<p><strong>Programa:</strong><br>' + (data.course_name || '-').toUpperCase() + '</p>' +
                        '<p><strong>Tipo de programa:</strong><br>' + (data.course_type || '-') + '</p>' +
                        '<p><strong>Fecha de inicio:</strong><br>' + (data.course_startdate || '-') + '</p>' +
                        '<p><strong>Fecha de terminación:</strong><br>' + (data.course_finishdate || '-') + '</p>' +
                    '</div>' +
                '</div>' +
                '<hr>' +
                '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<p><strong>Modalidad de titulación:</strong><br>' +
                        (data.expedition_degreemodality || '-') + '</p>' +
                        '<p><strong>Fecha de examen profesional:</strong><br>' +
                        (data.expedition_dateprofessionalexam || '-') + '</p>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<p><strong>Estado de expedición:</strong><br>' +
                        (data.expedition_state || '-') + '</p>' +
                        '<p><strong>Fecha de registro:</strong><br>' +
                        (data.date_register || '-') + '</p>' +
                    '</div>' +
                '</div>';

            $('#modal-body-detalle').html(html);
            $('#btn-promover').data('id', id).show();
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