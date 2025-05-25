<!doctype html>
<html lang="en">

<head>
    <title>{{ $title ?? 'Explore Bogor' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/aos.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/02c07b0853.js" crossorigin="anonymous"></script>

    @yield('custom-styles')
    @livewireStyles

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">



    <div class="site-wrap" id="home-section">

        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <x-navbar />

        @if (session('success'))
            <div class="d-flex justify-content-center mt-3">
                <div id="success-alert" class="alert alert-success shadow rounded-3 w-100 w-md-50" role="alert"
                    style="max-width: 500px; transition: opacity 0.5s;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
                        <div class="flex-grow-1">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0';
                        setTimeout(function() {
                            alertBox.style.display = 'none';
                        }, 500); // animasi fade out
                    }
                }, 3000); // hilang dalam 3 detik
            </script>
        @endif

        @if (session('gagalLogin'))
            <div class="d-flex justify-content-center mt-3">
                <div id="success-alert" class="alert alert-danger shadow rounded-3 w-100 w-md-50" role="alert"
                    style="max-width: 500px; transition: opacity 0.5s;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
                        <div class="flex-grow-1">
                            {{ session('gagalLogin') }}
                        </div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    var alertBox = document.getElementById('success-alert');
                    if (alertBox) {
                        alertBox.style.opacity = '0';
                        setTimeout(function() {
                            alertBox.style.display = 'none';
                        }, 500); // animasi fade out
                    }
                }, 3000); // hilang dalam 3 detik
            </script>
        @endif


        {{ $slot }}

    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/aos.js"></script>

    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
        integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous">
    </script>

    @livewireScripts

</body>

</html>
