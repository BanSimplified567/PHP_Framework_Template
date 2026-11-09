<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class MailService
{
    public function send(string $to, string $subject, string $body): bool
    {
        $to = trim($to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            error_log('Email sending failed: invalid recipient address.');
            return false;
        }

        if (!class_exists(PHPMailer::class)) {
            error_log('Email sending failed: PHPMailer class not found. Run composer install.');
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            $host = $this->config('MAIL_HOST', 'smtp.gmail.com');
            $username = $this->config('MAIL_USERNAME', '');
            $password = $this->config('MAIL_PASSWORD', '');
            $port = (int) $this->config('MAIL_PORT', 587);
            $encryption = strtolower((string) $this->config('MAIL_ENCRYPTION', 'tls'));
            $fromAddress = $this->config('MAIL_FROM_ADDRESS', $username);
            $fromName = $this->config('MAIL_FROM_NAME', $this->config('APP_NAME', 'PHP Application'));

            if ($username === '' || $password === '' || $fromAddress === '') {
                error_log('Email sending failed: mail credentials/from address are not configured.');
                return false;
            }

            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 20;
            $mail->SMTPAutoTLS = true;

            if ($encryption === 'ssl' || $encryption === 'smtps' || $port === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                if ($port === 587 && $encryption !== 'tls') {
                    $mail->Port = 465;
                }
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            if ($this->config('APP_DEBUG', false)) {
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
            }

            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($to);
            $mail->addReplyTo($fromAddress, $fromName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = trim(html_entity_decode(strip_tags($body), ENT_QUOTES, 'UTF-8'));

            $sent = $mail->send();
            if (!$sent) {
                error_log('Email sending failed: PHPMailer send returned false. ' . $mail->ErrorInfo);
            }
            return $sent;
        } catch (Exception $e) {
            error_log('Email sending failed: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
            return false;
        } catch (\Throwable $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendRegistrationWelcome(string $to, string $name, string $role): bool
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeRole = htmlspecialchars(ucfirst($role), ENT_QUOTES, 'UTF-8');
        $appName = htmlspecialchars($this->config('APP_NAME', 'PHP Application'), ENT_QUOTES, 'UTF-8');

        $subject = "Welcome to {$appName}";
        $body = "<h2>Welcome, {$safeName}!</h2>"
            . "<p>Your account has been created successfully.</p>"
            . "<p><strong>Email:</strong> " . htmlspecialchars($to, ENT_QUOTES, 'UTF-8') . "<br>"
            . "<strong>Role:</strong> {$safeRole}</p>"
            . "<p>You can now sign in with your credentials.</p>"
            . "<p>— {$appName}</p>";

        return $this->send($to, $subject, $body);
    }

    public function sendPasswordResetCode(string $to, string $name, string $code, int $minutesValid = 15): bool
    {
        $safeName = htmlspecialchars($name !== '' ? $name : 'there', ENT_QUOTES, 'UTF-8');
        $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
        $appName = htmlspecialchars($this->config('APP_NAME', 'PHP Application'), ENT_QUOTES, 'UTF-8');
        $minutes = max(1, $minutesValid);

        $subject = "{$appName} - Password Reset Code";
        $body = "<h2>Password reset request</h2>"
            . "<p>Hi {$safeName},</p>"
            . "<p>Use this verification code to continue resetting your account:</p>"
            . "<p style=\"font-size:24px;font-weight:700;letter-spacing:4px;margin:16px 0;\">{$safeCode}</p>"
            . "<p>This code expires in {$minutes} minute(s). If you did not request this, you can ignore this email.</p>"
            . "<p>— {$appName}</p>";

        return $this->send($to, $subject, $body);
    }

    public function sendGmailRegistrationInvite(string $to, string $registerUrl, int $minutesValid = 30): bool
    {
        $safeUrl = htmlspecialchars($registerUrl, ENT_QUOTES, 'UTF-8');
        $safeTo = htmlspecialchars($to, ENT_QUOTES, 'UTF-8');
        $appName = htmlspecialchars($this->config('APP_NAME', 'PHP Application'), ENT_QUOTES, 'UTF-8');
        $minutes = max(1, $minutesValid);

        $subject = "{$appName} - Create Your Account";
        $body = "<h2>Create your account</h2>"
            . "<p>We received a request to create an account for <strong>{$safeTo}</strong> using Gmail.</p>"
            . "<p>Click the button below to continue your registration:</p>"
            . "<p><a href=\"{$safeUrl}\" style=\"display:inline-block;padding:12px 18px;background:#2b2e33;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;\">Continue Registration</a></p>"
            . "<p>If the button does not work, copy and paste this link into your browser:<br><span style=\"word-break:break-all;\">{$safeUrl}</span></p>"
            . "<p>This link expires in {$minutes} minute(s). If you did not request this, you can ignore this email.</p>"
            . "<p>— {$appName}</p>";

        return $this->send($to, $subject, $body);
    }

    private function config(string $key, $default = null)
    {
        if (function_exists('env')) {
            $value = env($key, $default);
            return $value === null || $value === '' ? $default : $value;
        }
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

