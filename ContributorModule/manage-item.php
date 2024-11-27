<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "Contributor") {
    header("Location: ../login/login.php");
    exit();
}
include '../conn.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Missing Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container">
        <h1>Manage Missing Items</h1>
        <div class="btn-group">
            <button class="btn" onclick="openAddModal()">Add Item</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Office Collection Centre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['user_id'];
                $stmt = $conn->prepare("SELECT id, name, picture, OfficeCollectionCentre FROM missing_items WHERE contributor_id = ? AND status='missing'");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Picture'><img src='data:image/jpeg;base64," . base64_encode($row['picture']) . "' alt='Item Image'></td>
                            <td data-label='Name'>" . htmlspecialchars($row['name']) . "</td>
                            <td data-label='Office Collection Centre'>" . htmlspecialchars($row['OfficeCollectionCentre']) . "</td>
                            <td data-label='Actions'>
                                <button class='edit-btn' onclick=\"openEditModal(" . $row['id'] . ")\">Edit</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
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

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Item</h2>
                <button class="close-btn" onclick="closeModal('editModal')">×</button>
            </div>
            <form id="editForm" action="edit-item.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="itemId" name="id">
                <label for="editName">Item Name</label>
                <input type="text" id="editName" name="name">

                <label for="editPicture">Upload Picture</label>
                <input type="file" id="editPicture" name="picture" accept="image/*">

                <label for="editOffice">Office Collection Centre</label>
                <select id="editOffice" name="OfficeCollectionCentre">
                    <option value="Centre A">Centre A</option>
                    <option value="Centre B">Centre B</option>
                    <option value="Centre C">Centre C</option>
                </select>

                <div class="modal-actions">
                    <button type="submit" class="save-btn">Save Changes</button>
                    <button type="button" class="close-btn" onclick="closeModal('editModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function openEditModal(id) {
            document.getElementById('editModal').style.display = 'flex';

            fetch(`get-item.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('itemId').value = data.id;
                    document.getElementById('editName').value = data.name || '';
                    document.getElementById('editOffice').value = data.OfficeCollectionCentre || '';
                });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>

    <script src="../include/idle-logout.js"></script>
</body>

</html>