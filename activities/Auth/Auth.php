<?php

namespace Auth;

use Database\DataBase;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once 'vendor/autoload.php';
class Auth
{

    protected function redirect($url) {
        header('Location: ' . trim(CURRENT_DOMAIN, '/ ') . '/' . trim($url, '/ '));
        exit;
    }

    protected function redirectBack()
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function hash($password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashPassword;
    }

    private function random() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function activationMessage($username, $verifyToken)
    {
        $message = "
                    <h1>[Bookaholic] Xác thực tài khoản</h1>
                    <h3>Xin chào $username, </h3>
                    <h3>Vui lòng nhấn vào link bên dưới để kích hoạt tài khoản!</h3>
                    <h3>Lưu ý: Email chỉ có hiệu lực trong vòng 10 phút!</h3>
                    <h3><a href=" . url('activation/' . $verifyToken) . ">kích hoạt ngay!</a></h3>
                    ";
        return $message;
    }

    // Gửi mail
    public function sendMail($emailAddress, $subject, $body) {
        // Tạo một đối tượng mail
        $mail = new PHPMailer(true);
    
        try {
            // Cấu hình server
            $mail->CharSet = "UTF-8";
            $mail->isSMTP();
            $mail->Host = $GLOBALS['smtpConfig']['host'];
            $mail->SMTPAuth = $GLOBALS['smtpConfig']['smtpAuth'];
            $mail->Username = $GLOBALS['smtpConfig']['username'];
            $mail->Password = $GLOBALS['smtpConfig']['password'];
            $mail->SMTPSecure = $GLOBALS['smtpConfig']['smtpSecure'];
            $mail->Port = $GLOBALS['smtpConfig']['port'];
    
            // Tiêu đề
            $mail->setFrom($GLOBALS['smtpConfig']['senderMail'], $GLOBALS['smtpConfig']['senderName']);
            $mail->addAddress($emailAddress);
    
            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
    
            $mail->send();
            echo 'Mail đã được gửi!';
            return true;
        } catch (Exception $e) {
            echo "Không thể gửi mail. Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function register() {
        require_once BASE_PATH . '/template/auth/register.php';
    }

    public function registerStore($request) {
        // Kiểm tra trường bắt buộc
        if (empty($request['email']) || empty($request['username']) || empty($request['password']) || empty($request['confirm-password'])) {
            flash('register_error', 'Tất cả các trường là bắt buộc');
            $this->redirectBack();
        } 
        // Kiểm tra mật khẩu và mật khẩu xác thực
        else if ($request['password'] !== $request['confirm-password']) {
            flash('register_error', 'Mật khẩu xác thực không trùng khớp');
            $this->redirectBack();
        } 
        // Kiểm tra độ dài mật khẩu
        else if (strlen($request['password']) < 8) {
            flash('register_error', 'Mật khẩu phải có độ dài tối thiểu 8 kí tự');
            $this->redirectBack();
        } 
        // Kiểm tra định dạng email
        else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            flash('register_error', 'Email nhập vào không đúng định dạng');
            $this->redirectBack();
        } 
        else {
            // Đăng ký thành công
            $db = new DataBase();
            
            // Kiểm tra xem email đã tồn tại hay chưa
            $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();
            if ($user != null) {
                flash('register_error', 'Email đã tồn tại');
                $this->redirectBack();
            } 
            else {
                if (!is_array($request)) {
                    flash('register_error', 'Dữ liệu đăng ký không hợp lệ');
                    $this->redirectBack();
                }
                // Sinh token và gửi email xác thực

                $randomToken = $this->random();
                $activationMessage = $this->activationMessage($request['username'], $randomToken);
                $result = $this->sendMail($request['email'], 'Xác thực tài khoản', $activationMessage);
                
                if ($result) {
                    // Lưu thông tin người dùng và token vào cơ sở dữ liệu
                    $request['verify_token'] = $randomToken;
                    $request['password'] = $this->hash($request['password']);
                    $db->insert('users', array_keys($request), array_values($request));
                    var_dump($request);
                    $this->redirect('login');
                } 
                else {
                    flash('register_error', 'Không thể gửi email xác thực!');
                    $this->redirectBack();
                }

                
            }
        }
    }    

