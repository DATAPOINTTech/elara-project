<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;

class AuthController
{
    private FirestoreService $db;
    private array $appConfig;

    public function __construct(FirestoreService $db)
    {
        $this->db = $db;
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';

        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    /**
     * Show login view.
     */
    public function showLogin(): void
    {
        // If already logged in, redirect to dashboard or POS based on role
        if (!empty($_SESSION['elara_user'])) {
            header('Location: /dashboard');
            exit;
        }

        $appConfig = $this->appConfig;
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require dirname(__DIR__) . '/Views/login.php';
    }

    /**
     * Authenticate user credentials.
     */
    public function authenticate(): void
    {
        $input = $_POST;
        $username = trim($input['username'] ?? '');
        $password = trim($input['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both username and password.';
            header('Location: /login');
            exit;
        }

        $users = $this->getRegisteredUsers();

        $matchedUser = null;
        foreach ($users as $user) {
            if (
                (strcasecmp($user['username'], $username) === 0 || strcasecmp($user['email'], $username) === 0) &&
                ($password === $user['password'] || password_verify($password, $user['password']))
            ) {
                $matchedUser = $user;
                break;
            }
        }

        if (!$matchedUser) {
            $_SESSION['login_error'] = 'Invalid username or password. Please try again.';
            header('Location: /login');
            exit;
        }

        // Set session
        $_SESSION['elara_user'] = [
            'id' => $matchedUser['id'],
            'username' => $matchedUser['username'],
            'name' => $matchedUser['name'],
            'role' => $matchedUser['role'], // 'admin', 'cashier', 'staff'
            'email' => $matchedUser['email'],
            'logged_at' => date('Y-m-d H:i:s'),
        ];

        // Redirect based on role
        if ($matchedUser['role'] === 'cashier') {
            header('Location: /pos');
        } else {
            header('Location: /dashboard');
        }
        exit;
    }

    /**
     * Logout user.
     */
    public function logout(): void
    {
        unset($_SESSION['elara_user']);
        session_destroy();
        header('Location: /login?status=logged_out');
        exit;
    }

    /**
     * Middleware check: ensure user is logged in.
     */
    public static function checkAuth(): ?array
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        if (empty($_SESSION['elara_user'])) {
            $_SESSION['login_error'] = 'Authentication required. Please log in to access studio management.';
            header('Location: /login');
            exit;
        }

        return $_SESSION['elara_user'];
    }

    /**
     * Middleware check: ensure user has admin role.
     */
    public static function requireAdmin(): array
    {
        $user = self::checkAuth();
        if ($user['role'] !== 'admin') {
            $_SESSION['login_error'] = 'Access restricted to administrators only. You are signed in as cashier.';
            header('Location: /pos?error=admin_only');
            exit;
        }

        return $user;
    }

    /**
     * Pre-configured studio users with Admin & Cashier roles.
     */
    public function getRegisteredUsers(): array
    {
        return [
            [
                'id' => 'usr_admin',
                'username' => 'admin',
                'email' => 'admin@elarabeaute.pk',
                'name' => 'Elia & Sehar (Administrator)',
                'password' => 'elara123',
                'role' => 'admin',
            ],
            [
                'id' => 'usr_sehar',
                'username' => 'sehar',
                'email' => 'sehar@elarabeaute.pk',
                'name' => 'Sehar Tariq (Co-Founder & Bridal Director)',
                'password' => 'elara123',
                'role' => 'admin',
            ],
            [
                'id' => 'usr_elia',
                'username' => 'elia',
                'email' => 'elia@elarabeaute.pk',
                'name' => 'Elia Khan (Co-Founder & Creative Director)',
                'password' => 'elara123',
                'role' => 'admin',
            ],
            [
                'id' => 'usr_cashier1',
                'username' => 'cashier1',
                'email' => 'cashier1@elarabeaute.pk',
                'name' => 'Fatima Sheikh (Front Desk Lead)',
                'password' => 'elara123',
                'role' => 'cashier',
            ],
            [
                'id' => 'usr_cashier2',
                'username' => 'cashier2',
                'email' => 'cashier2@elarabeaute.pk',
                'name' => 'Areeba Memon (POS Receptionist)',
                'password' => 'elara123',
                'role' => 'cashier',
            ],
        ];
    }
}
