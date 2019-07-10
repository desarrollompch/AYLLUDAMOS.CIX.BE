<header class="header">
    <nav class="navbar">
        <!-- Search Box-->
        <div class="search-box">
            <button class="dismiss"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
                <input type="search" placeholder="What are you looking for..." class="form-control">
            </form>
        </div>
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <!-- Navbar Header-->
                <div class="navbar-header">
                    <!-- Navbar Brand --><a href="{{url('/')}}" class="navbar-brand">
                        <div class="brand-text brand-big"><strong>TICAPERU</strong></div>
                        <div class="brand-text brand-small"><strong>TP</strong></div>
                    </a>
                    <!-- Toggle Button-->
                    @guest
                    @else
                        <a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
                    @endguest
                </div>
                <!-- Navbar Menu -->
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                @guest
                @else
                    <!-- Logout    -->
                        <li class="nav-item">
                            <a href="login.html" class="nav-link logout"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                            >
                                {{ __('Logout') }}<i class="fas fa-sign-out-alt"></i></a>
                        </li>
                    @endguest
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </nav>
</header>