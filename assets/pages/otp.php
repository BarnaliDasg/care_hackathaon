<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile OTP Verification</title>
</head>
<body>
    <h2>Enter Your Phone Number</h2>
    <input type="text" id="phone" placeholder="Enter phone number" required>
    <button onclick="sendOTP()">Send OTP</button>

    <h2>Enter OTP</h2>
    <input type="text" id="otp" placeholder="Enter OTP" required>
    <button onclick="verifyOTP()">Verify OTP</button>

    <script>
        function sendOTP() {
            var phone = document.getElementById("phone").value;

            fetch("send_otp.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "phone=" + phone
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }

        function verifyOTP() {
            var otp = document.getElementById("otp").value;

            fetch("verify_otp.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "otp=" + otp
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }
    </script>
</body>
</html>
