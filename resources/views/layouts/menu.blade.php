<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ff4500; text-align: center"><a class="navbar-brand"><img src="{{ asset('assets/logo_transparant.png') }}"></a></nav>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"></a>
    <div class="">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Configure Stores</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Configurations
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Assign Stores</a>
                        <a class="dropdown-item" href="#">Check Mismatch Skus</a>
                        <a class="dropdown-item" href="#">Quantity Alert</a>
                        <a class="dropdown-item" href="#">Download Stock Items</a>
                        <a class="dropdown-item" href="#">Change Shipstaton Keys</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Stock Items
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">List</a>
                        <a class="dropdown-item" href="#">Configuration Item</a>
                        <a class="dropdown-item" href="#">Stock Categories</a>
                        <a class="dropdown-item" href="#">Unit Of Measurement</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" aria-disabled="true">Gyftgo's Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Inbound Order List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('outboundorder') }}">Outbound Order List</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administrator
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Languages</a>
                        <a class="dropdown-item" href="#">Users</a>
                        <a class="dropdown-item" href="#">User Levels</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Settings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Terms & Conditions</a>
                        <a class="dropdown-item" href="#">Locations</a>
                        <a class="dropdown-item" href="#">Warehouse</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </div>
    </div>
</nav>
