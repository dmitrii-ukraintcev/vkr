<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Вход</h1>
                <form class="d-grid" method="post">
                    <input type="hidden" name="module" value="dashboard">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="postAction" value="1">
                    <div class="form-outline mb-3">
                        <label for="username">Имя пользователя:</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" value="<?= $_POST['username'] ?? '' ?>">
                    </div>
                    <div class="form-outline mb-3">
                        <label for="password">Пароль:</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="<?= $_POST['password'] ?? '' ?>">
                    </div>
                    <div class="text-danger">
                        <?= $_SESSION['validation']['error'] ?? '' ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>