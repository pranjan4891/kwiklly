<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Store Location</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f9fc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden;">
        <div style="background-color: #FF6B35; padding: 20px; text-align: center;">
            <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="Logo" height="50">
            <h2 style="color: white;">Delivery Location</h2>
        </div>
        <div style="padding: 30px;">
            <p>Dear Delivery Personnel,</p>

            <p>Please collect the order from the vendor at the location provided below:</p>

            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #FF6B35;">
                <h3 style="margin-top: 0; color: #FF6B35;">{{ $vendorName }}</h3>
                <p><strong>Location:</strong><br>
                <a href="{{ $locationUrl }}" target="_blank" style="color: #007bff; text-decoration: none; font-weight: bold;">Click here to view on Google Maps</a></p>
            </div>

            <p>Please ensure timely pickup to maintain service quality.</p>

            <br>
            <p>Best Regards,<br>The KWIKLLY Team</p>
        </div>
    </div>
</body>
</html>
