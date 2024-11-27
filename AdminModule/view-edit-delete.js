function viewItem(id) {
  const modal = document.getElementById("viewModal");

  if (!modal) {
    console.error("View modal is not present in the DOM.");
    return;
  }

  modal.style.display = "flex";

  fetch(`get-item-details.php?id=${id}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        alert(data.error);
        closeModal("viewModal");
        return;
      }

      console.log("Fetched Data:", data); // Log fetched data

      // Check all required elements
      const itemName = document.getElementById("viewItemName");
      const itemPicture = document.getElementById("viewItemPicture");
      const contributorName = document.getElementById("viewContributorName");
      const contributorPicture = document.getElementById(
        "viewContributorPicture"
      );
      const status = document.getElementById("viewStatus");
      const created = document.getElementById("viewCreated");
      const office = document.getElementById("viewOffice");

      if (
        !itemName ||
        !itemPicture ||
        !contributorName ||
        !contributorPicture ||
        !status ||
        !created ||
        !office
      ) {
        console.error("Missing elements in the DOM.");
        return;
      }

      // Populate modal fields with data
      itemName.innerText = data.name || "N/A";
      itemPicture.src = `data:image/jpeg;base64,${data.picture}` || "";
      contributorName.innerText = data.contributor_name || "N/A";
      contributorPicture.src =
        `data:image/jpeg;base64,${data.contributor_picture}` || "";
      status.innerText = data.status || "N/A";
      created.innerText = data.created || "N/A";
      office.innerText = data.OfficeCollectionCentre || "N/A";
    })
    .catch((error) => {
      console.error("Error fetching item details:", error);
      alert("An error occurred while fetching item details. Please try again.");
    });
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function openEditModal(id) {
  document.getElementById("editModal").style.display = "flex";

  fetch(`get-item.php?id=${id}`)
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("editItemId").value = data.id;
      document.getElementById("editName").value = data.name || "";
      document.getElementById("editOffice").value =
        data.OfficeCollectionCentre || "";
    });
}

function deleteItem(id) {
  if (confirm("Are you sure you want to delete this item?")) {
    window.location.href = `delete-item.php?id=${id}`;
  }
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}
