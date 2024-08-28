document.addEventListener("DOMContentLoaded", function () {
<<<<<<< HEAD
  const modal = document.getElementById("appointment-modal");
  const closeButton = document.querySelector(".close");

  function openAppointmentModal(message) {
    const appointmentForm = document.getElementById("appointment-form");
    if (appointmentForm) appointmentForm.reset();
    
    document.querySelector("#appointment-modal .modal-content p").textContent = message;
    modal.style.display = "block";
  }
  
=======

  const modal = document.getElementById("appointment-modal");
  const closeButton = document.querySelector(".close");
  
  // Show the form by ID
  function showForm(formId) {
    document.querySelectorAll(".form").forEach(form => form.classList.remove("active"));
    const activeForm = document.getElementById(formId);
    if (activeForm) activeForm.classList.add("active");
  }

  // Save data to the server
  function saveDataToServer(tableName, formData) {
    fetch(`save_data.php?table=${tableName}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        openAppointmentModal("Data saved successfully.");
      } else {
        openAppointmentModal("Failed to save data. Please try again.");
      }
    })
    .catch(error => {
      console.error("Error:", error);
      openAppointmentModal("An error occurred while saving data.");
    });
  }

  // Open modal with a message
  function openAppointmentModal(message) {
    if (modal) {
      document.querySelector("#appointment-modal .modal-content p").textContent = message;
      modal.style.display = "block";
    }
  }

  // Close modal
>>>>>>> fb14e8fdee72b90a0df1ff5364a2cad274d9a209
  function closeAppointmentModal() {
    if (modal) modal.style.display = "none";
  }

  if (closeButton) {
    closeButton.addEventListener("click", closeAppointmentModal);
  }

  window.onclick = function(event) {
    if (event.target === modal) {
      closeAppointmentModal();
    }
  };

<<<<<<< HEAD
=======
  // Load calendar data
>>>>>>> fb14e8fdee72b90a0df1ff5364a2cad274d9a209
  function loadCalendar() {
    const staffId = document.getElementById("staff").value;
    const currentMonthYear = document.getElementById("currentMonthYear").textContent;
    const [month, year] = currentMonthYear.split(" ");
    const monthIndex = new Date(Date.parse(month + " 1, 2022")).getMonth();
    
    fetch(`load_calendar.php?action=fetch_appointments&staff_id=${staffId}&month=${monthIndex + 1}&year=${year}`)
      .then(response => response.json())
      .then(data => {
        const calendarGrid = document.getElementById("calendarGrid");
        if (calendarGrid) {
          calendarGrid.innerHTML = "";
          data.days.forEach(day => {
            const dayDiv = document.createElement("div");
            dayDiv.className = "calendar-day";
            dayDiv.textContent = day.date;
<<<<<<< HEAD

=======
            
>>>>>>> fb14e8fdee72b90a0df1ff5364a2cad274d9a209
            if (day.appointments && day.appointments.length > 0) {
              dayDiv.classList.add("has-appointments");
              dayDiv.addEventListener("click", () => {
                openAppointmentModal(`Appointments for ${day.date}: ${day.appointments.join(", ")}`);
              });
<<<<<<< HEAD
            } else {
              dayDiv.addEventListener("click", () => {
                openAppointmentModal(`Create a new appointment for ${day.date}`);
              });
=======
>>>>>>> fb14e8fdee72b90a0df1ff5364a2cad274d9a209
            }
            
            calendarGrid.appendChild(dayDiv);
          });
        }
      })
      .catch(error => console.error("Error loading calendar:", error));
  }

<<<<<<< HEAD
  function changeMonth(direction) {
    const currentMonthYear = document.getElementById("currentMonthYear");
    if (currentMonthYear) {
      let [month, year] = currentMonthYear.textContent.split(" ");
      const monthIndex = new Date(Date.parse(month +" 1, 2022")).getMonth();
      const newDate = new Date(year, monthIndex + direction);
      const newMonth = newDate.toLocaleString('default', { month: 'long' });
      const newYear = newDate.getFullYear();
      currentMonthYear.textContent = `${newMonth} ${newYear}`;
      loadCalendar();
    }
  }

  document.getElementById("delete-appointment").addEventListener("click", function () {
    const reason = prompt("Please enter the reason for deleting this appointment:");
    if (reason) {
      deleteAppointment(reason);
    }
  });

  function deleteAppointment(reason) {
    // Implement the deletion logic here
    console.log("Deleting appointment with reason:", reason);
    // Call the server to delete the appointment
=======
  // Handle appointment form submission
  const appointmentForm = document.getElementById("appointment-form");
  if (appointmentForm) {
    appointmentForm.addEventListener("submit", function (e) {
      e.preventDefault();
      
      const formData = {
        date: document.getElementById("appointment-date").value,
        time: document.getElementById("appointment-time").value,
        needs: document.getElementById("appointment-details").value,
        staff_id: document.getElementById("staff").value
      };

      fetch("load_calendar.php?action=handle_appointment", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          openAppointmentModal("Appointment saved successfully.");
          loadCalendar();
        } else {
          openAppointmentModal("Failed to save appointment. Please try again.");
        }
      })
      .catch(error => {
        console.error("Error:", error);
        openAppointmentModal("An error occurred while saving the appointment.");
      });
    });
>>>>>>> fb14e8fdee72b90a0df1ff5364a2cad274d9a209
  }

  loadCalendar();

  // Change month event listeners
  const prevMonthButton = document.querySelector('.date-selector button:nth-of-type(1)');
  const nextMonthButton = document.querySelector('.date-selector button:nth-of-type(2)');

  if (prevMonthButton) {
    prevMonthButton.addEventListener('click', () => changeMonth(-1));
  }

  if (nextMonthButton) {
    nextMonthButton.addEventListener('click', () => changeMonth(1));
  }

  // Change the displayed month
  function changeMonth(direction) {
    const currentMonthYear = document.getElementById("currentMonthYear");
    if (currentMonthYear) {
      let [month, year] = currentMonthYear.textContent.split(" ");
      const monthIndex = new Date(Date.parse(month +" 1, 2022")).getMonth();
      const newDate = new Date(year, monthIndex + direction);
      const newMonth = newDate.toLocaleString('default', { month: 'long' });
      const newYear = newDate.getFullYear();
      currentMonthYear.textContent = `${newMonth} ${newYear}`;
      loadCalendar();
    }
  }
});
