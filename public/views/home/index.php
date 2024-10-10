<?php include_once 'public/views/layout/head.php' ?>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed">
    <?php include_once 'public/views/layout/aside.php' ?>
    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div id="kt_header" class="header " data-kt-sticky="true" data-kt-sticky-name="header"
            data-kt-sticky-offset="{default: '200px', lg: '300px'}">
            <div class=" container-fluid  d-flex align-items-stretch justify-content-between" id="kt_header_container">
                <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-2 mb-5 mb-lg-0"
                    data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                    <h1 class="text-dark fw-bold mt-1 mb-1 fs-2">
                        Dashboard <small class="text-muted fs-6 fw-normal ms-1"></small>
                    </h1>
                    <ul class="breadcrumb fw-semibold fs-base mb-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="<?php echo BASE_URL ?>home" class="text-muted text-hover-primary">
                                Dashboard </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Dashboard </li>
                    </ul>
                </div>
                <?php include_once 'public/views/layout/navbar.php' ?>
            </div>
        </div>

        <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
            <div class=" container-fluid " id="kt_content_container">
                <h1 class="fw-bolder text-dark mb-1">
                    Subir un archivo CSV
                </h1>
                <div class="py-5 text-center justify-content-center">
                    <button class="btn" onclick="uploadAndImportCSV('data')">
                        <div class="rounded border p-10">
                            <form class="form">
                                <div class="fv-row">
                                    <div class="dropzone dz-clickable" id="kt_dropzonejs_example_1">
                                        <div class="dz-message needsclick">
                                            <i class="fa-solid fa-file-excel text-success fa-3x"></i>
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Selecione un data set .csv
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </button>

                    <div class="row text-center justify-content-center">
                        <div class="col-4 col-sm-12">
                            <button hidden id="selectFolderButton" class="btn btn-primary m-2" onclick="getLastCsv()">Generar Prediciones</button>
                            <button hidden id="downloadButton" class="btn btn-primary m-2"
                                onclick="downloadFile()">Guardar Predicciones</button>
                        </div>

                    </div>

                    <div id="predic" class="container-fluid" >
                        <h2 class="fw-bolder text-dark mb-1"> Grafico de predicciones</h2>
                    <canvas id="predicciones" width="auto" height="auto"></canvas>
                    </div>




                    <div id="encuesta" class="centered-content mt-5 mb-4">
                        <h3>Resultados de la Encuesta</h3>
                        <div id="chartContainer" class="respuestas mb-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <?php include_once 'public/views/layout/footer.php' ?>

</body>
<script src="<?php echo BASE_URL ?>public/views/home/home.js"></script>

</html>