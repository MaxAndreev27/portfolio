<!DOCTYPE html>
<html>
<head>
    <title>Нове повідомлення з контактної форми</title>
</head>
<body>
<h1>Отримано нове повідомлення</h1>

<p>Ви отримали нове повідомлення з вашого вебсайту. Деталі:</p>

<hr>

<p><strong>Ім'я:</strong> {{ $name }}</p>
<p><strong>Email:</strong> {{ $email }}</p>

<p><strong>Повідомлення:</strong></p>
<div style="
        border: 1px solid #eee;
        padding: 15px;
        margin-top: 10px;
        background-color: #f9f9f9;
        white-space: pre-wrap;
    ">
    {{ $messageText }}
</div>

<p style="margin-top: 25px;">Це повідомлення було надіслано автоматично.</p>
</body>
</html>
