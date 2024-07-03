<?php
session_start();
require '../includes/config.php';
// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch user data
    $stmt = $pdo->prepare('SELECT id, password, role FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

// CSRF token generation
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

    <body class="auth-bg 100-vh">
        <div class="bg-overlay bg-light"></div>
    
        <div class="account-pages">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100 py-0 py-xl-4">
    
                                    <div class="text-center mb-5">
                                        <a href="index.html">
                                            <span class="logo-lg">
                                                <img src="assets/images/logo-dark.png" alt="" height="21">
                                            </span>
                                        </a>
                                    </div>
    
                                    <div class="card my-auto overflow-hidden">
                                            <div class="row g-0">
                                                <div class="col-lg-6">
                                                    <div class="p-lg-5 p-4">
                                                        <div class="text-center">
                                                            <h5 class="mb-0">Welcome Back !</h5>
                                                            <p class="text-muted mt-2">Sign in to continue to Invoika.</p>
                                                        </div>
                                                    
                                                        <div class="mt-4">
                                                            <form action="index.html" class="auth-input">
                                                                <div class="mb-3">
                                                                    <label for="username" class="form-label">Username</label>
                                                                    <input type="text" class="form-control" id="username" placeholder="Enter username">
                                                                </div>
                                        
                                                                <div class="mb-2">
                                                                    <label for="userpassword" class="form-label">Password</label>
                                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                                        <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input">
                                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                                                                   </div>
                                                                </div>
                    
                                                                <div class="form-check form-check-primary fs-16 py-2">
                                                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                                                    <div class="float-end">
                                                                        <a href="auth-resetpassword.html" class="text-muted text-decoration-underline fs-14">Forgot your password?</a>
                                                                    </div>
                                                                    <label class="form-check-label fs-14" for="remember-check">
                                                                        Remember me
                                                                    </label>
                                                                </div>
                    
                                                                <div class="mt-2">
                                                                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                                                                </div>
                    
                                                                <div class="mt-4 text-center">
                                                                    <div class="signin-other-title">
                                                                        <h5 class="fs-15 mb-3 title">Sign in with</h5>
                                                                    </div>
                                    
                                                                    <ul class="list-inline">
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript:void()" class="social-list-item bg-primary text-white border-primary">
                                                                                <i class="mdi mdi-facebook"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript:void()" class="social-list-item bg-info text-white border-info">
                                                                                <i class="mdi mdi-twitter"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript:void()" class="social-list-item bg-danger text-white border-danger">
                                                                                <i class="mdi mdi-google"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                    
                                                                <div class="mt-4 text-center">
                                                                    <p class="mb-0">Don't have an account ? <a href="auth-signup.html" class="fw-medium text-primary text-decoration-underline"> Signup now </a> </p>
                                                                </div>
                                                            </form>
                                                        </div>
                                    
                                                    </div>
                                                </div>
                    
                                                <div class="col-lg-6">
                                                    <div class="d-flex h-100 bg-auth align-items-end">
                                                        <div class="p-lg-5 p-4">
                                                            <div class="bg-overlay bg-primary"></div>
                                                            <div class="p-0 p-sm-4 px-xl-0 py-5">
                                                                <div id="reviewcarouselIndicators" class="carousel slide auth-carousel" data-bs-ride="carousel">
                                                                    <div class="carousel-indicators carousel-indicators-rounded">
                                                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                                    </div>
                                                                
                                                                    <!-- end carouselIndicators -->
                                                                    <div class="carousel-inner mx-auto">
                                                                        <div class="carousel-item active">
                                                                            <div class="testi-contain text-center">
                                                                                <h5 class="fs-20 text-white mb-0">“I feel confident
                                                                                    imposing
                                                                                    on myself”
                                                                                </h5>
                                                                                <p class="fs-15 text-white-50 mt-2 mb-0">Vestibulum auctor orci in risus iaculis consequat suscipit felis rutrum aliquet iaculis
                                                                                    augue sed tempus In elementum ullamcorper lectus vitae pretium Nullam ultricies diam
                                                                                    eu ultrices sagittis.</p>
                                                                            </div>
                                                                        </div>
                        
                                                                        <div class="carousel-item">
                                                                            <div class="testi-contain text-center">
                                                                                <h5 class="fs-20 text-white mb-0">“Our task must be to
                                                                                    free widening circle”</h5>
                                                                                <p class="fs-15 text-white-50 mt-2 mb-0">
                                                                                    Curabitur eget nulla eget augue dignissim condintum Nunc imperdiet ligula porttitor commodo elementum
                                                                                    Vivamus justo risus fringilla suscipit faucibus orci luctus
                                                                                    ultrices posuere cubilia curae ultricies cursus.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                        
                                                                        <div class="carousel-item">
                                                                            <div class="testi-contain text-center">
                                                                                <h5 class="fs-20 text-white mb-0">“I've learned that
                                                                                    people forget what you”</h5>
                                                                                <p class="fs-15 text-white-50 mt-2 mb-0">
                                                                                    Pellentesque lacinia scelerisque arcu in aliquam augue molestie rutrum Fusce dignissim dolor id auctor accumsan
                                                                                    vehicula dolor
                                                                                    vivamus feugiat odio erat sed  quis Donec nec scelerisque magna
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end carousel-inner -->
                                                                </div>
                                                                <!-- end review carousel -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                        </div>
                                    </div>
                                    <!-- end card -->
                                    
                                    <div class="mt-5 text-center">
                                        <p class="mb-0 text-muted">©
                                            <script>document.write(new Date().getFullYear())</script> Invoika. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- password-addon init -->
    <script src="assets/js/pages/password-addon.init.js"></script>

</body>

</html>