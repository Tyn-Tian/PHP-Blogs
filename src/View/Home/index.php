<div style="min-height: 100vh">
    <header>
        <nav class="navbar navbar-expand px-1">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-3 fw-bold" href="/">
                    <i class="bi bi-journal-text" style="font-size: 2rem; color: #1e88e5;"></i>
                    <span>
                        PHP <span style="color: #1e88e5;">Blog</span>
                    </span>
                </a>
                <div class="navbar" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto gap-4">
                        <li class="nav-item d-md-block d-none">
                            <a class="nav-link" aria-current="page" href="#">Github</a>
                        </li>
                        <li class="nav-item d-sm-block d-none">
                            <a class="nav-link" href="/users/login">Sign in</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-success" href="/users/register">Get started</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="pt-5 px-1">
        <div class="container pt-5">
            <div class="row">
                <div class="col-12">
                    <h1 id="title" class="fw-bold pt-5 display-1 d-inline"></h1>
                </div>
                <div class="col-xl-6 col-lg-8">
                    <p class="fs-5 mt-3">Embark on a journey of inspiration and discovery through captivating stories and insightful articles.</p>
                    <a href="/users/register" class="btn btn-outline-success mt-5 btn-lg">Get reading</a>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<script>
    var typed = new Typed('#title', {
        strings: [
            'Explore New <span style="color: #e0e0e0">Knowledge.</span>',
            'Embrace <span style="color: #e0e0e0">Inspiration.</span>',
        ],
        typeSpeed: 50,
        showCursor: false,
        backSpeed: 50,
    });
</script>