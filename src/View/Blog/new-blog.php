<div class="container">
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
                    <div class="dropstart">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person text-black opacity-50" style="font-size: 2rem;"></i>
                        </a>

                        <ul class="dropdown-menu">
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

    <main class="my-5">
        <div class="container px-lg-5">
            <?php if (isset($model["error"])) { ?>
                <div class="row px-md-5">
                    <div class="alert alert-danger" role="alert">
                        <?= $model["error"] ?>
                    </div>
                </div>
            <?php } ?>

            <form class="px-md-5" method="post" action="/new-blog">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="title" placeholder="name@example.com">
                    <label for="floatingInput">Title</label>
                </div>
                <textarea id="default-editor" name="content" placeholder="Tell your story...">
                </textarea>
                <button type="submit" class="btn btn-outline-success btn-lg mt-3">Publish</button>
            </form>
        </div>
    </main>
</div>

<script src="/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: 'textarea#default-editor'
    });
</script>