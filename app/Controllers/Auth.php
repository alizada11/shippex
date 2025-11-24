<?php

namespace App\Controllers;

use App\Models\ShippingBookingModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{

    public function index()
    {
        helper('app');
        $model = new UserModel();
        $data['title'] = 'Users List';
        $data['users'] = $model->orderBy('created_at', 'DESC')->paginate(12);
        $data['pager'] = $model->pager;

        return view('admin/users/index', $data);
    }

    public function userProfile($id)
    {
        $userModel = new UserModel();


        $data['profile'] = $userModel->where('id', $id)->first();
        $data['title'] = 'User Profile';
        return view('admin/users/profile', $data);
    }
    public function login()
    {
        $data['title'] = 'Login';
        return view('auth/login', $data);
    }

    public function loginPost()
    {
        $session = session();
        $model = new UserModel();
        $response = service('response');

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember'); // checkbox value

        // Find user by email or username
        $user = $model
            ->groupStart()
            ->where('email', $email)
            ->orWhere('username', $email)
            ->groupEnd()
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            // if ($user['email_verified'] == 0) {
            //     return redirect()->back()->with('error', 'Please verify your email before logging in.');
            // }

            //Set session data
            $sessionData = [
                'user_id'   => $user['id'],
                'full_name' => $user['firstname'] . ' ' . $user['lastname'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'role'      => $user['role'],
                'logged_in' => true,
            ];
            $session->set($sessionData);

            //Handle "Remember Me"
            if ($remember) {
                $token = bin2hex(random_bytes(32)); // secure random token

                // Save token in database
                $model->update($user['id'], ['remember_token' => $token]);

                // Set persistent cookie (30 days)
                $response->setCookie([
                    'name'     => 'remember_token',
                    'value'    => $token,
                    'expire'   => 60 * 60 * 24 * 30, // 30 days
                    'path'     => '/',
                    'domain'   => '',                // leave empty for localhost
                    'secure'   => false,             // true in production (HTTPS)
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }

            //Redirect safely with cookie attached
            return $response->redirect('/dashboard');
        }

        // Invalid credentials
        return redirect()->back()->with('error', 'Invalid login credentials.');
    }



    public function register()
    {
        $data['title'] = 'Register Form';
        return view('auth/register', $data);
    }

    public function registerPost()
    {
        $validation = \Config\Services::validation();
        $model = new \App\Models\UserModel();

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname'  => $this->request->getPost('lastname'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'phone_number'  => $this->request->getPost('phone_number'),
        ];

        // Validation Rules
        $rules = [
            'firstname' => 'required|min_length[2]|max_length[50]',
            'lastname'  => 'required|min_length[2]|max_length[50]',
            'username'  => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Generate token
        $token = bin2hex(random_bytes(32));

        // Insert user
        $userId = $model->insert([
            'firstname'  => $data['firstname'],
            'lastname'   => $data['lastname'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'       => 'customer',
            'email_verified' => 0,
            'email_verification_token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Send verification email
        $email = \Config\Services::email();
        $email->setFrom('info@shippex.online', 'Shippex Admin');
        $email->setTo($data['email']);
        $email->setSubject('Confirm Your Email');

        $verificationLink = base_url("verify-email/" . $token);

        $data['link'] = $verificationLink;
        $message = view('emails/verify_email', $data);


        $email->setMessage($message);
        $email->setMailType('html');
        $email->send();

        return redirect()->to('/login')->with(
            'success',
            'Registered successfully! Please check your email to verify your account.'
        );
    }

    public function verifyEmail($token)
    {
        $model = new \App\Models\UserModel();

        $user = $model->where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
        }

        // Update user as verified
        $model->update($user['id'], [
            'email_verified' => 1,
            'email_verification_token' => null,
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Email verified successfully! You can now log in.');
    }

    public function forgot()
    {
        $data['title'] = 'Frogot Password';
        return view('auth/forgot', $data);
    }

    public function forgotPost()
    {
        $user_email = $this->request->getPost('email');
        $model = new \App\Models\UserModel();
        $user = $model->where('email', $user_email)->first();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $db = \Config\Database::connect();
            $db->table('password_resets')->insert([
                'email' => $user_email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $link['name'] = $user['firstname'] . ' ' . $user['lastname'];
            $link['text'] = base_url('/reset-password/' . $token);

            $message = view('emails/forgot_password', $link);

            $email = \Config\Services::email();


            $email->setFrom('info@shippex.online', 'Shippex Admin');
            $email->setTo($user_email);
            $email->setSubject('Password Reset Link');
            $email->setMessage($message);
            $email->setMailType('html'); // Important

            $send =  $email->send();
            if ($send) {

                return redirect()->back()->with('success', "Reset link has been sent via email. Please check yout email");
            } else {

                return redirect()->back()->with('error', "There was a problem while sending email. Please make sure you typed correct email");
            }
        }

        return redirect()->back()->with('error', 'Email not found.');
    }
    public function getUserInfo($id)
    {
        $userModel = new \App\Models\UserModel();

        $bookingModel = new ShippingBookingModel();
        $shopperModel = new \App\Models\ShopperRequestModel();
        $packageModel = new \App\Models\PackageModel();

        $user = $userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found']);
        }

        // Example counts — adjust table and column names as needed
        $data = [
            'fullname' => $user['firstname'] . ' ' . $user['lastname'],
            'joined_date' => date('d M Y', strtotime($user['created_at'])),
            'total_bookings' => $bookingModel->where('user_id', $id)->countAllResults(),
            'total_shopper_requests' => $shopperModel->where('user_id', $id)->countAllResults(),
            'total_packages' => $packageModel->where('user_id', $id)->countAllResults(),
            'email' => $user['email'],
            'id' => $user['id'],
            'username' => $user['username'] ?? '-'
        ];

        return $this->response->setJSON($data);
    }

    public function reset($token)
    {

        return view('auth/reset', ['token' => $token, 'title' => 'New Password']);
    }

    public function resetPost($token)
    {
        $db = \Config\Database::connect();
        $reset = $db->table('password_resets')->where('token', $token)->get()->getRow();

        // Check if token is valid or expired
        if (!$reset || strtotime($reset->created_at) < strtotime('-1 hour')) {
            return redirect()->to('/forgot')->with('error', 'Token expired or invalid.');
        }

        // Validate passwords
        $validation = \Config\Services::validation();

        $rules = [
            'password' => [
                'label' => 'New Password',
                'rules' => 'required|min_length[6]|max_length[50]',
                // 'rules' => 'required|min_length[6]|max_length[50]|regex_match[/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/]',
                'errors' => [
                    'required' => 'Please enter a new password.',
                    'min_length' => 'Password must be at least 6 characters.',
                    // 'regex_match' => 'Password must contain an uppercase letter, lowercase letter, number, and special character.'
                ]
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Please confirm your new password.',
                    'matches' => 'Passwords do not match.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // If validation passes, hash and update
        $newPass = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $db->table('users')->where('email', $reset->email)->update(['password' => $newPass]);

        // Remove reset token
        $db->table('password_resets')->where('email', $reset->email)->delete();

        return redirect()->to('/login')->with('success', 'Password reset successfully.');
    }
    public function updateProfile()
    {
        // Ensure user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Load model
        $userModel = new \App\Models\UserModel();

        // Validation rules
        $rules = [
            'firstname'     => 'required|min_length[2]|max_length[50]',
            'lastname'      => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|valid_email',
            'phone_number'  => 'permit_empty|min_length[8]|max_length[20]',
            'address'       => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', $this->validator->listErrors());
        }

        // Prepare data for update
        $data = [
            'firstname'    => $this->request->getPost('firstname'),
            'lastname'     => $this->request->getPost('lastname'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address'      => $this->request->getPost('address'),
        ];

        // Update user
        $userModel->update($userId, $data);

        // Flash success message
        return redirect()->back()->with('success', 'Your profile has been updated successfully.');
    }

    public function changePassword()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        $data['title'] = 'Change Password';
        return view('auth/change_password', $data);
    }


    public function changePasswordPost()
    {

        $session = session();
        // Check if user is logged in
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }
        $userId = $session->get('user_id');
        $model = new \App\Models\UserModel();
        $user = $model->find($userId);

        // Validate inputs
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            return redirect()->back()->with('error', 'All password fields are required.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New password and confirm password do not match.');
        }

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Incorrect current password.');
        }

        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'New password must be at least 8 characters long.');
        }

        // Optional: Add more password strength validation here (uppercase, numbers, symbols, etc.)

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($model->update($userId, ['password' => $hashedPassword])) {
            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    public function logout()
    {
        $session = session();
        helper('cookie');

        $userId = $session->get('user_id');

        if ($userId) {
            $userModel = new UserModel();
            // Remove remember_token from DB
            $userModel->update($userId, ['remember_token' => null]);
        }

        // Delete remember_token cookie from browser
        delete_cookie('remember_token', '/');

        // Destroy the session completely
        $session->destroy();

        return redirect()->to('/login');
    }
}
