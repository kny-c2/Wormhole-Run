<?php
session_start();

$error = '';
$username = '';

// Only runs when the form was submitted
if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    //Read users.json and turn it into a PHP array
    $users = json_decode(file_get_contents('users.json'), true);

    if ($username === '' || $password === '') {
        $error = 'Please enter a username and password.';
    } elseif (isset($users[$username])) {
        $error = 'That username is already taken.';
    } else {
        // Add the new user with a hashed password
        $users[$username] = password_hash($password, PASSWORD_DEFAULT);
        // Save the binder: turn the array back into text and write it to users.json
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        // Redirect to main page, ignore rest of file
        header('Location: index.php');
        exit;
    }
}

include 'header.php';
?>

<main>
  <h1>Create an account</h1>

  <?php if ($error !== ''): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post" action="register.php">
    <label for="username">Username</label>
    <input type="text" id="username" name="username"
           value="<?php echo htmlspecialchars($username); ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password">

    <button type="submit">Create account</button>
  </form>

  <p>Already have an account? <a href="index.php">Log in</a></p>
</main>

<?php include 'footer.php'; ?>