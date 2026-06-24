@component('mail::message')
# Your OTP Code

Use the code below to complete your {{ $otp->purpose->label() }}:

<div style="text-align: center; margin: 30px 0">
    <div class="active-code">{{ $otp->code }}</div>
</div>
<p>This code will expire in {{ setting('otp_expiration') }} minutes.</p>
<p>If you did not request this code, please ignore this email.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent