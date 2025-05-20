<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
    <header class="site-navbar js-sticky-header site-navbar-target" role="banner">


        <div class="container">
            <div class="row align-items-center position-relative">


                <div class="site-logo">
                    <a href="index.html" class="text-black"><span class="text-primary">Explore Bogor</a>
                </div>

                <div class="col-12">
                    <nav class="site-navigation text-right ml-auto " role="navigation">

                        <ul class="site-menu main-menu js-clone-nav ml-auto d-none d-lg-block">
                            <li><a href="{{ route('beranda') }}"
                                    class="{{ Route::currentRouteNamed('beranda') ? 'active' : '' }} 
                    nav-link">Beranda</a>
                            </li>
                            <li><a href="{{ route('peta') }}"
                                    class="{{ Route::currentRouteNamed('peta') ? 'active' : '' }}
                    nav-link">
                                    Peta</a>
                            </li>

                            <li><a href="{{ route('artikel') }}"
                                    class="{{ Route::currentRouteNamed('artikel') ? 'active' : '' }}
                    nav-link">Berita
                                    dan Artikel</a>
                            </li>

                            <li><a href="{{ route('kontak') }}"
                                    class="{{ Route::currentRouteNamed('kontak') ? 'active' : '' }} 
                    nav-link">
                                    Kontak Kami
                                </a></li>

                            <li><button class=" btn btn-primary 
                    nav-link" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">
                                    Login</button></li>
                        </ul>
                    </nav>
                </div>

                <div class="toggle-button d-inline-block d-lg-none"><a href="#"
                        class="site-menu-toggle py-5 js-menu-toggle text-black"><span class="icon-menu h3"></span></a>
                </div>
            </div>
        </div>

    </header>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">LOGIN</h1>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="username">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" class="form-control" placeholder="Password">
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