    // Kích hoạt tài khoản
    public function activation($verifyToken) {
        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE verify_token = ? AND is_active = 0", [$verifyToken])->fetch();

        if ($user === null) {
            flash('activation_error', 'Không tìm thấy thông tin tài khoản để kích hoạt.');
            // $this->redirect('login');
        }
        // Tiếp tục chỉ khi người dùng được tìm thấy
        $result = $db->update('users', $user['id'], ['is_active'], [1]);

        if ($result) {
            flash('activation_success', 'Tài khoản đã được kích hoạt thành công. Bạn có thể đăng nhập.');
            $this->redirect('login');
        } else {
            // Xử lý lỗi khi cập nhật không thành công
            flash('activation_error', 'Không thể kích hoạt tài khoản. Vui lòng thử lại.');
            $this->redirect('login');
        }
    }

    public function login() {
        require_once BASE_PATH . '/template/auth/login.php';
    }

    public function checkLogin($request)
    {
        if (empty($request['email']) || empty($request['password'])) {
            flash('login_error', 'Các trường là bắt buộc!');
            $this->redirectBack();
        } else {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();
            if ($user != null) {
                if (password_verify($request['password'], $user['password']) && $user['is_active'] == 1) {
                    $_SESSION['user'] = $user['id'];
                    $this->redirect('admin');
                } else {
                    flash('login_error', 'Sai mật khẩu hoặc tài khoản chưa được kích hoạt!');
                    $this->redirectBack();
                }
            } else {
                flash('login_error', 'Tài khoản không tồn tại');
                $this->redirectBack();
            }
        }

    }

    public function checkAdmin()
    {
        if (isset($_SESSION['user'])) {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE id = ?", [$_SESSION['user']])->fetch();
            if ($user != null) {
                if ($user['permission'] != 'admin') {
                    $this->redirect('home');
                }

            } else {
                $this->redirect('home');
            }
        } else {
            $this->redirect('home');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            session_destroy();
        }
        $this->redirect('login');

    }

    public function forgot()
    {
        require_once BASE_PATH . '/template/auth/forgot-password.php';
    }

    public function forgotMessage($username, $forgotToken)
    {
        $message = '
                <h1>Khôi phục mật khẩu</h1>
                <h3>Xin chào, </h3>
                <h3>Hệ thống của chúng tôi vừa nhận được yêu cầu khôi phục lại mật khẩu từ phía bạn, vui lòng nhấp vào link bên dưới để có thể khôi phục mật khẩu.</h3>
                <div><a href="' . url('reset-password-form/' . $forgotToken) . '">Khôi phục mật khẩu</a></div>
                ';
        return $message;
    }

    public function forgotRequest($request) {
        if (empty($request['email'])) {
            flash('forgot_error', 'Các trường là bắt buộc!');
            $this->redirectBack();
        } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            flash('forgot_error', 'Email nhập vào không hợp lệ');
            $this->redirectBack();
        } else {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();
            if ($user == null) {
                flash('forgot_error', 'Vui lòng điền email cần khôi phục');
                $this->redirectBack();
            } else {
                $randomToken = $this->random();
                $forgotMessage = $this->forgotMessage($user['username'], $randomToken);
                $result = $this->sendMail($request['email'], 'Khôi phục mật khẩu', $forgotMessage);
                if ($result) {
                    $db->update('users', $user['id'], ['forgot_token', 'forgot_token_expire'], [$randomToken, date("Y-m-d H:i:s", strtotime('+15 minutes'))]);
                    $this->redirect('login');
                } else {
                    flash('forgot_error', 'Không thể gửi email');
                    $this->redirectBack();
                }

            }
        }
    }

    public function resetPasswordView($forgot_token)
    {
        require_once BASE_PATH . '/template/auth/reset-password.php';
    }

    public function resetPassword($request, $forgot_token) {
        if (!isset($request['password']) || strlen($request['password']) < 8) {
            flash('reset_error', 'Mật khẩu cần có tối thiểu 8 kí tự');
            $this->redirectBack();
        }else if($request['confirm-password'] !== $request['password']) {
            flash('reset_error', 'Mật khẩu không trùng khớp');
            $this->redirectBack();
        } else {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE forgot_token = ?", [$forgot_token])->fetch();
            if ($user == null) {
                flash('reset_error', 'Không tìm thấy thông tin của người dùng');
                $this->redirectBack();
            } else {
                if ($user['forgot_token_expire'] < date('Y-m-d H:i:s')) {
                    flash('reset_error', 'Liên kết xác thực đã hết hạn');
                    $this->redirectBack();
                }
                if ($user) {
                    $db->update('users', $user['id'], ['password'], [$this->hash($request['password'])]);
                    $this->redirect('login');
                } else {
                    $this->redirectBack();
                }
            }
        }
    }

}