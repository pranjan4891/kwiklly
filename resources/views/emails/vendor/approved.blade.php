<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vendor Approval</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f9fc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden;">
        <div style="background-color: #4CAF50; padding: 20px; text-align: center;">
            <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="Logo" height="50">
            <h2 style="color: white;">Congratulations!</h2>
        </div>
        <div style="padding: 30px;">
            <p>Dear {{ $vendor->name }},</p>
            <p>We are pleased to inform you that your vendor registration has been <strong style="color: green;">approved</strong> by the admin.</p>
            <p>You can now log in and start managing your profile.</p>
            <p>Thank you for partnering with us.</p>
            <br>
            <p>Best Regards,<br>The Admin Team, KWIKLLY</p>
        </div>
    </div>
</body>
</html>
