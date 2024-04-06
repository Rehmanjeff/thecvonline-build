<x-mail::message>
Hello {{ $user->first_name }},<br>

We received a password reset request for your account. If you initiated this request, please click on the button below to securely reset your password:

@if($user->verification_token)
<x-mail::button :url="config('general.frontend-url').'reset-password?email='.$user->email.'&token='.$user->verification_token" color="success">
    Reset Password
</x-mail::button>
<br>
<div style="text-align:center; font-size: small;">
If you did not request a password reset, no action is required. Your account is secure.
<br>
Alternatively, you can copy and paste the following link into your browser to reset your password:<br />
{{ config('general.frontend-url') }}?email={{ $user->email }}&token={{ $user->verification_token }}
</div>
<br>
@endif
Thank you for choosing {{ config('app.name') }}. If you have any questions or concerns, please contact our support team.
<br>
Best regards,<br>{{ config('app.name') }} Team
</x-mail::message>
