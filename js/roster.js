document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("appointment-modal");
  const closeButton = document.querySelector(".close");

  function openAppointmentModal(message) {
    const appointmentForm = document.getElementById("appointment-form");
    if (appointmentForm) appointmentForm.reset();
    
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

            if (day.appointments && day.appointments.length > 0) {
              dayDiv.classList.add("has-appointments");
              dayDiv.addEventListener("click", () => {
                openAppointmentModal(`Appointments for ${day.date}: ${day.appointments.join(", ")}`);
              });
            } else {
              dayDiv.addEventListener("click", () => {
                openAppointmentModal(`Create a new appointment for ${day.date}`);
              });
            }
            
            calendarGrid.appendChild(dayDiv);
          });
        }
      })
      .catch(error => console.error("Error loading calendar:", error));
  }

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
  }

  loadCalendar();
});
