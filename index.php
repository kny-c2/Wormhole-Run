<?php
session_start();

$error = '';
$username = '';

// Only runs when the form was submitted 
if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Read users.json and turn it into a PHP array
    $users = json_decode(file_get_contents('users.json'), true);

    // Is this username in the binder AND does the password match?
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['user'] = $username;   // Authorize and give session
        header('Location: setup.php');  // Redirect to setup page
        exit; // Ignore rest of file
    } else {
        $error = 'Wrong username or password.'; // Continue file
    }
}

include 'header.php';
?>

<main>
  <h1>Log in</h1>

  <?php if ($error !== ''): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post" action="index.php">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password">

    <button type="submit">Log in</button>
  </form>

  <p>New here? <a href="register.php">Create an account</a></p>
</main>

<?php include 'footer.php'; ?>