<?php

if (!function_exists('app_mail_is_configured')) {
    function app_mail_is_configured(): bool
    {
        return MAIL_HOST !== ''
            && MAIL_PORT > 0
            && MAIL_USERNAME !== ''
            && MAIL_PASSWORD !== ''
            && (MAIL_FROM_EMAIL !== '' || MAIL_USERNAME !== '');
    }
}

if (!function_exists('app_send_branded_otp_email')) {
    function app_send_branded_otp_email(string $toEmail, string $otp, array $copy = array()): array
    {
        $safeOtp = preg_replace('/\D+/', '', $otp);
        $inlineAssets = app_get_email_inline_assets();
        $logoSrc = isset($inlineAssets[0]['cid']) ? 'cid:' . $inlineAssets[0]['cid'] : '';
        $subject = trim((string) ($copy['subject'] ?? 'Pasar-kita OTP'));

        $defaults = array(
            'eyebrow' => MAIL_FROM_NAME,
            'preheader' => 'Secure account verification',
            'headline' => 'Your verification code is ready',
            'intro' => 'Use the OTP below to continue your secure verification step.',
            'code_label' => 'Use this code on the verification screen',
            'expires_label' => 'Expires in ' . APP_OTP_TTL_SECONDS . ' sec',
            'body_note' => 'Enter the code exactly as shown within ' . APP_OTP_TTL_SECONDS . ' seconds. Never share it with anyone.',
            'reason_title' => 'Why you received this',
            'reason_body' => 'A verification step was started for your Pasar-kita account.',
            'ignore_title' => 'Didn\'t request this?',
            'ignore_body' => 'Ignore this email and no changes will be made.',
        );

        $copy = array_merge($defaults, $copy);
        $htmlBody = app_build_otp_email_html($safeOtp, $copy, $logoSrc);
        $textBody = app_build_otp_email_text($safeOtp, $copy);

        return app_send_smtp_mail($toEmail, $subject, $htmlBody, $textBody, $inlineAssets);
    }
}

if (!function_exists('app_send_password_reset_email')) {
    function app_send_password_reset_email(string $toEmail, string $resetKey, string $accountLabel = 'Account'): array
    {
        $accountLabel = trim($accountLabel) !== '' ? trim($accountLabel) : 'Account';

        return app_send_branded_otp_email($toEmail, $resetKey, array(
            'subject' => 'Pasar-kita ' . $accountLabel . ' Reset OTP',
            'preheader' => 'Secure password recovery',
            'headline' => 'Your ' . $accountLabel . ' reset code is ready',
            'intro' => 'Use the OTP below to continue your password reset. Your password will not change until this code is verified.',
            'reason_body' => 'A password reset was requested for your ' . $accountLabel . ' account.',
            'ignore_body' => 'Ignore this email and your password will stay unchanged.',
        ));
    }
}

if (!function_exists('app_send_signup_otp_email')) {
    function app_send_signup_otp_email(string $toEmail): array
    {
        $otp = (string) random_int(100000, 999999);
        $result = app_send_branded_otp_email($toEmail, $otp, array(
            'subject' => 'Pasar-kita Signup OTP',
            'preheader' => 'Confirm your signup',
            'headline' => 'Confirm your signup with this OTP',
            'intro' => 'Use the OTP below to complete your signup. Your account will not be created until this code is verified.',
            'reason_body' => 'A new Pasar-kita signup was started with this email address.',
            'ignore_body' => 'Ignore this email and no new account will be created.',
        ));

        $result['otp'] = $otp;
        return $result;
    }
}

if (!function_exists('app_send_login_otp_email')) {
    function app_send_login_otp_email(string $toEmail, string $username): array
    {
        $otp = (string) random_int(100000, 999999);
        $result = app_send_branded_otp_email($toEmail, $otp, array(
            'subject' => 'Pasar-kita Login OTP',
            'preheader' => 'Confirm your login',
            'headline' => 'Confirm your login with this OTP',
            'intro' => 'Your username and password were correct. Enter the OTP below to finish signing in securely.',
            'reason_body' => 'A login attempt was made for the Pasar-kita username "' . $username . '".',
            'ignore_body' => 'Ignore this email if this login attempt was not yours.',
        ));

        $result['otp'] = $otp;
        return $result;
    }
}

