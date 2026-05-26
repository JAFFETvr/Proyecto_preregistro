<?php date_default_timezone_set("America/Mexico_City"); ?>
<?php session_start();

if (!isset($_SESSION['user_name'])) {
    echo '<script>location.href = "../../index.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SISTE - Panel Secretaría</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="../../assets/css/estilos.css">
    <style>
        .tab-siste .nav-link {
            background: #e9ecef;
            color: #333;
            border-radius: 4px 4px 0 0;
            margin-right: 4px;
            font-weight: 600;
            font-size: 13px;
        }
        .tab-siste .nav-link.active {
            background: #162DBF;
            color: #fff;
        }
        .badge-pendiente {
            background: #ffc107;
            color: #333;
            font-size: 11px;
            border-radius: 10px;
            padding: 2px 7px;
            margin-left: 5px;
        }
        .table-preregistro thead {
            background: #162DBF;
            color: white;
            font-size: 13px;
        }
        .table-preregistro tbody tr:hover {
            background: #f0f4ff;
        }
        .btn-ver {
            background: #e67e22;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-ver:hover { background: #cf6d17; color: white; }
        .section-title {
            font-weight: bold;
            font-size: 15px;
            border-bottom: 3px solid #cecece;
            margin-bottom: 20px;
            padding-bottom: 6px;
        }
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.6);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
        }
    </style>
</head>
<body>

<!-- Navbar (igual al resto del proyecto) -->
<nav class="navbar navbar-light bg-nav">
    <div class="container">
        <label>
            <a><img src="../../assets/images/siste_4.png" style="max-width:100%;width:230px;height:70px;"></a>
        </label>
        <?php if (isset($_SESSION['username'])): ?>
            <span style="color:white;font-weight:bold;font-size:20px;">Sistema de Titulación Electrónica</span>
            <ul class="nav align-left">
                <li>
                    <a class="dropdown-item" id="navbarDropdown1" role="menu"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="ctrl-control h5 text-white" id="username">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <div id="user" hidden><?php echo $_SESSION['username']; ?></div>
                            <?php echo $_SESSION['username']; ?>
                            <i class="fa-solid fa-caret-down"></i>
                        </span>
                    </a>
                    <ul>
                        <li><a id="btnLogout"><span class="ctrl-control h6 text-white">Cerrar Sesión</span></a></li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</nav>

<div id="loading-overlay">
    <img src="../../assets/loading.gif" alt="Cargando..." style="width:80px;">
</div>

<div class="container mt-4">

    <!-- Título del módulo -->
    <div class="mb-3">
        <h5 style="font-weight:bold; letter-spacing:1px;">EXPEDIENTE ELECTRÓNICO</h5>
    </div>

    <!-- Pestañas estilo SISTE -->
    <ul class="nav tab-siste mb-0" id="tabsSecretaria">
        <li class="nav-item">
            <a class="nav-link active" id="tab-preregistros" href="#" data-tab="preregistros">
                Pre-registros Pendientes
                <span class="badge-pendiente" id="count-pendientes">0</span>
            </a>
        </li>
    </ul>

    <div style="border:1px solid #dee2e6; border-top:none; padding:20px; background:white;">

        <!-- Panel Pre-registros -->
        <div id="panel-preregistros">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="section-title mb-0">Alumnos con pre-registro pendiente de atención</span>
                <button class="btn btn-sm btn-secondary" id="btn-refresh">
                    <i class="fa-solid fa-rotate-right"></i> Actualizar
                </button>
            </div>

            <div id="tabla-container">
                <table class="table table-bordered table-preregistro">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NOMBRE DEL ALUMNO</th>
                            <th>PROGRAMA</th>
                            <th>CORREO ELECTRÓNICO</th>
                            <th>FECHA DE REGISTRO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-preregistros">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fa-solid fa-spinner fa-spin"></i> Cargando...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal detalle del pre-registro -->
<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#162DBF; color:white;">
                <h5 class="modal-title" id="modalDetalleLabel">
                    <i class="fa-solid fa-user-graduate"></i> Detalle del Pre-registro
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-detalle">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-promover" style="display:none;">
                    <i class="fa-solid fa-check"></i> Marcar como atendido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts (mismo orden que el resto del proyecto) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
<script src="../../controllers/secretaria/script_secretaria.js"></script>
</body>
</html>