<?php
declare(strict_types=1);

final class AuthController extends Controller {
  public function login(): void {
    if (Auth::check()) $this->redirect('dashboard/index');

    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) {
        $error = "Security check failed. Refresh and try again.";
      } else {
        $email = trim((string)$this->post('email'));
        $pass = (string)$this->post('password');
        $user = User::findByEmail($email);
        if (!$user || !password_verify($pass, $user['password_hash'])) {
          $error = "Invalid email or password.";
        } else {
          Auth::login($user);
          $this->redirect('dashboard/index');
        }
      }
    }

    $this->render('auth/login', ['error' => $error]);
  }

  public function register(): void {
    if (Auth::check()) $this->redirect('dashboard/index');

    $error = null;
    $success = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!CSRF::verify($this->post('csrf'))) {
        $error = "Security check failed. Refresh and try again.";
      } else {
        $name = trim((string)$this->post('name'));
        $email = trim((string)$this->post('email'));
        $pass = (string)$this->post('password');

        if ($name === '' || $email === '' || $pass === '') {
          $error = "All fields are required.";
        } elseif (User::findByEmail($email)) {
          $error = "Email is already registered.";
        } else {
          $role = User::countAll() === 0 ? 'admin' : 'staff';
          User::create($name, $email, $pass, $role);
          $success = "Account created. You can login now.";
        }
      }
    }

    $this->render('auth/register', ['error' => $error, 'success' => $success]);
  }

  public function logout(): void {
    Auth::logout();
    $this->redirect('auth/login');
  }
}
