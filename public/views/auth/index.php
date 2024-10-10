<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Las muñecas de Ramón" />
    <meta name="keywords" content="Las muñecas de Ramón" />
    <meta name="author" content="NuweSoft" />
    <title><?php echo TITLE ?> | Iniciar sesión</title>

    <!-- CSS -->
    <link href="<?php echo BASE_URL ?>public/assets/css/all.min.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>public/assets/css/plugins.bundle.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>public/assets/css/style.bundle.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>public/assets/css/toastr.css" rel="stylesheet" />

</head>

<body id="kt_body" class="auth-bg">

    <div class="d-flex flex-column flex-root mt-12">

        <div  class="d-flex flex-column flex-xl-row flex-column-fluid">
         
            <div class="flex-row-fluid d-flex flex-center justify-content-xl-start p-10">
                <div class="d-flex flex-center p-15 shadow rounded w-100 w-md-550px mx-auto">
                    <form class="form" id="kt_free_trial_form" novalidate>
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Iniciar sesión</h1>
                            <div class="text-gray-400 fw-bold fs-4">
                                <small class="text-gray-600 fw-bold fs-7">
                                    Ingrese sus datos para iniciar sesión en su cuenta
                                </small>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="fv-row mb-10">
                            <label for="correo" class="form-label fs-6 fw-bold text-dark">Correo</label>
                            <input type="text" id="correo" name="correo" class="form-control form-control-lg form-control-solid" placeholder="Correo electrónico" autocomplete="off" />
                        </div>

                        <!-- Password Input -->
                        <div class="fv-row mb-10">
                            <label for="password" class="form-label fw-bold text-dark fs-6">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg form-control-solid" placeholder="Contraseña" autocomplete="off" />
                        </div>

                        <!-- Buttons -->
                        <div class="text-center pb-lg-0 pb-8">
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="login(event)">
                                Iniciar sesión
                            </button>
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up">
                                ¿Has olvidado tu contraseña?
                            </button>

                            <!-- Icon Mode -->
                            <div class="d-flex flex-center flex-wrap mt-5">
                                <?php include_once 'public/components/iconMode.php'; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

   
    </div>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL ?>public/assets/js/plugins.bundle.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/scripts.bundle.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/axios.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/toastr.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL ?>';
    </script>
    <script src="<?php echo BASE_URL ?>public/views/auth/auth.js"></script>
</body>

</html>
