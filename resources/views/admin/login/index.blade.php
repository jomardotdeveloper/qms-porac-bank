<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>QMS - Login</title>
    <link rel="icon" type="image/x-icon" href="/admin/assets/img/appicon2.ico">
    <!-- Common Styles Starts -->
    <link href="/admin/css2.css?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/main.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/structure.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/highlight/styles/monokai-sublime.css" rel="stylesheet" type="text/css">
    <!-- Common Styles Ends -->
    <!-- Common Icon Starts -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Common Icon Ends -->
    <!-- Page Level Plugin/Style Starts -->
    <!-- <link href="/admin/assets/css/loader.css" rel="stylesheet" type="text/css"> -->
    <link href="/admin/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/owl-carousel/owl.theme.default.min.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/authentication/auth_1.css" rel="stylesheet" type="text/css">
    <!-- Page Level Plugin/Style Ends -->
</head>
<body class="login-one">
    <!-- Loader Starts -->
    <!-- <div id="load_screen"> 
        <div class="boxes">
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
        </div>
        <p class="xato-loader-heading">Xato</p>
    </div> -->
    <!--  Loader Ends -->
    <!-- Main Body Starts -->
    <div class="container-fluid login-one-container">
        <div class="p-30 h-100">
            <div class="row main-login-one h-100">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 p-0">
                    <div class="login-one-start">
                        @if($errors->any())
                        <div class="alert alert-danger mb-4" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                <i class="las la-times"></i>
                            </button> 
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        @endif
                        <img src="/admin/assets/img/appiconwo.png" style="width:40%;display: block;margin-left: auto; margin-right: auto;" alt="logo"/>
                        <h6 class="mt-2 text-primary text-center font-20">Log In</h6>
                        <p class="text-center text-muted mt-3 mb-3 font-14">Please Log into your account</p>
                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf
                            <div class="login-one-inputs mt-5">
                                <input type="text" placeholder="Username" name="username" required>
                                <i class="las la-user-alt"></i> 
                            </div>
                            <div class="login-one-inputs mt-3">
                                <input type="password" placeholder="Password" name="password" required> 
                                <i class="las la-lock"></i>
                            </div>
                            <div class="login-one-inputs mt-4">
                                <button class="ripple-button ripple-button-primary btn-lg btn-login" type="submit">
                                    <div class="ripple-ripple js-ripple">
                                    <span class="ripple-ripple__circle"></span>
                                    </div>
                                    LOG IN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-6 col-md-6 d-none d-md-block p-0">
                    <div class="slider-half">
                        <div class="slide-content">
                            <div class="clearfix"></div>
                            <div class="owl-carousel owl-theme">
                                <div class="item">
                                    <i class="lar la-grin-alt font-45 text-white"></i>
                                    <h2 class="font-30 text-white mt-2">User-Friendly</h2>
                                    <p class="summary-count text-white font-12 mt-2 slide-text">Controls are easy to navigate.</p>
                                </div>
                                <div class="item">
                                    <i class="lar la-file-alt font-45 text-white"></i>
                                    <h2 class="font-30 text-white mt-2">Usage Reports</h2>
                                    <p class="summary-count text-white font-12 mt-2 slide-text">Provides reports to help in allocation and staffing of employees.</p>
                                </div>
                                <div class="item">
                                    <i class="las la-hand-holding-usd font-45 text-white"></i>
                                    <h2 class="font-30 text-white mt-2">Smooth Transactions</h2>
                                    <p class="summary-count text-white font-12 mt-2 slide-text">The controls are well integrated</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <!-- Main Body Ends -->
    <!-- Page Level Plugin/Script Starts -->
    <!-- <script src="/admin/assets/js/loader.js"></script> -->
    <script src="/admin/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="/admin/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="/admin/plugins/owl-carousel/owl.carousel.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/admin/assets/js/authentication/auth_1.js"></script>
    <!-- Page Level Plugin/Script Ends -->
</body>
</html>