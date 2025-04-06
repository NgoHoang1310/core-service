
<!doctype html>
<html lang="en" data-bs-theme="dark" dir="ltr">


<!-- Mirrored from templates.iqonic.design/streamit-dist/dashboard/html/auth/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 03 Apr 2025 08:34:39 GMT -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title data-setting="app_name" data-rightJoin=" Responsive Bootstrap 5 Admin Dashboard Template">Streamit
    Responsive Bootstrap 5 Admin Dashboard Template</title>
  <meta name="description"
    content="Streamit is a revolutionary Bootstrap Admin Dashboard Template and UI Components Library. The Admin Dashboard Template and UI Component features 8 modules.">
  <meta name="keywords"
    content="premium, admin, dashboard, template, bootstrap 5, clean ui, streamit, admin dashboard,responsive dashboard, optimized dashboard, simple auth">
  <meta name="author" content="Iqonic Design">
  <meta name="DC.title" content="Streamit Simple | Responsive Bootstrap 5 Admin Dashboard Template">
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css') }}"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Library / Plugin Css Build -->
  <link rel="stylesheet" href="{{ asset('css/core/libs.min.css') }}">
  
  
  
  
  
  
  
  
  
  
  <!-- streamit Design System Css -->
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
  <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300&amp;display=swap"
      rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}"></head>

<body class=" ">
  <!-- loader Start -->
  <div id="loading">
    <div class="loader simple-loader">
        <div class="loader-body ">
            <img src="{{ asset('images/loader-unscreen.gif') }}" class="image-loader img-fluid" />
        </div>
    </div>  </div>
  <!-- loader END -->
  <div class="wrapper">

<section class="sign-in-page">
   <div class="container">
      <div class="justify-content-center align-items-center height-self-center row">
         <div class="align-self-center col-lg-5 col-md-12">
            <div class="sign-user_card">
               <div class="sign-in-page-data">
                  <div class="sign-in-from w-100 m-auto">
                     <h3 class="mb-3 text-center">Sign in</h3>
                     <form action class="mt-4">
                        <div class="mb-3"><input placeholder="Enter email" autocomplete="off" required type="email"
                              id="exampleInputEmail1" class="mb-0 form-control" /></div>
                        <div class="mb-3"><input placeholder="Password" required type="password"
                              id="exampleInputPassword2" class="mb-0 form-control" /></div>
                        <div class="d-flex justify-content-between align-items-cente sign-info"><button type="button"
                              class="btn btn-btn btn-primary">Sign
                              in</button>
                           <div class="custom-control custom-checkbox d-inline-block"><input type="checkbox"
                                 class="form-check-input mx-2" id="customCheck" /><label class="form-check-label"
                                 for="customCheck">Remember Me</label></div>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="mt-3">
                  <div class="d-flex justify-content-center links">Don't have an
                     account?
                     <a class="text-primary ms-2" href="sign-up">Sign Up</a>
                  </div>
                  <div class="d-flex justify-content-center links"><a class="f-link"
                        href="recoverpw.html">Forgot
                        your
                        password?</a></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
  </div>
  <!-- Library Bundle Script -->
  <script src="{{ asset('js/core/libs.min.js') }}"></script>
  <!-- Plugin Scripts -->
  
  
  
  
  
  
  
  
  
  <!-- Slider-tab Script -->
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


<!-- Mirrored from templates.iqonic.design/streamit-dist/dashboard/html/auth/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 03 Apr 2025 08:34:39 GMT -->
</html>