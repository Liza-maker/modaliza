function sendMessage() {
    var message = document.getElementById("messageInput").value;
    var user = document.getElementById("userName").value; // Utiliser l'ID utilisateur

    $.ajax({
        type: "POST",
        url: "send_message.php",  // Cette page backend traitera l'envoi
        data: { message: message, user: user },
        success: function(response) {
            $("#chatWindow").append("<p>" + user + " dit : '" + message + "'</p>");
            document.getElementById("messageInput").value = "";  // Vider le champ
        }
    });
}
