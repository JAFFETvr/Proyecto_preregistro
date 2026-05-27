<?php 
date_default_timezone_set("America/Mexico_City"); 
session_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="../../assets/css/estilos.css"> 
    <style>
        .tab-siste {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
        }
        .tab-siste .nav-link {
            color: #333;
            border: none;
            background: transparent;
            font-weight: 500;
            font-size: 13px;
            padding: 8px 12px;
            margin-right: 5px;
            cursor: pointer;
        }
        .tab-siste .nav-link:hover {
            background: #f8f9fa;
        }
        .tab-siste .nav-link.active {
            background: #0c315e; /* Azul de tu sistema */
            color: #fff;
            border-radius: 4px 4px 0 0;
        }
        .badge-count {
            font-size: 11px;
            border-radius: 4px;
            padding: 2px 6px;
            margin-left: 5px;
            color: #fff;
        }
        .badge-blue { background-color: #5bc0de; }
        .badge-gray { background-color: #868e96; }
        .badge-yellow { background-color: #f0ad4e; }
        .badge-red { background-color: #d9534f; }
        .badge-green { background-color: #5cb85c; }
        
        .btn-nuevo-registro {
            color: #5cb85c;
            border: 1px solid #5cb85c;
            background: transparent;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .btn-nuevo-registro:hover {
            background: #5cb85c;
            color: #fff;
        }

        .table-preregistro thead {
            background: #0c315e; /* Azul de tu sistema */
            color: white;
            font-size: 12px;
            text-transform: uppercase;
        }
        .table-preregistro tbody td {
            font-size: 13px;
            vertical-align: middle;
            font-weight: 600;
        }
        .table-preregistro tbody tr:hover {
            background: #f0f4ff;
        }
        .btn-accion-naranja {
            background: #f0ad4e;
            color: white;
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-accion-naranja:hover {
            background: #e69522;
            color: white;
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

<nav class="navbar navbar-light bg-nav">
    <div class="container">
        <label>
            <a><img src="../../assets/images/siste_4.png" style="max-width:100%;width:230px;height:70px;"></a>
        </label>
        <?php if (isset($_SESSION['user_name'])): ?>
            <span style="color:white;font-weight:bold;font-size:20px;">Sistema de Titulación Electrónica</span>
            <ul class="nav align-left">
                <li>
                    <a class="dropdown-item" id="navbarDropdown1" role="menu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="ctrl-control h5 text-white" id="username">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <div id="user" hidden><?php echo $_SESSION['user_name']; ?></div>
                            <?php echo $_SESSION['user_name']; ?>
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

    <div class="mb-3">
        <h6 style="font-weight:bold; letter-spacing:1px; margin-bottom: 20px;">EXPEDIENTE ELECTRÓNICO</h6>
    </div>

    <ul class="nav tab-siste" id="tabsSecretaria">
        <li class="nav-item">
            <a class="nav-link active" data-tab="preregistrados">
                Pre-registrados <span class="badge-count badge-blue" id="count-pre">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="creados">
                Creados <span class="badge-count badge-gray" id="count-creados">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="por-firmar">
                Por firmar <span class="badge-count badge-gray">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="listos-enviar">
                Listos para Enviar <span class="badge-count badge-yellow">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="revision">
                En Revisión DGP <span class="badge-count badge-yellow">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="rechazados">
                Rechazados <span class="badge-count badge-red">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-tab="emitidos">
                Emitidos <span class="badge-count badge-green">0</span>
            </a>
        </li>
    </ul>
    
    <div class="mb-3">
        <button class="btn-nuevo-registro">NUEVO REGISTRO</button>
    </div>

    <div style="background:white;">
        <div id="tabla-container">
            <table class="table table-bordered table-preregistro">
                <thead>
                    <tr>
                        <th>NOMBRE DEL ALUMNO</th>
                        <th>PROGRAMA</th>
                        <th class="text-center">FECHA PRE-REGISTRO</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="tbody-registros">
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fa-solid fa-spinner fa-spin"></i> Cargando...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#0c315e; color:white;">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user-graduate"></i> Detalle del Registro
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-detalle" style="padding: 20px;">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-promover" style="display:none;">
                    <i class="fa-solid fa-arrow-right"></i> Pasar a Creados
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
<script src="../../controllers/secretaria/script_secretaria.js"></script>
</body>
</html>