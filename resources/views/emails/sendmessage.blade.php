<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

            <style>

            </style>
            
</head>

<body>
<div class="container">
    <h1>Planaji</h1>
    <h3>Message</h3>
    <ul>
        <li>Name: {{ $details['fname'] }}</li>
        <li>Email: {{ $details['email'] }}</li>
        <li>Phone: {{ $details['phone'] }}</li>
        <li>Message: {{ $details['message'] }}</li>
    </ul>
</div>

</body>

</html>