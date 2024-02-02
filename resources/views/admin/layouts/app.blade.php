
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>@yield('title')</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />

        @if(!empty(settings()->favicon))
            <link rel="shortcut icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
        @else
            <link rel="shortcut icon" href="{{ asset('public/admin') }}/assets/media/logos/favicon.ico" />
        @endif

		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset('public/admin') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/admin') }}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset('public/admin') }}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{ asset('public/admin') }}/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
        @stack('styles')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>
            var defaultThemeMode = "light";
            var themeMode;
            if ( document.documentElement ) {
                if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if ( localStorage.getItem("data-bs-theme") !== null ) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                    if (themeMode === "system") {
                        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                    }
                    document.documentElement.setAttribute("data-bs-theme", themeMode);
                }
            }
        </script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header">
					<!--begin::Header primary-->
					@include('admin.layouts.header')
					<!--end::Header primary-->
					<!--begin::Header secondary-->
					@include('admin.layouts.companies')
					<!--end::Header secondary-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!--begin::Wrapper container-->
                    <div class="app-container container-xxl d-flex flex-row flex-column-fluid">
                        <!--begin::Main-->
                        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                            <!--begin::Content wrapper-->
                            <div class="d-flex flex-column flex-column-fluid">
                                <!--begin::Content-->
                                @yield('content')
                                <!--end::Content-->
                            </div>
                            <!--end::Content wrapper-->
                            <!--begin::Footer-->
                            @include('admin.layouts.footer')
                            <!--end::Footer-->
                        </div>
                        <!--end:::Main-->
                    </div>
                    <!--end::Wrapper container-->
                </div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->

		<!--begin::Drawers-->
		@include('admin.layouts.drawers')
		<!--end::Drawers-->

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
        @include('admin.layouts.modals')
		<!--end::Modals-->
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('public/admin') }}/assets/plugins/global/plugins.bundle.js"></script>
		<script src="{{ asset('public/admin') }}/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset('public/admin') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="{{ asset('public/admin') }}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('public/admin') }}/assets/js/widgets.bundle.js"></script>
		<script src="{{ asset('public/admin') }}/assets/js/custom/apps/chat/chat.js"></script>
		<script src="{{ asset('public/admin') }}/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="{{ asset('public/admin') }}/assets/js/custom/utilities/modals/users-search.js"></script>
        <script src="{{ asset('public/admin') }}/assets/js/custom/account/settings/signin-methods.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->

        <!-- Custom javascripts -->
        <script>
            @if(Session::has('message'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.success("{{ session('message') }}");
            @endif

            @if(Session::has('error'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.error("{{ session('error') }}");
            @endif

            @if(Session::has('info'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.info("{{ session('info') }}");
            @endif

            @if(Session::has('warning'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.warning("{{ session('warning') }}");
            @endif

            $(document).on('keyup', '.mobileNumber', function() {
                var mobile = $(this).val();
                var formattedMobile = formatMobileNumber(mobile);
                $(this).val(formattedMobile);
            });

            function formatMobileNumber(mobile) {
                mobile = mobile.replace(/\D/g, ''); // Remove non-numeric characters
                if (mobile.length > 4) {
                    mobile = mobile.substring(0, 4) + "-" + mobile.substring(4, 11);
                }
                return mobile;
            }

            $(document).on('keyup', '.phoneNumber', function() {
                var phone = $(this).val();
                var formattedPhone = formatPhoneNumber(phone);
                $(this).val(formattedPhone);
            });

            function formatPhoneNumber(phone) {
                phone = phone.replace(/\D/g, '');
                if (phone.length > 3) {
                    var areaCode = phone.substring(0, 3);
                    var telephoneNumber = phone.substring(3, 11);
                    phone = "(" + areaCode + ") - " + telephoneNumber;
                }
                return phone;
            }

            $(document).on('keyup', '.cnic_number', function() {
                var cnic = $(this).val();
                var formattedCnic = formatCnic(cnic);
                $(this).val(formattedCnic);
            });

            function formatCnic(cnic) {
                cnic = cnic.replace(/\D/g, ''); // Remove non-numeric characters
                if (cnic.length > 5) {
                    cnic = cnic.substring(0, 5) + "-" + cnic.substring(5, 12) + "-" + cnic.substring(12, 13);
                } else if (cnic.length > 2) {
                    cnic = cnic.substring(0, 5) + "-" + cnic.substring(5);
                }
                return cnic;
            }
        </script>
        <!-- Custom javascripts -->

        @stack('js')
	</body>
	<!--end::Body-->
</html>
