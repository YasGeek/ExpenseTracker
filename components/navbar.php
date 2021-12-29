<?php
    $loggedin = false;
    if (isset($_SESSION['username'])) {
        $loggedin = true;
        $username = $_SESSION['username'];
    }
?>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-xl">

        <a class="navbar-brand" href="/">
            <img src="images/logo.png" width="40">
            Expense Tracker
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" href="index.php">Home</a>
                </li>
            </ul>
        
            <div class="d-flex">
                <?php if($loggedin) { ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Hello, <?= $username ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="account.php">Account</a></li>
                            <li><hr class="dropdown-divider"></hr></li>
                            <li><a class="dropdown-item" href="signout.php">Sign Out</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                        <span>
                            <a class="btn btn-outline-light" href="login.php" type="submit">Login</a>
                            <span class="mr-4" />
                            <a class="btn btn-light" href="register.php" type="submit">Register</a>'
                        </span>
                    <?php } ?>
                ?>
            </a>

        </div>
    </div>
</nav>