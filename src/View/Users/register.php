<div class="container pt-3 pt-lg-5" style="max-width: 700px;">
    <?php if (isset($model["error"])) { ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $model["error"] ?>
            </div>
        </div>
    <?php } ?>

    <p class="fw-bold fs-3 text-center">Join PHP <span style="color: #1e88e5;">Blog</span>.</p>
    <form class="pt-1" method="post" action="/users/register">
        <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" placeholder="name@example.com">
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" name="username" class="form-control" placeholder="name@example.com">
            <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <p class="fw-semibold mt-2">Already have an account? <a class="text-decoration-none fw-bold text-success" href="">Sign in</a></p>
        <button type="submit" class="btn btn-outline-success">Submit</button>
    </form>
</div>