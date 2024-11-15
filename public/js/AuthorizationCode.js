document.addEventListener("DOMContentLoaded", () => {
    addSendCodeButtonHandlers();
});

function addSendCodeButtonHandlers() {
    document.querySelectorAll(".send-code-btn").forEach(button => {
        button.addEventListener("click", function () {
            const smartlockId = this.getAttribute("data-id");
            const userName = this.closest("tr").children[1].innerText;
            const accountUserId = this.getAttribute("data-account-user-id");

            console.log("smartlockId:", smartlockId);
            console.log("userName:", userName);
            console.log("accountUserId:", accountUserId);

           // Matomo Event Tracking
           console.log("_paq is:", typeof _paq);
           if (typeof _paq !== 'undefined') {
               _paq.push(['trackEvent', 'Smartlock', 'Send Code Click', 'Smartlock ID: ' + smartlockId]);
               console.log("Event tracking code executed");
           } else {
               console.error("Matomo tracking code (_paq) is not defined");
           }

            fetch("../../app/controllers/AuthorizationCodeController.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ smartlockId, userName, accountUserId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text(); // Retrieve response as plain text first
                })
                .then(text => {
                    if (text.trim() === "") {
                        throw new Error("Empty response from server");
                    }

                    try {
                        const data = JSON.parse(text); // Parse to JSON after confirming it's not empty
                        if (!data.error) {
                            alert("Authorization sent successfully!");
                            setTimeout(() => {
                                location.reload();
                            }, 1000); // Delay of 1000 milliseconds (1 second)
                        } else {
                            alert("Error: " + data.details);
                        }
                    } catch (err) {
                        console.error("JSON parse error:", err.message);
                        throw new Error(`Invalid JSON response: ${text}`);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error.message);
                    alert(`Error: ${error.message}`);
                });
        });
    });
}
