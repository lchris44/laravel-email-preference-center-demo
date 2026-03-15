<!DOCTYPE html>
<html>
<body>
<h1>Monthly Newsletter</h1>
<p>Hello {{ $user->name }},</p>
<p>Here are your monthly updates and promotions.</p>
<p><a href="{{ $unsubscribeUrl }}">Unsubscribe from marketing emails</a></p>
</body>
</html>
