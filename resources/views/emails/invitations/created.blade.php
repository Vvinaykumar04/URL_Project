<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <p>Hello {{ $invitation->name }},</p>

    <p>
        You have been invited to join
        <strong>{{ $invitation->company_name }}</strong>
        as <strong>{{ $invitation->role }}</strong>.
    </p>

    <p>
        Use the link below to accept your invitation and create your account:
    </p>

    <p>
        <a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a>
    </p>

    <p>
        This invitation expires on {{ optional($invitation->expires_at)->format('d M Y h:i A') }}.
    </p>
</body>
</html>
