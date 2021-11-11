<?php

namespace app\Http\Controllers\Auth;

use app\Services\Request;
use app\Users;
use app\Services\Mail;
use database\DB;

class ResetController
{
    protected Users $users;
    protected Mail $mail;

    public function __construct()
    {
        $this->mail = new Mail();
        $this->users = new Users();
        AuthController::isLogin();
    }

    public function forgotPassword(Request $request)
    {
        $validate = [];
        if ($request->post()) {
            $email = $request->input('email');

            $validate = $request->validate([
                'email' => 'required|email',
            ], [
                'email.required' => 'Vui lòng điền thông tin',
                'email.email' => 'Email không đúng định dạng',
            ]);

            $user = $this->users->findEmail($email);
            if (empty($user)) {
                $validate['email'][] = "Email không tồn tại trong hệ thống";
            }

            if (empty($validate)) {
                $token = encode_email_token($email);
                $start_time = date("Y-m-d H:i:s");
                $end_time = date("Y-m-d H:i:s", strtotime("+60 minutes"));

                DB::table('user_tokens')->insert([
                    'user_id' => $user['user_id'], 'access_token' => $token, 'start_time' => $start_time, 'end_time' => $end_time
                ])->save();

                $verify_btn = 'Cập nhật mật khẩu';
                $verify_url = route('account.password.reset.'.$token);
                $mail_title = "Xin chào ".$user['first_name']."!.<br><br>Bạn đã đã có yêu cầu đổi mật khẩu. Bạn hãy bấm vào nút dưới đây để cập nhật lại mật khẩu cho tài khoản, liên kết có thời hạn 60 phút:";
                $mail_subject = 'Quên mật khẩu POLY-MOBILE';
                $this->mail->email($email)
                    ->subject($mail_subject)
                    ->body($mail_title, $verify_btn, $verify_url)
                    ->send();

                session_set('message', 'Chúng tôi đã gửi cho bạn một liên kết cập nhật lại mật khẩu vào địa chỉ email bạn cung cấp.');
                redirect('account.forgot-password');
            }
        }
        view('auth.forgot-password.email', [
            'errors' => $validate
        ]);
    }

    public function resetPassword($token, Request $request)
    {
        $users_token = DB::table('user_tokens')->select('*')
            ->where('access_token', '=', $token)
            ->execute()->first();

        if (empty($users_token)) {
            error_page();
        }

        $time_now = strtotime(date("Y-m-d H:i:s"));
        $end_time = strtotime($users_token['end_time']);

        if ($time_now > $end_time || $users_token['is_active'] == 1) {
            error_page();
        }

        $user = $this->users->findUserByID($users_token['user_id']);

        $validate = [];
        if ($request->post()) {
            $password = $request->input('password');
            $validate = $request->validate([
                'password' => 'required|min:6|max:32',
                'confirm_password' => 'required|min:6|max:32|match:password'
            ]);

            if (empty($validate)) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                DB::table('users')->update([
                    'password' => $password, 'updated_at' => date("Y-m-d H:i:s")
                ])->where('user_id', '=', $users_token['user_id'])->save();

                DB::table('user_tokens')->update([
                    'is_active' => 1
                ])->where('access_token', '=', $token)->save();

                session_set('message', 'Cập nhật mật khẩu thành công');
                redirect('account.login');
            }
        }
        view('auth.forgot-password.reset', [
            'email' => $user['email'],
            'errors' => $validate
        ]);
    }
}