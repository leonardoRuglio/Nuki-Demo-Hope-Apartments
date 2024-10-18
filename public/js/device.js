document.addEventListener("DOMContentLoaded", () => {
    const deviceTableBody = document.getElementById("deviceTableBody");
    const deviceSearchInput = document.getElementById("deviceSearch");
    const searchButton = document.getElementById("searchButton");
    const sortButton = document.getElementById("sortButton");

    let originalDeviceData = [];
    let filteredData = [];
    let sortDirection = 'asc';

    // Initialize the data
    try {
        if (Array.isArray(deviceData)) {
            originalDeviceData = deviceData;
        } else {
            originalDeviceData = JSON.parse(deviceData);
        }
        filteredData = originalDeviceData;
    } catch (error) {
        console.error("Error parsing device data:", error);
        originalDeviceData = [];
        filteredData = [];
    }

    // Function to append a device row to the table
    function appendDeviceRow(device) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${device.name || ""}</td>
            <td>${device.smartlockId || ""}</td>
            <td>${device.accountId || ""}</td>
        `;
        deviceTableBody.appendChild(row);
    }

    // Function to render the device table
    function renderTable(data) {
        deviceTableBody.innerHTML = '';
        if (data.length > 0) {
            data.forEach(device => appendDeviceRow(device));
        } else {
            deviceTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
        }
    }

    // Function to filter the table based on search input
    function filterTable() {
        const searchTerm = deviceSearchInput.value.trim().toLowerCase();
        if (searchTerm === '') {
            filteredData = originalDeviceData;
        } else {
            filteredData = originalDeviceData.filter(device => {
                const name = (device.name || '').toLowerCase();
                const accountId = String(device.accountId || '').toLowerCase(); // Convert accountId to string
                return name.includes(searchTerm) || accountId.includes(searchTerm);
            });
        }

        renderTable(filteredData);
    }

    // Function to sort data alphabetically by Name
    function sortData(data) {
        data.sort((a, b) => {
            const nameA = (a.name || '').toLowerCase();
            const nameB = (b.name || '').toLowerCase();
            if (nameA < nameB) return sortDirection === 'asc' ? -1 : 1;
            if (nameA > nameB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
    }

    // Event listener for sort button
    sortButton.addEventListener('click', () => {
        sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
        sortData(filteredData);
        renderTable(filteredData);
    });

    // Event listener for search button
    searchButton.addEventListener('click', filterTable);

    // Handle Enter key press on the search input
    deviceSearchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            filterTable();
        }
    });

    // Initialize table with the original data
    renderTable(filteredData);
});
