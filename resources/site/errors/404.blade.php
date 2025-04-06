<!doctype html>
<html lang="en" data-bs-theme="dark" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title data-setting="app_name" data-rightJoin="Responsive Bootstrap 5 Admin Dashboard Template">Streamit
    Responsive Bootstrap 5 Admin Dashboard Template</title>
  <meta name="description"
    content="Streamit is a revolutionary Bootstrap Admin Dashboard Template and UI Components Library. The Admin Dashboard Template and UI Component features 8 modules.">
  <meta name="keywords"
    content="premium, admin, dashboard, template, bootstrap 5, clean ui, streamit, admin dashboard, responsive dashboard, optimized dashboard, simple auth">
  <meta name="author" content="Iqonic Design">
  <meta name="DC.title" content="Streamit Simple | Responsive Bootstrap 5 Admin Dashboard Template">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Library / Plugin Css Build -->
  <link rel="stylesheet" href="{{ asset('css/core/libs.min.css') }}">

  <!-- Streamit Design System Css -->
  <link rel="stylesheet" href="{{ asset('css/streamit.min848f.css?v=5.2.1') }}">

  <!-- Custom Css -->
  <link rel="stylesheet" href="{{ asset('css/custom.min848f.css?v=5.2.1') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard-custom.min848f.css?v=5.2.1') }}">

  <!-- RTL Css -->
  <link rel="stylesheet" href="{{ asset('css/rtl.min848f.css?v=5.2.1') }}">

  <!-- Customizer Css -->
  <link rel="stylesheet" href="{{ asset('css/customizer.min848f.css?v=5.2.1') }}">

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300&amp;display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">

</head>

<body class=" ">
  <!-- loader Start -->
  <div id="loading">
    <div class="loader simple-loader">
      <div class="loader-body ">
        <img src="{{ asset('images/loader-unscreen.gif') }}" class="image-loader img-fluid" />
      </div>
    </div>
  </div>
  <!-- loader END -->

  <div class="wrapper">
    <div class="pt-5 container vh-100">
      <div class="no-gutters height-self-center row h-100">
        <div class="text-center align-self-center col-sm-12">
          <div class="iq-error position-relative">
            <img src="{{ asset('images/error/404.png') }}" class="img-fluid iq-error-img" alt="">
            <h2 class="mb-0 mt-4">Oops! This Page is Not Found.</h2>
            <p>The requested page does not exist.</p>
            <a class="btn btn-primary mt-3" href="{{ url('/') }}"><i class="ri-home-4-line"></i>Back to Home</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Library Bundle Script -->
  <script src="{{ asset('js/core/libs.min.js') }}"></script>

  <!-- Plugin Scripts -->
  <script src="{{ asset('js/plugins/slider-tabs.js') }}"></script>

  <!-- Lodash Utility -->
  <script src="{{ asset('vendor/lodash/lodash.min.js') }}"></script>

  <!-- Utilities Functions -->
  <script src="{{ asset('js/iqonic-script/utility.min.js') }}"></script>

  <!-- Settings Script -->
  <script src="{{ asset('js/iqonic-script/setting.min.js') }}"></script>

  <!-- Settings Init Script -->
  <script src="{{ asset('js/setting-init.js') }}"></script>

  <!-- External Library Bundle Script -->
  <script src="{{ asset('js/core/external.min.js') }}"></script>

  <!-- Widgetchart Script -->
  <script src="{{ asset('js/charts/widgetcharts848f.js?v=5.2.1') }}" defer></script>

  <!-- Dashboard Script -->
  <script src="{{ asset('js/charts/dashboard848f.js?v=5.2.1') }}" defer></script>

  <!-- qompacui Script -->
  <script src="{{ asset('js/streamit848f.js?v=5.2.1') }}" defer></script>

  <script src="{{ asset('js/sidebar848f.js?v=5.2.1') }}" defer></script>

  <script src="{{ asset('js/chart-custom848f.js?v=5.2.1') }}" defer></script>

  <script src="{{ asset('js/plugins/select2848f.js?v=5.2.1') }}" defer></script>

  <script src="{{ asset('js/plugins/flatpickr848f.js?v=5.2.1') }}" defer></script>

  <script src="{{ asset('js/plugins/countdown848f.js?v=5.2.1') }}" defer></script>

</body>

</html>
