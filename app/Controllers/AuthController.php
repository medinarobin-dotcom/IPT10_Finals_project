<?php

class AuthController
{
    public function loginForm($request)
    {
        return view('auth/login');
    }

    public function login($request)
    {
        check_csrf($request);

        $user = User::findByEmail(trim($request->input('email')));

        if (!$user || !password_verify($request->input('password'), $user['password'])) {
            flash('Wrong email or password.', 'error');
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']];

        flash('Welcome back, ' . $user['name'] . '!');
        redirect($user['role'] === 'admin' ? '/admin' : '/');
    }

    public function registerForm($request)
    {
        return view('auth/register');
    }

    public function register($request)
    {
        check_csrf($request);

        $name = trim($request->input('name'));
        $email = trim($request->input('email'));
        $password = $request->input('password');

        $error = null;
        if ($name === '' || $email === '' || $password === '') {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif (User::findByEmail($email)) {
            $error = 'That email is already registered.';
        }

        if ($error) {
            flash($error, 'error');
            redirect('/register');
        }

        $id = User::create($name, $email, $password);
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $id, 'name' => $name, 'role' => 'customer'];

        flash('Account created. Happy shopping!');
        redirect('/');
    }

    public function logout($request)
    {
        check_csrf($request);

        unset($_SESSION['user']);
        flash('You are logged out.');
        redirect('/');
    }
}
