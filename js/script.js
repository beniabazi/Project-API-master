document.addEventListener("DOMContentLoaded", function () {
  // Function to show the selected form and hide others
  function showForm(formId) {
    const forms = document.querySelectorAll(".form");
    forms.forEach((form) => form.classList.remove("active"));

    const activeForm = document.getElementById(formId);
    if (activeForm) {
      activeForm.classList.add("active");
    }
  }

  const formSubmissions = {
    "residents-form": {
      tableId: "residents-table",
      fields: ["resident-name", "resident-details", "emergency-contact", "care-plan"],
    },
    "equipment-form": {
      tableId: "equipment-table",
      fields: ["equipment-name", "category", "quantity", "condition"],
    },
    "family-form": {
      tableId: "family-table",
      fields: ["resident-id", "family-member-name", "relationship", "contact-details"],
    },
    "needs-form": {
      tableId: "needs-table",
      fields: ["resident-id", "need-description", "priority", "service-provider"],
    },
    "medications-form": {
      tableId: "medications-table",
      fields: ["resident-id", "medication-name", "dosage", "frequency", "prescribing-doctor"],
    },
    "room-bookings-form": {
      tableId: "room-bookings-table",
      fields: ["resident-id", "room-number", "check-in-date", "check-out-date"],
    },
  };

  Object.keys(formSubmissions).forEach((formId) => {
    const form = document.getElementById(formId);
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        const { tableId, fields } = formSubmissions[formId];
        const table = document.getElementById(tableId).getElementsByTagName("tbody")[0];
        const row = table.insertRow();
        fields.forEach((field) => {
          row.insertCell().innerText = document.getElementById(field).value;
        });
        form.reset();
        const tableName = tableId.replace("-table", "");
        fetchDataFromServer(tableName);
      });
    }
  });

  // Event listeners for navigation links
  document.querySelectorAll(".nav ul li a").forEach((link) => {
    link.addEventListener("click", function (e) {
      const formId = this.getAttribute("data-form-id");

      if (formId) {
        e.preventDefault(); // Only prevent default if the link is tied to a form
        showForm(formId);
      }
      // No else block needed, as the link will default to normal behavior if no formId exists
    });
  });

  // Generate Reports form submission
  const generateReportsForm = document.querySelector("#generate-reports form");
  if (generateReportsForm) {
    generateReportsForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const reportType = document.getElementById("report-type").value;
      const startDate = document.getElementById("start-date").value;
      const endDate = document.getElementById("end-date").value;
      console.log(`Generating ${reportType} report from ${startDate} to ${endDate}`);
      // Send this data to the server to generate the report
    });
  }

  // Show the default section (e.g., residents-section)
  showForm("residents-section");

  // Modal functionality
  const modal = document.getElementById("modal");
  const closeButton = document.querySelector(".close-button");

  function openModal(content) {
    document.getElementById("modal-body").innerHTML = content;
    modal.classList.remove("hidden");
  }

  function closeModal() {
    modal.classList.add("hidden");
  }

  closeButton.addEventListener("click", closeModal);

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  // Function to fetch data from the server
  function fetchDataFromServer(tableName) {
    fetch(`fetch_data.php?table=${tableName}`)
      .then((response) => response.json())
      .then((data) => {
        updateTable(tableName, data);
      })
      .catch((error) => console.error("Error:", error));
  }

  // Function to update table with fetched data
  function updateTable(tableName, data) {
    const tableBody = document.querySelector(`#${tableName}-table tbody`);
    tableBody.innerHTML = ""; // Clear existing rows

    data.forEach((row) => {
      const tr = document.createElement("tr");
      Object.values(row).forEach((value) => {
        const td = document.createElement("td");
        td.textContent = value;
        tr.appendChild(td);
      });
      tableBody.appendChild(tr);
    });
  }

  // Fetch data for all tables when the page loads
  const tables = ["equipment", "family_members", "individual_needs", "medications", "residents", "room_bookings"];
  tables.forEach((table) => fetchDataFromServer(table));

  // Refresh data every 5 minutes (300000 milliseconds)
  setInterval(() => {
    tables.forEach((table) => fetchDataFromServer(table));
  }, 300000);

  // Dropdown menu functionality
  document.querySelectorAll(".dropbtn").forEach((dropbtn) => {
    dropbtn.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default behavior if necessary
      const dropdownContent = this.nextElementSibling;
      dropdownContent.classList.toggle("show");
    });
  });

  // Close the dropdown if the user clicks outside of it
  window.addEventListener("click", function (event) {
    if (!event.target.matches(".dropbtn")) {
      document.querySelectorAll(".dropdown-content").forEach((dropdown) => {
        dropdown.classList.remove("show");
      });
    }
  });
});
