document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("userTableBody");
    const userSearchInput = document.getElementById("userSearch");
    const searchButton = document.getElementById("searchButton");
    const sortButton = document.getElementById("sortButton");
    const paginationControls = document.getElementById("paginationControls");

    let originalUserData = [];
    let filteredData = [];
    const usersPerPage = 25;
    let currentPage = 1;
    let sortDirection = 'asc';

    // Ensure `userData` is a valid array
    try {
        if (Array.isArray(userData)) {
            originalUserData = userData;
        } else {
            originalUserData = JSON.parse(userData);
        }
        filteredData = originalUserData;
    } catch (error) {
        console.error("Error parsing user data:", error);
        originalUserData = [];
        filteredData = [];
    }

    // Function to append a user row to the table
    function appendUserRow(user) {
        const row = document.createElement("tr");

        // Format the creation date
        let formattedCreationDate = "N/A";
        if (user.creationDate) {
            const date = new Date(user.creationDate);
            if (!isNaN(date.getTime())) {
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();
                formattedCreationDate = `${month}/${day}/${year}`;
            }
        }

        row.innerHTML = `
            <td>${user.userId || ""}</td>
            <td>${user.accountId || ""}</td>
            <td>${user.email || ""}</td>
            <td>${user.name || user.userName || ""}</td>
            <td>${formattedCreationDate}</td>
        `;
        tableBody.appendChild(row);
    }

    // Function to render the user data for the current page
    function renderTable(data, page) {
        tableBody.innerHTML = '';
        const startIndex = (page - 1) * usersPerPage;
        const endIndex = Math.min(startIndex + usersPerPage, data.length);
        const usersToDisplay = data.slice(startIndex, endIndex);

        if (usersToDisplay.length > 0) {
            usersToDisplay.forEach(user => appendUserRow(user));
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
        }

        renderPaginationControls(data.length, page);
    }

    // Function to render pagination controls
    function renderPaginationControls(totalUsers, page) {
        paginationControls.innerHTML = "";

        const totalPages = Math.ceil(totalUsers / usersPerPage);

        if (totalPages <= 1) {
            return;
        }

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

    // Function to filter the table based on search input
    function filterTable() {
        const searchTerm = userSearchInput.value.trim().toLowerCase();

        if (searchTerm === '') {
            filteredData = originalUserData;
        } else {
            filteredData = originalUserData.filter(user => {
                const name = (user.name || user.userName || '').toLowerCase();
                const email = (user.email || '').toLowerCase();
                return name.includes(searchTerm) || email.includes(searchTerm);
            });
        }

        sortData(filteredData);
        currentPage = 1;
        renderTable(filteredData, currentPage);
    }

    // Function to sort data alphabetically by Name
    function sortData(data) {
        data.sort((a, b) => {
            const nameA = (a.name || a.userName || '').toLowerCase();
            const nameB = (b.name || b.userName || '').toLowerCase();
            if (nameA < nameB) return sortDirection === 'asc' ? -1 : 1;
            if (nameA > nameB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
    }

    // Event listener for sort button
    sortButton.addEventListener('click', () => {
        // Toggle sort direction
        sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';

        // Update sort icon
        const sortIcon = sortButton.querySelector('i');
        if (sortDirection === 'asc') {
            sortIcon.classList.remove('fa-sort-alpha-up-alt');
            sortIcon.classList.add('fa-sort-alpha-down');
        } else {
            sortIcon.classList.remove('fa-sort-alpha-down');
            sortIcon.classList.add('fa-sort-alpha-up-alt');
        }

        // Re-sort data and render table
        sortData(filteredData);
        renderTable(filteredData, currentPage);
    });

    // Event listener for search button
    searchButton.addEventListener('click', filterTable);

    // Handle Enter key press on the search input
    userSearchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            filterTable();
        }
    });

    // Initialize table with sorted data
    if (Array.isArray(originalUserData) && originalUserData.length > 0) {
        sortData(filteredData);
        renderTable(filteredData, currentPage);
    } else {
        console.error("No valid user data found:", originalUserData);
        renderTable([], 1);
    }
});
