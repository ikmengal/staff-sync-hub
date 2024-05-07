<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('public/admin') }}/assets/"
  data-template="vertical-menu-template"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <!-- Favicon -->
    @if(!empty(settings()))
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin/favicon.png') }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin/favicon.png') }}" />
    @endif
    <!-- Favicon -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/admin')}}/assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{asset('public/admin')}}/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />



    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/css/pages/cards-advance.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/css/custom.css" />
    @stack('styles')
    <!-- Custom CSS -->

    <!-- Helpers -->
    <script src="{{ asset('public/admin') }}/assets/vendor/js/helpers.js"></script>

    <script src="{{ asset('public/admin') }}/assets/vendor/js/template-customizer.js"></script>
    <script src="{{ asset('public/admin') }}/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('admin.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('admin.layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('admin.layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ asset('public/admin') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ asset('public/admin') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/swiper/swiper.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/pickr/pickr.js"></script>
    <script src="{{ asset('public/admin') }}/assets/js/main.js"></script>

    <!-- Page JS -->
 
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="{{ asset('public/admin') }}/assets/js/dashboards-analytics.js"></script>
    <script src="{{ asset('public/admin') }}/assets/js/attendance-daily-report.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('public/admin')}}/assets/js/forms-pickers.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Required JS -->
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

        $(document).on("input", ".numeric", function() {
            this.value = this.value.replace(/\D/g, '');
        });

        $('.form-select').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
            });
        });
        if (typeof description !== 'undefined') {
            CKEDITOR.replace('description');
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
    </script>
    <!-- Required JS -->

    <!-- Custom JS -->
    @stack('js')
    <!-- Custom JS -->
  </body>
</html>
