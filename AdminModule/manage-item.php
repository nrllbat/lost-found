<?php

include '../conn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Missing Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        #addItemButton {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        #addItemButton:hover {
            background-color: #218838;
        }

        #resetButton {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        #resetButton:hover {
            background-color: #c82333;
        }

        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container">
        <h1>Manage Missing Items</h1>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <button id="addItemButton" onclick="openModal('addModal')">Add Item</button>
            <div class="filter-controls">
                <input type="text" id="searchInput" placeholder="Search by item name...">
                <select id="statusFilter">
                    <option value="">Filter by Status</option>
                    <option value="Missing">Missing</option>
                    <option value="Pending">Pending</option>
                    <option value="Found">Found</option>
                </select>
                <button id="resetButton">Reset</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Office Collection Centre</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="itemTableBody">
                <?php
                $stmt = $conn->prepare("SELECT id, name AS item_name, picture AS item_picture, OfficeCollectionCentre, status FROM missing_items");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Picture'><img src='data:image/jpeg;base64," . base64_encode($row['item_picture']) . "' alt='Item Image'></td>
                            <td data-label='Name' class='item-name'>" . htmlspecialchars($row['item_name']) . "</td>
                            <td data-label='Office Collection Centre'>" . htmlspecialchars($row['OfficeCollectionCentre']) . "</td>
                            <td data-label='Status' class='status'>" . htmlspecialchars($row['status']) . "</td>
                            <td data-label='Actions'>
                                <button class='edit-btn btn' onclick=\"openEditModal(" . $row['id'] . ")\">Edit</button>
                                <button class='delete-btn btn' onclick=\"deleteItem(" . $row['id'] . ")\">Delete</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div id="paginationControls"></div>

        <!-- View Modal -->
        <div class="modal" id="viewModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="viewItemName">Item Details</h2>
                    <button class="close-btn" onclick="closeModal('viewModal')">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="column">
                            <h3>Item Picture</h3>
                            <img id="viewItemPicture" alt="Item Picture" class="modal-picture">
                        </div>
                    </div>
                    <hr>
                    <p><strong>Status:</strong> <span id="viewStatus">Loading...</span></p>
                    <p><strong>Created On:</strong> <span id="viewCreated">Loading...</span></p>
                    <p><strong>Office Collection Centre:</strong> <span id="viewOffice">Loading...</span></p>
                </div>
                <div class="modal-footer">
                    <button class="close-btn" onclick="closeModal('viewModal')">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal" id="editModal">
            <div class="modal-content">
                <h2>Edit Item</h2>
                <form id="editForm" action="edit-item.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="editItemId" name="id">
                    <label for="editName">Item Name</label>
                    <input type="text" id="editName" name="name" required>

                    <label for="editPicture">Upload Picture</label>
                    <input type="file" id="editPicture" name="picture" accept="image/*">

                    <label for="editOffice">Office Collection Centre</label>
                    <select id="editOffice" name="OfficeCollectionCentre" required>
                        <option value="Centre A">Centre A</option>
                        <option value="Centre B">Centre B</option>
                        <option value="Centre C">Centre C</option>
                    </select>

                    <label for="editStatus">Status</label>
                    <select id="editStatus" name="status" required>
                        <option value="missing">Missing</option>
                        <option value="pending">Pending</option>
                        <option value="found">Found</option>
                    </select>

                    <button type="submit" class="save-btn btn">Save Changes</button>
                    <button type="button" class="close-btn btn" onclick="closeModal('editModal')">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal" id="addModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add Item</h2>
                    <button class="close-btn" onclick="closeModal('addModal')">×</button>
                </div>
                <form action="add-item.php" method="POST" enctype="multipart/form-data">
                    <label for="name">Item Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter item name" required>

                    <label for="picture">Upload Picture</label>
                    <input type="file" id="picture" name="picture" accept="image/*" required>

                    <label for="office">Office Collection Centre</label>
                    <select id="office" name="OfficeCollectionCentre" required>
                        <option value="" disabled selected>Select Office Collection Centre</option>
                        <option value="Centre A">Centre A</option>
                        <option value="Centre B">Centre B</option>
                        <option value="Centre C">Centre C</option>
                    </select>

                    <div class="modal-actions">
                        <button type="submit" class="save-btn">Add Item</button>
                        <button type="button" class="close-btn" onclick="closeModal('addModal')">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="view-edit-delete.js"></script>
    </div>

    <script>
        const rowsPerPage = 5;
        let currentPage = 1;

        function paginateTable(filteredRows) {
            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            // Hide all rows
            document.querySelectorAll('#itemTableBody tr').forEach(row => row.style.display = 'none');

            // Show rows for the current page
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            for (let i = start; i < end && i < totalRows; i++) {
                filteredRows[i].style.display = '';
            }

            // Update pagination controls
            const paginationControls = document.getElementById('paginationControls');
            paginationControls.innerHTML = '';

            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = 'pagination-btn';
                    if (i === currentPage) {
                        button.classList.add('active');
                    }
                    button.onclick = () => {
                        currentPage = i;
                        paginateTable(filteredRows);
                    };
                    paginationControls.appendChild(button);
                }
            }
        }

        function filterAndPaginateTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();

            const rows = document.querySelectorAll('#itemTableBody tr');
            const filteredRows = Array.from(rows).filter(row => {
                const itemName = row.querySelector('.item-name').textContent.toLowerCase();
                const status = row.querySelector('.status').textContent.toLowerCase();

                const matchesSearch = itemName.includes(searchInput);
                const matchesStatus = !statusFilter || status.includes(statusFilter);

                return matchesSearch && matchesStatus;
            });

            currentPage = 1; // Reset to the first page
            paginateTable(filteredRows);

            // Hide pagination if rows <= rowsPerPage
            document.getElementById('paginationControls').style.display = filteredRows.length <= rowsPerPage ? 'none' : 'block';
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            filterAndPaginateTable();
        }

        document.getElementById('searchInput').addEventListener('input', filterAndPaginateTable);
        document.getElementById('statusFilter').addEventListener('change', filterAndPaginateTable);
        document.getElementById('resetButton').addEventListener('click', resetFilters);

        // Initial call
        filterAndPaginateTable();

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>

    <script src="../include/idle-logout.js"></script>
</body>

</html>