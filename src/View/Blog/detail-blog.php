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
    <div class="container mt-lg-5 mt-3" style="max-width: 700px;">
        <?php if (isset($model["error"])) { ?>
            <div class="row px-md-5">
                <div class="alert alert-danger" role="alert">
                    <?= $model["error"] ?>
                </div>
            </div>
        <?php } ?>

        <?php $content = preg_replace('/<p([^>]*)>/', '<p$1 class="fs-5">', $model["blog"]->content); ?>
        <h1 class="fw-bold display-5 mb-0"><?= $model["blog"]->title ?></h1>
        <?php
        $date = new DateTime($model["blog"]->createdAt);
        ?>
        <p class="p-0 mb-3"><?= $date->format('F j, Y') ?></p>
        <div class="d-flex gap-2 align-items-center mb-2 border-bottom pb-lg-4 pb-3">
            <i class="bi bi-person-fill" style="font-size: 1.2rem"></i>
            <a class="text-black text-decoration-none" href="/<?= $model['username'] ?>"><?= $model['username'] ?></a>
        </div>
        <?= $content ?>

        <a class="btn btn-outline-success" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            Comments
        </a>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form class="border-bottom pb-3 mb-3" method="post" action="/blog-<?= $model["blog"]->id ?>/new-comment">
                    <div class="form-floating mb-3">
                        <textarea name="content" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Comments</label>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Publish</button>
                </form>

                <?php if (isset($model["comments"])) {
                    foreach ($model["comments"] as $comment) { 
                        $date = new DateTime($comment['created_at']) ?>
                        <div class="border-bottom mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="bi bi-person-fill" style="font-size: 1.2rem"></i>
                                    <a class="text-black text-decoration-none" href="/<?= $comment['username'] ?>"><?= $comment['username'] ?></a>
                                </div>
                                <p class="p-0 mb-0"><?= $date->format('F j, Y') ?></p>
                            </div>
                            <p><?= $comment['content'] ?></p>
                            <?php if ($model["currentUsername"] == $comment["username"]) { ?>
                                <a href="/comment/delete/<?= $comment["id"] ?>" class="btn btn-outline-danger mb-3">Delete Comment</a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</main>