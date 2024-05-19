<header>
    <nav class="navbar navbar-expand px-1 border-bottom">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center gap-3 fw-bold" href="/">
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
                <a class="d-md-flex d-none align-items-center text-decoration-none gap-2 fw-semibold text-black opacity-50" href="/new-blog">
                    <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i> Write
                </a>
                <div class="dropstart">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person text-black opacity-50" style="font-size: 2rem;"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="d-block d-md-none">
                            <a class="dropdown-item d-flex align-items-center gap-3 py-0 opacity-75" href="/new-blog">
                                <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i> Write
                            </a>
                        </li>
                        <li class="d-block d-md-none">
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 py-0 opacity-75" href="/<?= $model['currentUsername'] ?>">
                                <i class="bi bi-person" style="font-size: 1.5rem;"></i> Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-0 opacity-75" href="/users/logout">Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="container">
        <div class="row p-lg-5 p-2 px-md-0">
            <div class="col-12">
                <a class="text-black text-decoration-none d-inline-block mb-3 mb-sm-4" href="">
                    <h5 class="display-5 fw-semibold "><?= $model['username'] ?></h5>
                </a>
                <div>
                    <ul class="gap-5 text-decoration-none nav border-bottom">
                        <li class="nav-item border-bottom border-black pb-3">
                            <a class="nav-link active p-0 text-black fw-semibold " href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 text-black fw-semibold " href="/">Liked</a>
                        </li>
                    </ul>
                </div>
                <div class="row p-lg-5 p-3">
                    <?php if (isset($model['blogs'])) {
                        foreach ($model['blogs'] as $row) {
                            $content = preg_replace('/<p([^>]*)>/', '<p$1 class="limited-paragraph">', $row['content']); ?>
                            <div class="col-12 p-0 border-bottom mb-4">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <i class="bi bi-person-fill" style="font-size: 1.2rem"></i>
                                    <a class="text-black text-decoration-none" href="/<?= $row['username'] ?>"><?= $row['username'] ?></a>
                                </div>
                                <a href="/blog-<?= $row['id'] ?>/detail" class="text-black text-decoration-none">
                                    <h6 class="fw-bold fs-2"><?= $row['title'] ?></h6>
                                </a>
                                <?= $content ?>
                                <?php
                                $date = new DateTime($row['created_at']);
                                ?>
                                <p><?= $date->format('F j, Y') ?></p>
                                <?php if ($model["currentProfile"]) { ?>
                                    <div class="d-flex mb-3">
                                        <a href="/delete/<?= $row["id"] ?>" class="btn btn-outline-danger">Delete Blog</a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>