if (!function_exists('app_build_otp_email_html')) {
    function app_build_otp_email_html(string $otp, array $copy, string $logoSrc = ''): string
    {
        $safeOtp = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');
        $safeFromName = htmlspecialchars(MAIL_FROM_NAME, ENT_QUOTES, 'UTF-8');
        $safeEyebrow = htmlspecialchars((string) $copy['eyebrow'], ENT_QUOTES, 'UTF-8');
        $safePreheader = htmlspecialchars((string) $copy['preheader'], ENT_QUOTES, 'UTF-8');
        $safeHeadline = htmlspecialchars((string) $copy['headline'], ENT_QUOTES, 'UTF-8');
        $safeIntro = htmlspecialchars((string) $copy['intro'], ENT_QUOTES, 'UTF-8');
        $safeCodeLabel = htmlspecialchars((string) $copy['code_label'], ENT_QUOTES, 'UTF-8');
        $safeExpiresLabel = htmlspecialchars((string) $copy['expires_label'], ENT_QUOTES, 'UTF-8');
        $safeBodyNote = htmlspecialchars((string) $copy['body_note'], ENT_QUOTES, 'UTF-8');
        $safeReasonTitle = htmlspecialchars((string) $copy['reason_title'], ENT_QUOTES, 'UTF-8');
        $safeReasonBody = htmlspecialchars((string) $copy['reason_body'], ENT_QUOTES, 'UTF-8');
        $safeIgnoreTitle = htmlspecialchars((string) $copy['ignore_title'], ENT_QUOTES, 'UTF-8');
        $safeIgnoreBody = htmlspecialchars((string) $copy['ignore_body'], ENT_QUOTES, 'UTF-8');
        $safeLogoSrc = htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8');
        $logoMarkup = $safeLogoSrc !== ''
            ? '<img src="' . $safeLogoSrc . '" alt="Pasar-kita" width="56" height="56" style="display:block;width:56px;height:56px;border:0;outline:none;text-decoration:none;">'
            : '<div style="width:56px;height:56px;border-radius:18px;background:#f2a900;color:#0f224a;font-size:24px;font-weight:800;line-height:56px;text-align:center;">P</div>';

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
</head>
<body style="margin:0;padding:0;background:#edf3fb;font-family:Arial,Helvetica,sans-serif;color:#14213d;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background:
        radial-gradient(circle at top left,#ffd98a 0%,rgba(255,217,138,0) 32%),
        radial-gradient(circle at bottom right,#b4d3ff 0%,rgba(180,211,255,0) 34%),
        linear-gradient(180deg,#edf3fb 0%,#f7faff 100%);">
        <tr>
            <td align="center" style="padding:34px 18px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:640px;margin:0 auto;">
                    <tr>
                        <td style="padding-bottom:16px;text-align:center;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#7284a7;">' . $safePreheader . '</td>
                    </tr>
                    <tr>
                        <td style="border-radius:30px;overflow:hidden;background:#0f224a;box-shadow:0 30px 80px rgba(15,34,74,.18);">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding:26px 28px 8px;background:
                                        radial-gradient(circle at top right,rgba(255,191,73,.28) 0%,rgba(255,191,73,0) 36%),
                                        linear-gradient(135deg,#0f224a 0%,#18387a 58%,#102a5d 100%);">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td valign="top" style="width:72px;padding-right:14px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="width:72px;height:72px;border-radius:24px;background:rgba(255,255,255,.12);text-align:center;vertical-align:middle;">' . $logoMarkup . '</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td valign="top" style="color:#ffffff;">
                                                    <div style="display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.18);font-size:12px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;">' . $safeEyebrow . '</div>
                                                    <h1 style="margin:18px 0 10px;font-size:34px;line-height:1.18;font-weight:800;color:#ffffff;">' . $safeHeadline . '</h1>
                                                    <p style="margin:0;max-width:430px;font-size:16px;line-height:1.75;color:rgba(255,255,255,.82);">' . $safeIntro . '</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:24px;">
                                            <tr>
                                                <td style="padding:0 0 26px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-radius:26px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);">
                                                        <tr>
                                                            <td style="padding:22px 22px 20px;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                                                    <tr>
                                                                        <td align="left" style="font-size:12px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase;color:#c6d5f3;">One-time password</td>
                                                                        <td align="right"><span style="display:inline-block;padding:7px 12px;border-radius:999px;background:#f4b334;color:#0f224a;font-size:12px;font-weight:800;letter-spacing:.8px;">' . $safeExpiresLabel . '</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" style="padding-top:18px;">
                                                                            <div style="padding:20px 18px;border-radius:22px;background:#ffffff;text-align:center;box-shadow:inset 0 0 0 1px rgba(14,44,98,.06);">
                                                                                <div style="font-size:12px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:#d18800;">' . $safeCodeLabel . '</div>
                                                                                <div style="margin-top:12px;font-size:44px;line-height:1.1;font-weight:900;letter-spacing:12px;color:#11295c;">' . $safeOtp . '</div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;background:#ffffff;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding:28px 28px 12px;font-size:17px;line-height:1.75;color:#475569;">' . $safeBodyNote . '</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 28px 24px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                                        <tr>
                                                            <td valign="top" style="width:50%;padding-right:8px;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="height:100%;border-radius:20px;background:#f8fbff;border:1px solid #d9e5f7;">
                                                                    <tr><td style="padding:18px 18px 16px;"><div style="font-size:12px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase;color:#7284a7;">' . $safeReasonTitle . '</div><div style="padding-top:10px;font-size:15px;line-height:1.7;color:#3d4b66;">' . $safeReasonBody . '</div></td></tr>
                                                                </table>
                                                            </td>
                                                            <td valign="top" style="width:50%;padding-left:8px;">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="height:100%;border-radius:20px;background:#fff9ef;border:1px solid #ffd690;">
                                                                    <tr><td style="padding:18px 18px 16px;"><div style="font-size:12px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase;color:#c27a00;">' . $safeIgnoreTitle . '</div><div style="padding-top:10px;font-size:15px;line-height:1.7;color:#6f5331;">' . $safeIgnoreBody . '</div></td></tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 28px 28px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #e4edf9;">
                                                        <tr><td style="padding-top:18px;font-size:13px;line-height:1.8;color:#7b8aa6;text-align:center;">This message was sent by ' . $safeFromName . ' using an authenticated SMTP connection.</td></tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
}

if (!function_exists('app_build_otp_email_text')) {
    function app_build_otp_email_text(string $otp, array $copy): string
    {
        return trim((string) $copy['headline']) . "\n\n"
            . trim((string) $copy['intro']) . "\n\n"
            . "Your 6-digit OTP: " . $otp . "\n\n"
            . trim((string) $copy['body_note']) . "\n\n"
            . trim((string) $copy['ignore_body']) . "\n";
    }
}

if (!function_exists('app_send_smtp_mail')) {
    function app_send_smtp_mail(string $toEmail, string $subject, string $htmlBody, string $textBody = '', array $inlineAttachments = array()): array
    {
        if (!app_mail_is_configured()) {
            return array(
                'success' => false,
                'error' => 'SMTP is not configured. Set MAIL_USERNAME, MAIL_PASSWORD, and MAIL_FROM_EMAIL in frontend/config/constants.php.',
            );
        }

        $toEmail = app_sanitize_email($toEmail);
        $fromEmail = app_sanitize_email(MAIL_FROM_EMAIL !== '' ? MAIL_FROM_EMAIL : MAIL_USERNAME);
        $replyToEmail = app_sanitize_email(MAIL_REPLY_TO_EMAIL !== '' ? MAIL_REPLY_TO_EMAIL : $fromEmail);
        $fromName = app_sanitize_header_value(MAIL_FROM_NAME);
        $host = app_sanitize_header_value(MAIL_HOST);
        $encryption = strtolower(trim((string) MAIL_ENCRYPTION));
        $port = (int) MAIL_PORT;
        $timeout = max(5, (int) MAIL_TIMEOUT);

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return array('success' => false, 'error' => 'Recipient email address is invalid.');
        }

        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            return array('success' => false, 'error' => 'Sender email address is invalid. Check MAIL_FROM_EMAIL.');
        }

        $remoteHost = ($encryption === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
        $socket = @stream_socket_client(
            $remoteHost,
            $errorNumber,
            $errorString,
            $timeout,
            STREAM_CLIENT_CONNECT,
            stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => MAIL_VERIFY_PEER,
                    'verify_peer_name' => MAIL_VERIFY_PEER,
                    'allow_self_signed' => !MAIL_VERIFY_PEER,
                ),
            ))
        );

        if ($socket === false) {
            return array('success' => false, 'error' => 'SMTP connection failed: ' . trim((string) $errorString) . ' (' . (int) $errorNumber . ').');
        }

        stream_set_timeout($socket, $timeout);

        $result = app_smtp_expect($socket, array(220), 'SMTP handshake');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $heloHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $heloHost = preg_replace('/[^A-Za-z0-9.-]/', '', $heloHost);
        if ($heloHost === '') {
            $heloHost = 'localhost';
        }

        $result = app_smtp_command($socket, 'EHLO ' . $heloHost, array(250), 'EHLO');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        if ($encryption === 'tls' || $encryption === 'starttls') {
            $result = app_smtp_command($socket, 'STARTTLS', array(220), 'STARTTLS');
            if (!$result['success']) {
                fclose($socket);
                return $result;
            }

            $cryptoEnabled = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if ($cryptoEnabled !== true) {
                fclose($socket);
                return array('success' => false, 'error' => 'Unable to start TLS encryption for SMTP.');
            }

            $result = app_smtp_command($socket, 'EHLO ' . $heloHost, array(250), 'EHLO after STARTTLS');
            if (!$result['success']) {
                fclose($socket);
                return $result;
            }
        }

        $result = app_smtp_command($socket, 'AUTH LOGIN', array(334), 'AUTH LOGIN');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $result = app_smtp_command($socket, base64_encode(MAIL_USERNAME), array(334), 'SMTP username');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $result = app_smtp_command($socket, base64_encode(MAIL_PASSWORD), array(235), 'SMTP password');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $result = app_smtp_command($socket, 'MAIL FROM:<' . $fromEmail . '>', array(250), 'MAIL FROM');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $result = app_smtp_command($socket, 'RCPT TO:<' . $toEmail . '>', array(250, 251), 'RCPT TO');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $result = app_smtp_command($socket, 'DATA', array(354), 'DATA');
        if (!$result['success']) {
            fclose($socket);
            return $result;
        }

        $payload = app_build_email_payload($toEmail, $fromEmail, $fromName, $replyToEmail, $subject, $htmlBody, $textBody, $inlineAttachments);
        $payload = preg_replace("/(?m)^\\./", '..', $payload);
        fwrite($socket, $payload . "\r\n.\r\n");

        $result = app_smtp_expect($socket, array(250), 'Message body');
        app_smtp_command($socket, 'QUIT', array(221), 'QUIT');
        fclose($socket);

        return $result;
    }
}

if (!function_exists('app_build_email_payload')) {
    function app_build_email_payload(
        string $toEmail,
        string $fromEmail,
        string $fromName,
        string $replyToEmail,
        string $subject,
        string $htmlBody,
        string $textBody,
        array $inlineAttachments = array()
    ): string {
        $outerBoundary = 'mixed_' . bin2hex(random_bytes(12));
        $alternativeBoundary = 'alt_' . bin2hex(random_bytes(12));
        $domain = app_email_domain($fromEmail);
        $messageId = sprintf('<%s@%s>', bin2hex(random_bytes(16)), $domain);
        $headers = array(
            'Date: ' . date(DATE_RFC2822),
            'To: ' . $toEmail,
            'From: ' . app_format_address($fromEmail, $fromName),
            'Reply-To: ' . $replyToEmail,
            'Subject: ' . app_sanitize_header_value($subject),
            'Message-ID: ' . $messageId,
            'MIME-Version: 1.0',
            'X-Mailer: Pasar-kita SMTP Mailer',
            'Content-Type: multipart/related; boundary="' . $outerBoundary . '"',
        );

        $textBody = app_normalize_newlines($textBody);
        $htmlBody = app_normalize_newlines($htmlBody);

        $body = '--' . $outerBoundary . "\r\n";
        $body .= 'Content-Type: multipart/alternative; boundary="' . $alternativeBoundary . '"' . "\r\n\r\n";
        $body .= '--' . $alternativeBoundary . "\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textBody . "\r\n";
        $body .= '--' . $alternativeBoundary . "\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlBody . "\r\n";
        $body .= '--' . $alternativeBoundary . "--\r\n";

        foreach ($inlineAttachments as $attachment) {
            if (
                !isset($attachment['content'], $attachment['content_type'], $attachment['filename'], $attachment['cid']) ||
                $attachment['content'] === ''
            ) {
                continue;
            }

            $body .= '--' . $outerBoundary . "\r\n";
            $body .= 'Content-Type: ' . app_sanitize_header_value($attachment['content_type']) . '; name="' . app_sanitize_header_value($attachment['filename']) . '"' . "\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= 'Content-ID: <' . app_sanitize_header_value($attachment['cid']) . '>' . "\r\n";
            $body .= 'Content-Disposition: inline; filename="' . app_sanitize_header_value($attachment['filename']) . '"' . "\r\n\r\n";
            $body .= chunk_split(base64_encode($attachment['content']), 76, "\r\n");
        }

        $body .= '--' . $outerBoundary . "--\r\n";

        return implode("\r\n", $headers) . "\r\n\r\n" . $body;
    }
}

if (!function_exists('app_get_email_inline_assets')) {
    function app_get_email_inline_assets(): array
    {
        $logoPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo2.png';
        if (!is_file($logoPath) || !is_readable($logoPath)) {
            return array();
        }

        $content = file_get_contents($logoPath);
        if ($content === false || $content === '') {
            return array();
        }

        return array(
            array(
                'filename' => 'pasar-kita-logo.png',
                'content_type' => 'image/png',
                'cid' => 'pasarkita-logo',
                'content' => $content,
            ),
        );
    }
}

if (!function_exists('app_smtp_command')) {
    function app_smtp_command($socket, string $command, array $expectedCodes, string $context): array
    {
        fwrite($socket, $command . "\r\n");
        return app_smtp_expect($socket, $expectedCodes, $context);
    }
}

if (!function_exists('app_smtp_expect')) {
    function app_smtp_expect($socket, array $expectedCodes, string $context): array
    {
        $response = app_smtp_read_response($socket);
        if ($response['code'] === 0) {
            return array('success' => false, 'error' => $context . ' failed. No response from SMTP server.');
        }

        if (!in_array($response['code'], $expectedCodes, true)) {
            return array(
                'success' => false,
                'error' => $context . ' failed. SMTP server response: ' . $response['message'],
            );
        }

        return array('success' => true, 'error' => '', 'response' => $response['message']);
    }
}

if (!function_exists('app_smtp_read_response')) {
    function app_smtp_read_response($socket): array
    {
        $message = '';

        while (($line = fgets($socket, 515)) !== false) {
            $message .= $line;
            if (preg_match('/^\d{3} /', $line) === 1) {
                break;
            }
        }

        $trimmed = trim($message);
        if ($trimmed === '' || preg_match('/^(\d{3})/', $trimmed, $matches) !== 1) {
            return array('code' => 0, 'message' => $trimmed);
        }

        return array('code' => (int) $matches[1], 'message' => $trimmed);
    }
}

if (!function_exists('app_sanitize_header_value')) {
    function app_sanitize_header_value(string $value): string
    {
        return trim(str_replace(array("\r", "\n"), '', $value));
    }
}

if (!function_exists('app_sanitize_email')) {
    function app_sanitize_email(string $value): string
    {
        return app_sanitize_header_value($value);
    }
}

if (!function_exists('app_normalize_newlines')) {
    function app_normalize_newlines(string $value): string
    {
        return preg_replace("/\r\n|\r|\n/", "\r\n", trim($value));
    }
}

if (!function_exists('app_email_domain')) {
    function app_email_domain(string $email): string
    {
        $parts = explode('@', $email);
        return isset($parts[1]) && $parts[1] !== '' ? $parts[1] : 'localhost';
    }
}

if (!function_exists('app_format_address')) {
    function app_format_address(string $email, string $name = ''): string
    {
        $email = app_sanitize_email($email);
        $name = app_sanitize_header_value($name);

        if ($name === '') {
            return $email;
        }

        return '"' . addcslashes($name, '"\\') . '" <' . $email . '>';
    }
}
