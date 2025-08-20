<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vendor Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #fceaea; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden;">
        <div style="background-color: #e53935; padding: 20px; text-align: center;">
            <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="Logo" height="50">
            <h2 style="color: white;">We're Sorry!</h2>
        </div>
        <div style="padding: 30px;">
            <p>Dear {{ $vendor->name }},</p>
            <p>Unfortunately, your vendor registration has been <strong style="color: red;">rejected</strong> by the admin.</p>

            @if(!empty($vendor->admin_comments))
                <p><strong>Reason:</strong> {{ $vendor->admin_comments }}</p>
            @endif

            <p>If you believe this was in error or you would like to reapply, feel free to contact our support team.</p>
            <br>
            <p>Best Regards,<br>The Admin Team</p>
        </div>
    </div>
</body>
</html>
