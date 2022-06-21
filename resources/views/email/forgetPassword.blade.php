<h1>Forget Password Email</h1>

You can reset password from bellow link:
<a href="{{ route('resetPassword', $token) }}">Reset Password</a>

1) a POST route that checks if email exists and if yes
would send a token and email to a button and sends this button to the user Email
( also the token would be saved to the database )

2) a GET route that checks if the coming URL has the token and email and compares them
with the token in the database , if yes sends the token to a specified route .

3) a POST request that posts the password and password_confirmation and token
to the postman . if valid , changes the password and logs in the user .


