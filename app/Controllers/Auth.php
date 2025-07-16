<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginPost()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true,
            ]);
            return redirect()->to('/admin');
        } else {
            return redirect()->back()->with('error', 'Invalid login credentials.');
        }
    }
    public function register()
    {
        return view('auth/register');
    }

    public function registerPost()
    {
        $model = new \App\Models\UserModel();
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'staff',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $model->insert($data);
        return redirect()->to('/login')->with('success', 'Registered successfully!');
    }
    public function forgot()
    {
        return view('auth/forgot');
    }

    public function forgotPost()
    {
        $email = $this->request->getPost('email');
        $model = new \App\Models\UserModel();
        $user = $model->where('email', $email)->first();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $db = \Config\Database::connect();
            $db->table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // TODO: Send email (dummy link here)
            $link = base_url('/reset-password/' . $token);
            return redirect()->back()->with('success', "Reset link: $link");
        }

        return redirect()->back()->with('error', 'Email not found.');
    }
    public function reset($token)
    {
        return view('auth/reset', ['token' => $token]);
    }

    public function resetPost($token)
    {
        $db = \Config\Database::connect();
        $reset = $db->table('password_resets')->where('token', $token)->get()->getRow();

        if (!$reset || strtotime($reset->created_at) < strtotime('-1 hour')) {
            return redirect()->to('/forgot')->with('error', 'Token expired or invalid.');
        }

        $newPass = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $db->table('users')->where('email', $reset->email)->update(['password' => $newPass]);
        $db->table('password_resets')->where('email', $reset->email)->delete();

        return redirect()->to('/login')->with('success', 'Password reset successfully.');
    }
    public function changePassword()
    {
        return view('auth/change_password');
    }

    public function changePasswordPost()
    {
        $userId = session()->get('user_id');
        $model = new \App\Models\UserModel();
        $user = $model->find($userId);

        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Incorrect current password.');
        }

        $newPass = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        $model->update($userId, ['password' => $newPass]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
