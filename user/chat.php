<?php
require_once('../config/constants.php');

include('../partials-front/menu.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat với Admin - WowFood</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/chat.css">
</head>
<body>
    <div class="chat-container" style="margin-top: 100px; padding: 20px;">
        <div class="chat-wrapper">
            <div class="chat-header">
                <h2>💬 Chat với Admin</h2>
                <p>Chúng tôi sẽ phản hồi trong thời gian sớm nhất</p>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here -->
            </div>
            
            <div class="chat-input-container">
                <form id="chatForm" class="chat-form">
                    <input type="text" id="messageInput" placeholder="Nhập tin nhắn của bạn..." autocomplete="off" required>
                    <button type="submit" id="sendButton">Gửi</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

