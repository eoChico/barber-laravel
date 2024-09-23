document.addEventListener("DOMContentLoaded", function() {
    // Deseleciona o select ao recarregar a página
    const barbeiroSelect = document.getElementById('barbers');
    barbeiroSelect.value = ''; // Define o valor como vazio, deselecionando qualquer opção
});

document.addEventListener("DOMContentLoaded", function() {
    const calendarDays = document.getElementById("calendarDays");
    const currentMonthText = document.getElementById("currentMonth");
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");
    const barberSelect = document.getElementById("barbers"); // Barber select
    const serviceList = document.getElementById("list-services"); // <ul> for services
    const registerButton = document.getElementById('register');
    const timeSelect = document.getElementById('times');
    let currentDate = new Date();
    let selectedDayDiv = null; // To keep track of the selected day
    let availability = {}; // Store availability data

    // When the barber select changes
    barberSelect.addEventListener('change', function() {
        const barberId = this.value;

        // Clear the time select
        timeSelect.innerHTML = '<option value="">Selecione um horário</option>';

        // If no barber is selected, do nothing
        if (!barberId) return;

        // Fetch availability when a barber is selected
        fetchAvailability(barberId);
    });

    // Handle appointment registration
    registerButton.addEventListener('click', function() {
        const selectedServices = Array.from(document.querySelectorAll('#list-services input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);

        if (selectedDayDiv == null) {
            showModal("Atenção", "Escolha o Dia do Agendamento.");
            return;
        }

        const day = selectedDayDiv.textContent;
        const barberId = Number(barberSelect.value);
        const appointmentData = {
            barber_id: barberId,
            appointment_date: `${currentDate.getFullYear()}-${currentDate.getMonth() + 1}-${day}`,
            start_time: timeSelect.value,
            services: selectedServices // IDs of services
        };

        if (!barberSelect.value) {
            showModal("Atenção", "Escolha um Barbeiro.");
            return;
        }
        if (!timeSelect.value) {
            showModal("Atenção", "Escolha um Horário.");
            return;
        }
        if (selectedServices.length === 0) {
            showModal("Atenção", "Escolha pelo menos um serviço.");
            return;
        }

        // Make POST request to schedule
        fetch('/schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(appointmentData)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request Error');
                }
                return response.json();
            })
            .then(data => {
                console.log('Appointment successfully scheduled:', data);
                showModal("Sucesso", "Agendamento marcado com sucesso!", true);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while scheduling the appointment.');
            });
    });

    // Function to fetch availability and services
    function fetchAvailability(barberId) {
        fetch(`/get-services/${barberId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request Error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.availability) {
                    availability = data.availability;
                    renderCalendar(currentDate); // Re-render calendar with new availability
                }

                if (data.services) {
                    renderServices(data.services); // Render the list of services
                }

                if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error fetching availability:', error);
                alert('Error fetching availability: ' + error.message);
            });
    }


    function showModal(title, message, shouldReload = false) {
        const modal = document.getElementById('generic-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');

        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modal.classList.remove('hidden');

        const closeModalButton = document.getElementById('close-modal');
        closeModalButton.onclick = function() {
            modal.classList.add('hidden');
            if (shouldReload) {
                location.reload(); // Recarrega a página se necessário
            }
        };

        // Fechar modal ao clicar fora dele
        modal.onclick = function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                if (shouldReload) {
                    location.reload(); // Recarrega a página se necessário
                }
            }
        };
    }
    // Function to render services


    function renderServices(services) {
        serviceList.innerHTML = ''; // Clear the previous service list

        services.forEach((service, index) => {
            const li = document.createElement('li');
            li.innerHTML = `
            <input type="checkbox" id="service-${service.id}" value="${service.id}" class="hidden peer" ${index === 0 ? 'checked' : ''}>
            <label for="service-${service.id}" class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                <div class="block">
                    <div class="w-full text-lg font-semibold">${service.name}</div>
                    <div class="w-full text-sm font-semibold">R$ ${parseFloat(service.value).toFixed(2)}</div>
                    <div class="w-full text-sm">${service.duration} min</div>
                </div>
            </label>
        `;

            serviceList.appendChild(li); // Add the item to the list

            // Add event listener to the checkbox
            const checkbox = li.querySelector('input[type="checkbox"]');
            checkbox.addEventListener('change', function() {
                const selectedServices = Array.from(document.querySelectorAll('#list-services input[type="checkbox"]:checked'))
                    .map(checkbox => checkbox.value);
                timeSelect.innerHTML = '<option value="">Selecione um horário</option>';
                if (selectedDayDiv) {
                    // If a day is selected, fetch availability again
                    const day = selectedDayDiv.textContent;
                    const barberId = Number(barberSelect.value);
                    if (barberId) {
                        fetchAvailableTimes(barberId, `${currentDate.getFullYear()}-${currentDate.getMonth() + 1}-${day}`, selectedServices);
                    }
                }
            });
        });
    }


    // Function to render the calendar
    function renderCalendar(date) {
        calendarDays.innerHTML = "";
        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Set the time to 00:00 for date comparison

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const lastDateOfMonth = new Date(year, month + 1, 0).getDate();

        // Show the current month and year
        const options = { month: 'long', year: 'numeric' };
        currentMonthText.textContent = date.toLocaleDateString('pt-BR', options);

        // Fill in empty days before the first day of the month
        for (let i = 0; i < firstDayOfMonth; i++) {
            const emptyDiv = document.createElement('div');
            calendarDays.appendChild(emptyDiv);
        }

        // Fill in the days of the month
        for (let day = 1; day <= lastDateOfMonth; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.textContent = day;
            dayDiv.className = "py-2 rounded transition";
            dayDiv.style.cursor = "pointer";

            const dayDate = new Date(year, month, day); // Create a date object for each day
            const dayOfWeek = dayDate.getDay(); // Get the day of the week (0 = Sunday, 1 = Monday, etc.)
            const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const dayName = daysOfWeek[dayOfWeek];
            const isAvailable = availability[dayName] === 1;

            // If the day has passed or is not available, disable it visually
            if (dayDate < today || !isAvailable) {
                dayDiv.classList.add("bg-gray-200", "cursor-not-allowed", "text-gray-400");
                dayDiv.style.cursor = "not-allowed";
                dayDiv.removeEventListener("click", handleDayClick); // Remove click event, if exists
            } else {
                dayDiv.classList.add("hover:bg-blue-500", "hover:text-white", "cursor-pointer", "text-white");
                // Add click event to select the day
                dayDiv.addEventListener("click", function() {
                    handleDayClick(event, barberSelect.value);
                });
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    function handleDayClick(event, barberId) {
        const dayDiv = event.currentTarget;

        if (selectedDayDiv) {
            // Remove blue background from previously selected day
            selectedDayDiv.classList.remove("bg-blue-500");
            selectedDayDiv.classList.add("hover:bg-blue-500", "hover:text-white");
        }

        // Set blue background for clicked day
        dayDiv.classList.remove("hover:bg-blue-500", "hover:text-white");
        dayDiv.classList.add("bg-blue-500", "text-white");

        // Log the clicked day, month, and year to the console
        const day = dayDiv.textContent;
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        console.log(`Day: ${day}, Month: ${month}, Year: ${year}`);

        // Update selectedDayDiv to the current day
        selectedDayDiv = dayDiv;

        // Clear the time select
        timeSelect.innerHTML = '<option value="">Selecione um horário</option>';
        const selectedServices = Array.from(document.querySelectorAll('#list-services input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);
        if (selectedServices.length === 0) {
            showModal("Atenção", "Escolha pelo menos um serviço")
            return
        }
        // Fetch available times for the selected day and barber
        if (barberId) {
            fetchAvailableTimes(barberId, `${year}-${month}-${day}`, selectedServices);
        }
    }

    // Function to fetch available times
    function fetchAvailableTimes(barberId, selectedDate, selectedServices) {
        fetch(`/get-times/${barberId}`, {
            method: 'POST', // Change to POST to send the date
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                date: selectedDate,
                services: selectedServices
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request Error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.horarios && Array.isArray(data.horarios)) {
                    // Iterate over the time array and add to the select
                    data.horarios.forEach(time => {
                        let option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeSelect.appendChild(option);
                    });
                } else if (data.error) {
                    alert(data.error);  // Show the error, if any
                }
            })
            .catch(error => {
                console.error('Error fetching times:', error);
                alert('Error fetching times: ' + error.message);
            });
    }

    // Navigation between months
    prevMonthButton.addEventListener("click", function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextMonthButton.addEventListener("click", function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Render the calendar with the current month
    renderCalendar(currentDate);
});

