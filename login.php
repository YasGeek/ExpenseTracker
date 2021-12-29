<?php   
    include_once('php/db.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(json_encode($_POST), true);

        $username = htmlspecialchars(trim($data["username"]));
        $password = htmlspecialchars(trim($data["password"]));

        // check if username is unique
        $db_query = $conn->prepare("SELECT password FROM users WHERE username = ? ");
        $db_query->execute([$username]);
        if($db_query->rowCount() == 0) {
          $error = "This username does not exist.";
        } else { 
          $hash = $db_query->fetch(PDO::FETCH_ASSOC)["password"];
          $correct = password_verify($password, $hash);

          if(!correct) {
            $error = "Enter the correct password.";
          } else {
            // set session
            $_SESSION["username"] = $username;

            // redirect
            header("Location: account.php");
          }
        }
    }

    include_once('components/top.html');
?>
<link rel="stylesheet" href="css/login.css">
<?php
    include_once('components/navbar.php');
?>

<main>
    <form class="form-signin" method="POST">
      <h1 class="h3 mb-1 fw-normal">
        <span>
            <img class="mb-4" src="images/logo.png" alt="" width="40" height="40">
        </span>
            Expense Tracker
      </h1>

      <h2 class="h4 mb-3 fw-normal">
        Log in
      </h2>

      <?php if(isset($error)){ ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
      <?php } ?>

      <div class="form-floating">
        <input name="username" type="text" minlength="4" required class="form-control top" id="inUsername" placeholder="">
        <label for="isUsername">Username</label>
      </div>
      <div class="form-floating">
        <input name="password" type="password" minlength="4" required class="form-control bottom" id="floatingPassword" placeholder="">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign In</button>
      <p class="mt-2 text-center">No account yet? <a href="register.php">Register</a></p>
    </form>
</main>

<?php include_once('components/bottom.html'); ?>
