<?php   
    include_once('php/db.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(json_encode($_POST), true);

        $username = htmlspecialchars(trim($data["username"]));
        $firstname = htmlspecialchars(trim($data["firstname"]));
        $lastname = htmlspecialchars(trim($data["lastname"]));
        $password = htmlspecialchars(trim($data["password"]));

        // check if username is unique
        $db_query = $conn->prepare("SELECT username FROM users WHERE username = ? ");
        $db_query->execute([$username]);
        if($db_query->rowCount() > 0) {
          $error = "This username is already taken";
        } else { 
          // create new user
          $statement = $conn->prepare("INSERT INTO users (username, firstname, lastname, password) VALUES (?,?,?,?)");
          $hash = password_hash($password, PASSWORD_DEFAULT);
          $statement->execute([$username, $firstname, $lastname, $hash]);

          // set session
          $_SESSION["username"] = $username;

          // redirect
          header("Location: account.php");
        }
    }

    include_once('components/top.html');
?>
<link rel="stylesheet" href="css/register.css">
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
        Register
      </h2>

      <?php if(isset($error)){ ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
      <?php } ?>

      <div class="form-floating">
        <input name="username"
          type="text" minlength="4" required 
          class="form-control top" id="inUsername" placeholder="">
        <label for="isUsername">User Name</label>
      </div>

      <div class="form-floating">
        <input name="firstname" 
          type="text" class="form-control" id="inFirstname" placeholder="">
        <label for="isFirstname">First Name</label>
      </div>

      <div class="form-floating">
        <input name="lastname" 
          type="text" class="form-control" id="inLastname" placeholder="">
        <label for="isLastname">Last Name</label>
      </div>

      <div class="form-floating">
        <input type="password" name="password"
            minlength="4" required 
            class="form-control bottom" id="floatingPassword" placeholder="">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
      <p class="mt-2 text-center">Already have an account? <a href="login.php">Log in</a></p>
    </form>
</main>

<?php include_once('components/bottom.html'); ?>
