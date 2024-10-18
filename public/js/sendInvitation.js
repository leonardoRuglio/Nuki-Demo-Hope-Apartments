document.addEventListener("DOMContentLoaded", () => {
    // Event listener for sending individual invitations
    document.body.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("send-invite-btn")) {
            const button = e.target;
            const smartlockId = button.getAttribute("data-id");
            const name = button.closest("tr").children[1].innerText;
            const email = prompt("Enter the email address to send the invitation to:");

            if (!email) {
                alert("Email address is required.");
                return;
            }

            // Simple email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return;
            }

            // Call the PHP controller to send the invitation
            fetch("../../app/controllers/SendInvitationController.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    smartlockId: smartlockId,
                    name: name,
                    email: email
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (!data.error) {
                    alert("Invitation sent successfully!");
                } else {
                    alert("Error: " + data.details);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
        }
    });

    // Event listener for bulk invitation sending
    const sendAllInvitationsButton = document.getElementById("sendAllInvitationsButton");

    if (sendAllInvitationsButton) {
        sendAllInvitationsButton.addEventListener("click", function () {
            if (confirm("Are you sure you want to send invitations to all users?")) {
                fetch("../../app/controllers/SendInvitationController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        sendToAll: true
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.error) {
                        alert("Invitations sent to all users successfully!");
                    } else {
                        alert("Error: " + data.details);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
            }
        });
    }
});
