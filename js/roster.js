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
    // ... your form submissions configuration
  };

  Object.keys(formSubmissions).forEach((formId) => {
    const form = document.getElementById(formId);
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        // ... form submission handling
      });
    }
  });

  // Function to save data to the server and check the response
  function saveDataToServer(tableName, formData) {
    fetch(`save_data.php?table=${tableName}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        openAppointmentModal("Data saved successfully.");
      } else {
        openAppointmentModal("Failed to save data. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      openAppointmentModal("An error occurred while saving data.");
    });
  }

  // Event listeners for navigation links
  document.querySelectorAll(".nav ul li a").forEach((link) => {
    link.addEventListener("click", function (e) {
      const formId = this.getAttribute("data-form-id");

      if (formId) {
        e.preventDefault(); // Only prevent default if the link is tied to a form
        showForm(formId);
      }
    });
  });

  // Modal functionality
  const modal = document.getElementById("appointment-modal");
  const closeButton = document.querySelector(".close");

  function openAppointmentModal(message) {
    document.querySelector("#appointment-modal .modal-content p").textContent = message;
    modal.style.display = "block";
  }

  function closeAppointmentModal() {
    modal.style.display = "none";
  }

  if (closeButton) {
    closeButton.addEventListener("click", closeAppointmentModal);
  }

  window.onclick = function(event) {
    if (event.target === modal) {
      closeAppointmentModal();
    }
  };

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

  // Load calendar function
  function loadCalendar() {
    const staffId = document.getElementById("staff").value;
    const currentMonthYear = document.getElementById("currentMonthYear").textContent;

    const [month, year] = currentMonthYear.split(" ");
    const monthIndex = new Date(Date.parse(month +" 1, 2022")).getMonth();
    
    fetch(`load_calendar.php?staff_id=${staffId}&month=${monthIndex + 1}&year=${year}`)
      .then((response) => response.json())
      .then((data) => {
        const calendarGrid = document.getElementById("calendarGrid");
        calendarGrid.innerHTML = ""; // Clear existing calendar

        // Generate calendar days
        data.days.forEach((day) => {
          const dayDiv = document.createElement("div");
          dayDiv.className = "calendar-day";
          dayDiv.textContent = day.date;
          
          // Add appointment details if any
          if (day.appointments && day.appointments.length > 0) {
            dayDiv.classList.add("has-appointments");
            dayDiv.addEventListener("click", function () {
              openAppointmentModal(`Appointments for ${day.date}: ${day.appointments.join(", ")}`);
            });
          }
          
          calendarGrid.appendChild(dayDiv);
        });
      })
      .catch((error) => console.error("Error loading calendar:", error));
  }

  // Initialize calendar on page load
  loadCalendar();
});
