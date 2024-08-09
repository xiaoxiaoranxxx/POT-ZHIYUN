<?php

namespace app\model;

use PHPMailer\PHPMailer\PHPMailer;
use think\Model;
use think\exception\ValidateException;
use app\validate\MailValidate;
use think\facade\Cache;
use app\model\Sysinfo;

class PotMail extends Model
{
    protected $name = 'pot_mail';
    protected $pk = 'id';
    
    public static function sendmail($params)
    {

        $sysinfo=Sysinfo::getlist();
        if (Cache::get($params['email'])) {
            return ['error' => 1, "msg" => '已正确发送验证码,请2分钟后再试'];
        }
        try {
            validate(MailValidate::class)->check($params);
            $toemail = $params['email'];
            $mail = new PHPMailer();
            // 使用SMTP服务
            $mail->isSMTP();
            // 编码格式为utf8，不设置编码的话，中文会出现乱码
            $mail->CharSet = "utf8";
            // 发送人的SMTP服务器地址（QQ邮箱就是“smtp.qq.com”）
            $mail->Host = $sysinfo[4];
            // 是否使用身份验证
            $mail->SMTPAuth = true;
            // 发送人的邮箱用户名，就是你自己的SMTP服务使用的邮箱
            $mail->Username = $sysinfo[5];
            // 发送方的邮箱密码，注意这里填写的是“客户端授权密码”而不是邮箱的登录密码！
            $mail->Password = $sysinfo[6];
            // 使用ssl协议方式
            $mail->SMTPSecure = "ssl";
            //ssl协议方式端口号是465
            $mail->Port = $sysinfo[7];
            // 设置发件人信息，如邮件格式说明中的发件人，这里会显示为  Mailer(xxx@qq.com）
            $mail->setFrom($sysinfo[5], "企业验证码");
            // 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
            $mail->addAddress($toemail);
            // 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
            $mail->addReplyTo($sysinfo[5], "企业验证码");
            // 邮件标题
            $mail->Subject = "账户电子邮件验证";

            $verificationCode = '';
            for ($i = 0; $i < 6; $i++) {
                $verificationCode .= rand(1, 9);
            }
            // 邮件正文
            $mail->Body = "尊敬的用户 您好:<br>  感谢注册使用我们的云产品,您正在绑定邮箱,您的验证码为: " . $verificationCode . " <br>请勿向任何人提供此验证码。<br><br>（为保障您的安全性，请在2分钟内完成验证注册。）";

            $data = [
                'email' => $toemail,
                'code' => $verificationCode,
                'date' => gettime(), // 设置创建时间
                'status' => 0
            ];

            if (!$mail->send()) {
                // 发送邮件
                // echo "Message could not be sent.";
                // echo "Mailer Error: " . $mail->ErrorInfo; // 输出错误信息
                $arr = ['error' => 1, "msg" => '发送失败'];
            } else {
                $arr = ['error' => 0, "msg" => '发送成功'];
                Cache::set($toemail, $data['code'], 120);
                $data['status'] = 1;
            }
        } catch (ValidateException $e) { // 验证失败
            //dump($e->getError());
            $arr = ['error' => 1, "msg" => $e->getError()];
        } catch (\Exception $e) { // 有异常
            //dump($e->getMessage());
            $arr = ['error' => 1, "msg" => '系统错误'];
        }
        self::create($data);
        return $arr;
    }



    public static function listlog($params)
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $start = ($page - 1) * $limit;

        $where = isset($params['searchParams']) ? json_decode($params['searchParams'], true) ?? [] : [];

        $users = self::withSearch(['date'], $where)->order('id DESC')->limit($start, $limit)->select();

        $responseData = [
            'code' => 0,
            'msg' => '成功',
            'count' => self::withSearch(['date'], $where)->count(),
            'data' => $users->toArray()
        ];
        return $responseData;
    }


    public function searchDateAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereDay('date', $value);
        }
    }
}
