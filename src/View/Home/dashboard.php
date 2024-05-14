<header>
    <nav class="navbar navbar-expand px-1">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center gap-3 fw-bold" href="#">
                    <i class="bi bi-journal-text" style="font-size: 2rem; color: #1e88e5;"></i>
                    <span>
                        PHP <span style="color: #1e88e5;">Blog</span>
                    </span>
                </a>
                <form class="d-none d-sm-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
            <div class="gap-4 d-flex align-items-center">
                <a class="text-black opacity-50 d-block d-sm-none" href="#">
                    <i class="bi bi-search" style="font-size: 1.5rem;"></i>
                </a>
                <a class="d-md-flex d-none align-items-center text-decoration-none gap-2 fw-semibold text-black opacity-50" href="#">
                    <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i> Write
                </a>
                <div class="dropstart">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person text-black opacity-50" style="font-size: 2rem;"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="d-block d-md-none">
                            <a class="dropdown-item d-flex align-items-center gap-3 py-0 opacity-75" href="#">
                                <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i> Write
                            </a>
                        </li>
                        <li class="d-block d-md-none">
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 py-0 opacity-75" href="#">
                                <i class="bi bi-person" style="font-size: 1.5rem;"></i> Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-0 opacity-75" href="#">Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>