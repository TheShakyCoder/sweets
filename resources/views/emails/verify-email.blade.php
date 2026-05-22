<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>Verify your email — {{ config('app.name') }}</title>
    <style type="text/css">
        @media only screen and (max-width: 600px) {
            .email-wrapper { width: 100% !important; }
            .email-body    { width: 100% !important; padding: 32px 20px !important; }
            .btn-primary   { width: 100% !important; display: block !important; text-align: center !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f5f5f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;-webkit-text-size-adjust:none;">

    <!-- Outer wrapper -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f5f4;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <!-- Email card -->
                <table class="email-wrapper" width="560" cellpadding="0" cellspacing="0" border="0"
                       style="width:560px;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">

                    <!-- ── Header banner ── -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#db2777 0%,#ec4899 50%,#be185d 100%);padding:40px 40px 36px;text-align:center;">

                            <!-- Logo -->
                            <img src="{{ config('app.url') }}/media/logo.png"
                                 alt="Acme Sweets"
                                 width="80" height="80"
                                 style="width:80px;height:80px;object-fit:contain;border-radius:50%;background-color:rgba(255,255,255,0.15);padding:6px;display:block;margin:0 auto 16px;" />

                            <p style="margin:0 0 4px;font-size:18px;font-weight:700;color:#ffffff;letter-spacing:-0.3px;">
                                Acme Sweets
                            </p>
                        </td>
                    </tr>

                    <!-- ── Body ── -->
                    <tr>
                        <td class="email-body" style="padding:44px 48px 36px;">

                            <!-- Greeting -->
                            <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#1c1917;letter-spacing:-0.4px;">
                                Hi {{ $user->name }},
                            </p>
                            <p style="margin:0 0 28px;font-size:15px;color:#78716c;line-height:1.6;">
                                Thanks for joining the Acme Sweets family. Before you get started,
                                we just need to confirm this is your email address.
                            </p>

                            <!-- Divider line accent -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td style="height:3px;background:linear-gradient(90deg,#ec4899,#f9a8d4,transparent);border-radius:2px;"></td>
                                </tr>
                            </table>

                            <!-- CTA button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}"
                                           class="btn-primary"
                                           style="display:inline-block;background-color:#ec4899;color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 36px;border-radius:10px;letter-spacing:0.1px;">
                                            Verify My Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiry notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="background-color:#fdf2f8;border:1px solid #fbcfe8;border-radius:10px;margin-bottom:28px;">
                                <tr>
                                    <td style="padding:14px 18px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top" style="padding-right:10px;font-size:18px;line-height:1;">⏱</td>
                                                <td style="font-size:13px;color:#be185d;line-height:1.5;">
                                                    This link will expire in <strong>{{ $expiresMinutes }} minutes</strong>.
                                                    If it expires, simply sign in and request a new one.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Didn't request notice -->
                            <p style="margin:0;font-size:13px;color:#a8a29e;line-height:1.6;">
                                If you didn't create an account with Acme Sweets,
                                you can safely ignore this email — no account will be activated.
                            </p>

                        </td>
                    </tr>

                    <!-- ── Fallback URL section ── -->
                    <tr>
                        <td style="padding:0 48px 36px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="background-color:#fafaf9;border:1px solid #e7e5e4;border-radius:10px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="margin:0 0 6px;font-size:12px;font-weight:600;color:#78716c;text-transform:uppercase;letter-spacing:0.6px;">
                                            Button not working?
                                        </p>
                                        <p style="margin:0 0 8px;font-size:12px;color:#a8a29e;line-height:1.5;">
                                            Copy and paste this link into your browser:
                                        </p>
                                        <p style="margin:0;font-size:11px;color:#ec4899;word-break:break-all;line-height:1.5;">
                                            {{ $verificationUrl }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ── Footer ── -->
                    <tr>
                        <td style="background-color:#1c1917;padding:28px 40px;border-radius:0 0 16px 16px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <p style="margin:0 0 4px;font-size:14px;font-weight:600;color:#ffffff;">
                                            {{ config('app.fullname') }}
                                        </p>
                                        <p style="margin:0 0 12px;font-size:12px;color:rgba(255,255,255,0.45);">
                                            Liverpool Road, Penwortham, Preston, PR1 0TB
                                        </p>
                                        <p style="margin:0 0 16px;font-size:11px;color:rgba(255,255,255,0.3);">
                                            Acme Sweets
                                        </p>
                                        <!-- Separator dots -->
                                        <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.25);">
                                            <a href="{{ config('app.url') }}" style="color:rgba(255,255,255,0.4);text-decoration:none;">Visit our website</a>
                                            &nbsp;&middot;&nbsp;
                                            <a href="mailto:{{ config('mail.from.address') }}" style="color:rgba(255,255,255,0.4);text-decoration:none;">{{ config('mail.from.address') }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- /Email card -->

                <!-- Below-card note -->
                <p style="margin:20px 0 0;font-size:12px;color:#a8a29e;text-align:center;">
                    You received this email because an account was created at
                    <a href="{{ config('app.url') }}" style="color:#ec4899;text-decoration:none;">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>
                    using this address.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>
