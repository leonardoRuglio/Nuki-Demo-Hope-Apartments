document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("smartlockTableBody");
    const searchButton = document.getElementById("searchButton");
    const searchInput = document.getElementById("searchInput");
    const noDataMessage = document.getElementById("noDataMessage");
    const paginationControls = document.getElementById("paginationControls");
    const filterButton = document.getElementById("filterButton");
    const filteredTableBody = document.getElementById("filteredTableBody");

    const usersPerPage = 25;
    let currentPage = 1;
    let filteredData = smartlockData;

    // Function to append a smartlock row to the specified table body
    function appendSmartlockRow(smartlock, tableBody, showExtendButton = false) {
        const row = document.createElement("tr");

        const formattedCreationDate = smartlock.creationDate ? new Date(smartlock.creationDate).toLocaleDateString("en-US") : "N/A";
        const formattedAllowedFromDate = smartlock.allowedFromDate ? new Date(smartlock.allowedFromDate).toLocaleDateString("en-US") : "N/A";
        const formattedAllowedUntilDate = smartlock.allowedUntilDate ? new Date(smartlock.allowedUntilDate).toLocaleDateString("en-US") : "N/A";

        row.innerHTML = `
            <td>${smartlock.deviceName || "N/A"}</td>
            <td>${smartlock.userName || "N/A"}</td>
            <td>${formattedCreationDate}</td>
            <td>${formattedAllowedFromDate}</td>
            <td>${formattedAllowedUntilDate}</td>
            ${
                showExtendButton
                    ? `<td><button class="btn btn-warning extend-date-btn" data-id="${smartlock.smartlockId}">Extend Date</button></td>`
                    : ""
            }
        `;
        tableBody.appendChild(row);
    }

    function renderTable(data, page) {
        tableBody.innerHTML = "";
        const startIndex = (page - 1) * usersPerPage;
        const endIndex = Math.min(startIndex + usersPerPage, data.length);
        const usersToDisplay = data.slice(startIndex, endIndex);

        usersToDisplay.forEach((user) => appendSmartlockRow(user, tableBody));

        noDataMessage.style.display = data.length === 0 ? "block" : "none";
        renderPaginationControls(data.length, page);
        addExtendDateHandlers();
    }

    function renderPaginationControls(totalUsers, page) {
        paginationControls.innerHTML = "";
        const totalPages = Math.ceil(totalUsers / usersPerPage);

        const prevButton = document.createElement("button");
        prevButton.innerText = "Previous";
        prevButton.disabled = page === 1;
        prevButton.classList.add("btn", "btn-primary", "me-2");
        prevButton.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable(filteredData, currentPage);
            }
        });

        const nextButton = document.createElement("button");
        nextButton.innerText = "Next";
        nextButton.disabled = page === totalPages;
        nextButton.classList.add("btn", "btn-primary");
        nextButton.addEventListener("click", () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable(filteredData, currentPage);
            }
        });

        paginationControls.appendChild(prevButton);
        paginationControls.appendChild(nextButton);
    }

    renderTable(filteredData, currentPage);

    // Search function
    searchButton.addEventListener("click", () => {
        const searchValue = searchInput.value.trim().toLowerCase();
        filteredData = smartlockData.filter((smartlock) => {
            const deviceName = smartlock.deviceName ? smartlock.deviceName.toLowerCase() : "";
            const userName = smartlock.userName ? smartlock.userName.toLowerCase() : "";
            return deviceName.includes(searchValue) || userName.includes(searchValue);
        });

        currentPage = 1;
        renderTable(filteredData, currentPage);
    });

    // Filter logic for the 7-9 day permission
    filterButton.addEventListener("click", () => {
        filteredTableBody.innerHTML = ""; // Clear previous results
        const today = new Date(); // Get today's date

        // Subtract one day from today's date
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        let found = false; // Track if any matching data is found

        // Filter smartlocks where the difference between yesterday and the expiration date is between 0 and 7 days
        smartlockData.forEach((smartlock) => {
            const allowedUntilDate = new Date(smartlock.allowedUntilDate);

            if (isNaN(allowedUntilDate.getTime())) {
                console.error("Invalid date:", smartlock.allowedUntilDate);
                return; // Skip this entry
            }

            const differenceInDays = Math.ceil(
                (allowedUntilDate - yesterday) / (1000 * 60 * 60 * 24)
            );

            // Show the smartlocks expiring in the next 7 days starting from yesterday
            if (differenceInDays >= 0 && differenceInDays <= 7) {
                appendSmartlockRow(
                    {
                        smartlockId: smartlock.id,
                        deviceName: smartlock.deviceName,
                        userName: smartlock.userName,
                        creationDate: smartlock.creationDate,
                        allowedFromDate: smartlock.allowedFromDate,
                        allowedUntilDate: smartlock.allowedUntilDate,
                    },
                    filteredTableBody,
                    true
                ); // Pass true to show "Extend Date" button

                found = true;
            }
        });

        // Show or hide the "No data found" message
        noDataMessage.style.display = found ? "none" : "block";

        // Add event handlers for the "Extend Date" buttons
        addExtendDateHandlers();
    });

    function addExtendDateHandlers() {
        const extendDateButtons = document.querySelectorAll(".extend-date-btn");

        extendDateButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const smartlockId = this.getAttribute("data-id");
                const addDays = 3;
                const name = this.closest("tr").children[1].innerText;
                const allowedUntilDate = this.closest("tr").children[4].innerText;
                const allowedFromDate = this.closest("tr").children[3].innerText;

                fetch("../../app/controllers/ExtendDateController.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        id: smartlockId,
                        addDays: addDays,
                        name,
                        allowedUntilDate,
                        allowedFromDate,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.error) {
                            alert("Date extended successfully!");
                            location.reload();
                        } else {
                            alert("Error: " + data.details);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                    });
            });
        });
    }
});
