    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                      
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="{{ route('frontend.index') }}">Home</a></li>
                            <li>
                                <a href="{{ route('cart.index') }}"><i class="fa fa-shopping-cart"></i> Cart</a>
                            </li>
                            <li>
                                <a href="{{ route('track-pesanan.index') }}"><i class="fa fa-truck"></i> Track Order</a>
                            </li>
                            <li>
                                <a href="{{ route('alamat.index') }}"><i class="fa fa-user"></i> Profile</a>
                            </li>
                            @if (Auth::check() && Auth::user()->hasRole('distributor'))
                                <li>
                                    <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer"></i> Dashboard</a>
                                </li>
                            @endif

                        </ul>

                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->
