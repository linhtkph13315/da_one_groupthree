<?php

namespace app\Http\Controllers\Auth;

use app\Services\Mail;
use app\Services\Request;
use app\Users;
use database\DB;

class RegisterController
{
    protected Mail $mail;
    protected Users $users;

    public function __construct()
    {
        $this->users = new Users();
        $this->mail = new Mail();
        AuthController::isLogin();
    }

    public function register(Request $request)
    {
        $validate = [];
        if ($request->post()) {
            $input = $request->body();

            $validate = $request->validate([
                'email' => 'required|email',
                'name' => 'required|min:6',
                'password' => 'required|min:6|max:32',
                'confirm_password' => 'required|min:6|match:password'
            ], [
                'email.required' => 'Vui lòng điền thông tin',
                'email.email' => 'Email không đúng định dạng',
                'name.required' => 'Vui lòng điền thông tin',
                'name.min' => 'Vui lòng điền tối tiểu 6 ký tự',
                'password.required' => 'Vui lòng điền thông tin',
                'password.min' => 'Vui lòng điền tối tiểu 6 ký tự',
                'password.max' => 'Tối đa chỉ đạt 32 ký tự',
                'confirm_password.required' => 'Vui lòng điền thông tin',
                'confirm_password.min' => 'Vui lòng điền tối tiểu 6 ký tự',
                'confirm_password.match' => 'Mật khẩu xác nhận không chính xác'
            ]); 

            $ck_email = $this->users->findEmail($input['email']);
            if ($ck_email) {
                $validate['email'][] = 'Địa chỉ email đã tồn tại';
            }

            if (empty($validate)) {
                $name_arr = $this->users->nameSeparation($input['name']);
                $first_name = $name_arr['first_name'];
                $last_name = $name_arr['last_name'];
                $password = password_hash($input['password'], PASSWORD_DEFAULT);

                $user_id = DB::table('users')->insert([
                    'email' => $input['email'], 'name' => $input['name'], 'first_name' => $first_name,
                    'last_name' => $last_name, 'password' => $password
                ])->save();

                $token = encode_email_token($input['email']);
                $start_time = date("Y-m-d H:i:s");
                $end_time = date("Y-m-d H:i:s");

                DB::table('user_tokens')->insert([
                    'user_id' => $user_id, 'access_token' => $token, 'start_time' => $start_time, 'end_time' => $end_time
                ])->save();

                $verify_btn = 'Xác nhận tài khoản';
                $verify_url = route('account.verify.'.$token);
                $mail_title = "Xin chào $first_name!.<br><br>Bạn đã đăng ký tài khản tại Foly-moblie. Bạn hãy bấm vào nút dưới đây để xác nhận tài khoản:";
                $mail_subject = 'Xác nhận tài khoản POLY-MOBILE';
                $this->mail->email($input['email'])
                    ->subject($mail_subject)
                    ->body($mail_title, $verify_btn, $verify_url)
                    ->send();

                session_set('message', 'Chúng tôi đã gửi cho bạn một liên kết xác nhận tài khoản vào email bạn đăng ký');
                redirect('account.register');
            }
        }
        view('auth.register', [
            'errors' => $validate
        ]);
    }

    public function verify($token)
    {
        $message = '';
        $users_token = DB::table('user_tokens')->select('*')
            ->where('access_token', '=', $token)
            ->execute()->first();
        if (!empty($users_token)) {
            if ($users_token['is_active'] == 0) {
                DB::table('user_tokens')->update([
                    'is_active' => 1
                ])->where('access_token', '=', $token)->save();

                DB::table('users')->update([
                    'is_verify' => 1
                ])->where('user_id', '=', $users_token['user_id'])->save();
                $message = 'Xác nhận tài khoản thành công';
            } else {
                error_page();
            }
        } else {
            error_page();
        }
        view('auth.verify', [
            'message' => $message
        ]);
    }
}