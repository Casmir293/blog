<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Young Stars FC</title>
    <?php include_once(ROOT_PATH . '/src/views/templates/bootstrap_css.php'); ?>
    <link rel="stylesheet" href="src/views/user/user.css">
    <style>
        .outer-wrapper {
            margin-top: 230px;
            margin-bottom: 250px;
        }

        @media only screen and (min-width: 600px) {
            .outer-wrapper {
                margin-top: 90px;
                margin-bottom: 110px;
            }
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['id'])) {
        include_once(ROOT_PATH . '/src/views/templates/header.php');
    } else {
        include_once(ROOT_PATH . '/src/views/templates/unauthorized_header.php');
    } ?>

    <?php if (isset($_SESSION['message']) && $_SESSION['status']) {
        echo "<div class='alertContainer alert alert-success' role='alert'>" . htmlspecialchars($_SESSION['message']) . "</div>";
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    } else if (isset($_SESSION['message']) && !$_SESSION['status']) {
        echo "<div class='alertContainer alert alert-danger' role='alert'>" . htmlspecialchars($_SESSION['message']) . "</div>";
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    } ?>

    <section class="container outer-wrapper">
        <div class="wrapper p-5 shadow-lg">
            <form action="?page=profile&action=avatar" method="POST" enctype="multipart/form-data">
                <p class="text-center fw-bold fs-4"><?= htmlspecialchars(ucfirst($user_details['privilege'])) ?> Profile</p>
                <div class="d-flex flex-column align-items-center">
                    <input type="file" id="imageInput" name="image" class="d-none" accept="image/*" required>
                    <img id="imagePreview" class="rounded" src="<?= htmlspecialchars($user_details['avatar']) ?>" alt="avatar" style="width: 100px; height: 150px; object-fit: cover;">
                    <div role="button" class="upload-img rounded-pill bg-secondary py-1 px-3 mt-2 text-light">upload</div>
                </div>
                <div class="mt-3">
                    <p><b>Email</b>: <?= htmlspecialchars($user_details['email']) ?></p>
                    <p><b>Username</b>: <?= htmlspecialchars($user_details['username']) ?></p>
                    <p><b>Member since</b>: <?= htmlspecialchars($user_details['created_at']) ?></p>

                    <button id="submit-form" type="submit" class="btn btn-warning w-50">Save Photo</button>

                    <button id="loading" class="btn btn-warning w-100 d-none" disabled>
                        <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                        <span role="status">Loading...</span>
                    </button>
                </div>
            </form>
            <hr>

            <h6 class="text-center"><b>Update Password</b></h6>
            <form id="passwordForm" method="POST" action="?page=profile&action=change_password" onsubmit="return changePassword(event)">
                <div class="mb-3">
                    <label class="form-label">Old Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="old_password" maxlength="15" class="form-control" placeholder="Enter old password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">New password <span class="text-danger">*</span></label>
                    <input type="password" id="newPassword" name="new_password" maxlength="15" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirm password <span class="text-danger">*</span></label>
                    <input type="password" id="confirmPassword" maxlength="15" class="form-control" placeholder="Confirm new password" required>
                </div>

                <button id="save-password" type="submit" class="btn btn-warning w-100">Update Password</button>

                <button id="loading-password" class="btn btn-warning w-100 d-none" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                    <span role="status">Loading...</span>
                </button>

                <p class="mt-4"><a href="?page=delete_user">Delete account?</a></p>
            </form>
            <div id="alertContainer" class="alertContainer"></div>
        </div>
        </div>
    </section>
    <?php include_once(ROOT_PATH . '/src/views/templates/bootstrap_js.php'); ?>
    <div class="fixed-bottom">
        <?php
        include_once(ROOT_PATH . '/src/views/templates/footer.php');
        ?>
    </div>

    <script src="src/views/user/user.js"></script>
</body>

</html>