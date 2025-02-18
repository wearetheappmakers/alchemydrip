<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!-- begin::Head -->
<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Alchemy Drip</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.1/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css" /> -->
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles -->
    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js" type="text/javascript"></script>
    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ asset('/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .toast.toast-success {
            background: #4eff1c;
        }
        .toast.toast-warning {
            background: red;
        }
        .toast.toast-error {
            background: #808000;
        }
    </style>
    <!--end::Global Theme Styles -->
    <script src="{{ asset('/assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/js/scripts.bundle.js') }}" type="text/javascript"></script>
    <!--begin::Layout Skins(used by all pages) -->
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{asset('assets/media/logos/AlchemyDrip_logo.png')}}" />
    @stack('styles')
</head>
<!-- end::Head -->
<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">
    <!-- begin:: Page -->
    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="{{route('home')}}">
                <img alt="Logo" src="{{asset('assets/media/logos/AlchemyDrip_logo.png')}}" style="height: 50px; width: 50px;" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <div class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></div>
            <div class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></div>
            <div class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></div>
        </div>
    </div>
    <!-- end:: Header Mobile -->
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <!-- begin:: Aside -->
            <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
            <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
                <!-- begin:: Aside Menu -->
                <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
                        <ul class="kt-menu__nav ">
                            <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('home')}}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-protection"></i><span class="kt-menu__link-text">Dashboard</span></a></li>
                            <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('product.index')}}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-layers"></i><span class="kt-menu__link-text">Product</span></a></li>
                            <!--  <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('po.index')}}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-shopping-cart"></i><span class="kt-menu__link-text">Purchase Order</span></a></li> -->
                            <!-- <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('so.index')}}" class="kt-menu__link "><i class="kt-menu__link-icon 
                                flaticon2-contract"></i><span class="kt-menu__link-text">Sales Order</span></a></li> -->
                            <!-- <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('b2b.index')}}" class="kt-menu__link "><i class="kt-menu__link-icon 
                                flaticon2-refresh"></i><span class="kt-menu__link-text">B2B Order</span></a></li> -->
                                <!-- <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('otherproduct.index')}}" class="kt-menu__link "><i class="kt-menu__link-icon 
                                    flaticon2-refresh"></i><span class="kt-menu__link-text">Accessories</span></a></li> -->
                                    <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-app"></i><span class="kt-menu__link-text">Master</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('school.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">School</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('size.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Size</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('gender.index')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Gender</span></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-more-1"></i><span class="kt-menu__link-text">Manage</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('shop.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Shop</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('user.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">User</span></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-shopping-cart-1"></i><span class="kt-menu__link-text">Orders</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">All order</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index.status',2)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pending order</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index.status',1)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Complate order</span></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-pie-chart-2"></i><span class="kt-menu__link-text">B2B Orders</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                        <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b All Order</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index.status',2)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b Pending Order</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index.status',1)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b Complate Order</span></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item  kt-menu__item--inactive" aria-haspopup="true"><a href="{{route('setting.edit',1)}}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-gear"></i><span class="kt-menu__link-text">Setting</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- end:: Aside Menu -->
                    </div>
                    <!-- end:: Aside -->
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                        <!-- begin:: Header -->
                        <div id="kt_header" class="kt-header kt-grid kt-grid--ver  kt-header--fixed ">
                            <!-- begin:: Aside -->
                            <div class="kt-header__brand kt-grid__item  " id="kt_header_brand" style="background-color: white;">
                                <div class="kt-header__brand-logo">
                                    <a href="{{route('home')}}">
                                        <img alt="Logo" src="{{asset('assets/media/logos/AlchemyDrip_logo.png')}}" style=" height: 70px; width: 99px;" />
                                    </a>
                                </div>
                            </div>
                            <!-- end:: Aside -->
                            <!-- begin:: Title -->
                            <h3 class="kt-header__title kt-grid__item">
                                <a href="{{route('home')}}" style=" color: #000000;">
                                    Alchemy Drip
                                </a>
                            </h3>
                            <!-- end:: Title -->
                            <!-- begin: Header Menu -->
                            <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                            <div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
                                <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                                    <ul class="kt-menu__nav ">
                                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('home')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a></li>
                                        <!-- <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('product.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Product</span></a></li> -->
                                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('inventory.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Inventory</span></a></li>
                                        <!-- <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('po.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Purchase Order</span></a></li> -->
                                        <!-- <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('so.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Sales Order</span></a></li> -->
                                        <!--  <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('b2b.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">B2B Order</span></a></li> -->
                                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('otherproduct.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Accessories</span></a></li>
                                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('otherproducttwo.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Accessories-2</span></a></li>
                                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('otherproductthree.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Accessories-3</span></a></li>
                                       <!--  <li class="kt-menu__item kt-menu__item--active kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Master</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                            <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                                <ul class="kt-menu__subnav">
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('school.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">School</span></a></li>
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('size.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Size</span></a></li>
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('gender.index')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Gender</span></a></li>
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('rawmaterial.index')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Raw Material</span></a></li>
                                                </ul>
                                            </div>
                                        </li> -->
                                        <!-- <li class="kt-menu__item kt-menu__item--active kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Manage</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                            <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                                <ul class="kt-menu__subnav">
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('shop.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Shop</span></a></li>
                                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('user.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">User</span></a></li>
                                                </ul>
                                            </div>
                                        </li> -->
                                        <!-- <li class="kt-menu__item kt-menu__item--active kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Orders</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                           <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">All order</span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index.status',2)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pending order </span></a></li>
                                                <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('so.index.status',1)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Complete order </span></a></li>
                                          <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('orderstatus.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Status</span></a></li>
                                 
                                     </ul>  
                                 </div>
                             </li> -->
                             <!-- <li class="kt-menu__item kt-menu__item--active kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open-dropdown" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text"> B2B Orders</span><i class="kt-menu__hor-arrow la la-angle-down"></i><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                               <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index')}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b All Order</span></a></li>
                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index.status',2)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b Pending Order</span></a></li>
                                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{route('b2b.index.status',1)}}"class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">B2b Complete Order</span></a></li>
                                </ul>  
                            </div>
                        </li> -->
                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('ledger.index')}}" class="kt-menu__link "><span class="kt-menu__link-text">Ledger</span></a></li>
                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('ledger.index.so')}}" class="kt-menu__link "><span class="kt-menu__link-text">New Ledger</span></a></li>
                        <li class="kt-menu__item  kt-menu__item--active " aria-haspopup="true"><a href="{{route('report.employee')}}" class="kt-menu__link "><span class="kt-menu__link-text">Employee Report</span></a></li>
                    </ul>
                </div>
            </div>
                    <!-- end: Header Menu -->