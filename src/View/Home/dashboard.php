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
            </div>
            <div class="gap-4 d-flex align-items-center">
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
                            <a class="dropdown-item d-flex align-items-center gap-3 py-0 opacity-75" href="/<?= $model['username'] ?>">
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
        <div class="row p-lg-5 p-3">
            <?php if (isset($model["error"])) { ?>
                <div class="row px-md-5 mb-3">
                    <div class="alert alert-danger" role="alert">
                        <?= $model["error"] ?>
                    </div>
                </div>
            <?php } ?>

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
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</main>