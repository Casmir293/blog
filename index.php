<?php
session_start();
define('ROOT_PATH', __DIR__);
// require_once './config/db.php';
require_once './src/controllers/auth_controller.php';
require_once './src/controllers/post_controller.php';
require_once './src/controllers/user_controller.php';

$authController = new AuthController($pdo);
$postController = new PostController($pdo);
$userController = new UserController($pdo);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : null;

switch ($page) {
        # REGISTRATION
    case 'register':
        if (isset($_SESSION['authenticated'])) {
            $authController->logout();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
            $message = $authController->register();

            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                header('Location: ?page=login');
            } else {
                header('Location: ?page=register');
            }
            exit();
        }
        include './src/views/auth/register.php';
        break;

        # RESEND EMAIL VERIFICATION
    case 'verify_email':
        if ($action === 'verify_email') {
            $message = $authController->verify_email();

            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                header('Location: ?page=login');
            } else {
                header('Location: ?page=register');
            }
            exit();
        }
        include './src/views/auth/register.php';
        break;

        # FORGOT PASSWORD
    case 'forgot_password':
        if (isset($_SESSION['authenticated'])) {
            $authController->logout();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'forgot_password') {
            $message = $authController->forgot_password();

            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                header('Location: ?page=login');
            } else {
                header('Location: ?page=forgot_password');
            }
            exit();
        }
        include './src/views/auth/forgot_password.php';
        break;

        # RESET PASSWORD
    case 'reset_password':
        if (isset($_SESSION['authenticated'])) {
            $authController->logout();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'reset_password') {
            $message = $authController->reset_password();

            $email = $_GET['email'];
            $token = $_GET['token'];
            $_SESSION['email'] = $email;
            $_SESSION['token'] = $token;
            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                header('Location: ?page=login');
            } else {
                if ($email && $token) {
                    header("Location: ?page=reset_password?email=$email&token=$token");
                } else {
                    header("Location: ?page=reset_password");
                }
            }
            exit();
        }
        include './src/views/auth/reset_password.php';
        break;

        # RESEND EMAIL VERIFICATION
    case 'resend_verification';
        if (isset($_SESSION['authenticated'])) {
            $authController->logout();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'resend_verification') {
            $message = $authController->resend_verification();

            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['location'] == 'login') {
                header('Location: ?page=login');
            } else {
                header('Location: ?page=register');
            }
            exit();
        }
        include './src/views/auth/resend_verification.php';
        break;

        # LOGIN
    case 'login':
        if (isset($_SESSION['authenticated'])) {
            $authController->logout();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
            $message = $authController->login();
            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                $_SESSION['authenticated'] = $message['authenticated'];
                $_SESSION['id'] = $message['id'];
                header('Location: index.php');
            } else {
                header('Location: ?page=login');
            }
            exit();
        }
        include './src/views/auth/login.php';
        break;

        # LOGOUT
    case 'logout':
        $message = $authController->logout();
        $_SESSION['status'] = $message['status'];
        $_SESSION['message'] = 'Logout successful';
        header('Location: ?page=login');
        exit();

        # CREATE POST
    case 'create_post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create_post') {
            $message = $postController->create();

            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];

            if ($message['status']) {
                header('Location: index.php');
            } else {
                header('Location: ?page=create_post');
            }
            exit();
        }
        include './src/views/posts/create.php';
        break;

        # VIEW SINGLE POST
    case 'view_post':
        $post = $postController->view();

        if ($action === 'react_post') {
            $react = $postController->like_post();
            exit();
        }

        if ($action === 'add_comment') {
            $comment = $postController->add_comment();
            exit();
        }

        if ($action === 'delete_comment') {
            $comment = $postController->delete_comment();
            exit();
        }

        if ($action === 'delete_post') {
            $comment = $postController->delete_post();
            exit();
        }
        break;

        # VIEW USER PROFILE
    case 'profile':
        $user_details = $userController->view_user_profile();

        if (isset($_GET['action']) && $_GET['action'] === 'avatar') {
            $message = $userController->update_avatar();
            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];
            header('Location: ?page=profile');
            exit();
        }

        if (isset($_GET['action']) && $_GET['action'] === 'change_password') {
            $message = $userController->update_password();
            $_SESSION['status'] = $message['status'];
            $_SESSION['message'] = $message['message'];
            header('Location: ?page=profile');
            exit();
        }
        include './src/views/user/index.php';
        break;

        # DELETE USERS
    case 'delete_user':
        if ($action === 'delete_user') {
            $response = $userController->delete_user();

            $_SESSION['status'] = $response['status'];
            $_SESSION['message'] = $response['message'];
            exit();
        }
        include './src/views/user/delete_user.php';
        break;

        # LIST ALL USERS
    case 'all_users':
        $users = $userController->get_all_users();

        if ($action === 'update_privilege') {
            $response = $userController->update_privilege();
            $_SESSION['status'] = $response['status'];
            $_SESSION['message'] = $response['message'];
            header('Location: index.php');
            exit();
        }
        include './src/views/user/all_users.php';
        break;

    default:
        $posts = $postController->view_all_posts($category, $search_query, $page);
        include './src/views/posts/index.php';
        break;
}
