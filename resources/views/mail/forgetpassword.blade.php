
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Hello {{$data['email'] ?? ''}},</p>

    <p>Welcome to Jumball, Your verification code is {{$data['otp'] ?? ''}}.</p>

   <!-- <p>OTP:{{$data['otp'] ?? ''}}</p> -->
</body>
</html>