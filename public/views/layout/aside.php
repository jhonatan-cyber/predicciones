<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        <div id="kt_aside" class="aside " data-kt-drawer="true" data-kt-drawer-name="aside"
            data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
            data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_aside_toggle">
            <div class="aside-logo d-flex justify-content-center align-items-center pt-5 pb-3 " id="kt_aside_logo">
                <a href="<?php echo BASE_URL ?>home">
                    <img alt="Logo" src="<?php echo BASE_URL ?>public/assets/img/sistema/logo.png" class="logo-default"
                        style="width:auto; height: 100px;" />
                </a>
            </div>
            <div class="aside-menu flex-column-fluid px-3 px-lg-6">
                <div class="menu menu-column menu-sub-indention menu-active-bg menu-pill menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-arrow-gray-500 fw-semibold fs-5 my-5 mt-lg-2 mb-lg-0"
                    id="kt_aside_menu" data-kt-menu="true">

                    <div class="hover-scroll-y me-n3 pe-3" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                        data-kt-scroll-wrappers="#kt_aside_menu"
                        data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-offset="20px">
                        <div class="menu-item mb-1">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>home">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboards</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>

                        <div class="menu-item mb-3" id="btn_usuarios" >
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>usuarios" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <span class="menu-title">Usuarios</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>

                        <div id="configuraciones" data-kt-menu-trigger="click" class="menu-item  menu-accordion mb-1">
                            <span
                                class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm mb-3"><span
                                    class="menu-icon">
                                    <span class="svg-icon svg-icon-2"><i class="fa-solid fa-screwdriver-wrench"></i>
                                    </span>
                                </span><span class="menu-title">Coniguraciones</span><span
                                    class="menu-arrow"></span></span>
                            <div class="menu-sub menu-sub-accordion">


                                <div class="menu-item mb-3"  >
                                    <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                        href="<?php echo BASE_URL ?>roles">
                                        <span class="menu-bullet">
                                            <i class="fa-solid fa-address-card"></i>
                                        </span>
                                        <span class="menu-title">Roles</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>