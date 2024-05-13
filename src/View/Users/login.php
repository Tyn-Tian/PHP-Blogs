<div class="container pt-3 pt-sm-5" style="max-width: 500px;">
    <?php if (isset($model["error"])) { ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $model["error"] ?>
            </div>
        </div>
    <?php } ?>

    <p class="fw-bold fs-3 text-center">Welcome <span style="color: #1e88e5;">Back</span>.</p>
    <form class="pt-1" method="post" action="/users/login">
        <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" placeholder="name@example.com">
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <p class="fw-semibold mt-2">No account? <a class="text-decoration-none fw-bold text-success" href="/users/register">Create one</a></p>
        <button type="submit" class="btn btn-outline-success">Submit</button>
    </form>
</div>