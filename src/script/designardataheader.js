document.addEventListener("DOMContentLoaded", function () {
    const calendarModal = new bootstrap.Modal(document.getElementById("calendarModal"));
    const calendarMonthYear = document.getElementById("calendarMonthYear");
    const calendarDays = document.getElementById("calendarDays");
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");

    let currentDate = new Date();

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();
        const firstDayIndex = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        calendarMonthYear.textContent = `${date.toLocaleString("default", { month: "long" })} ${year}`;

        // Limpar dias existentes
        calendarDays.innerHTML = "";

        // Preencher os espaços antes do primeiro dia do mês
        for (let i = 0; i < firstDayIndex; i++) {
            const blankDay = document.createElement("div");
            calendarDays.appendChild(blankDay);
        }

        // Adicionar os dias do mês
        for (let day = 1; day <= daysInMonth; day++) {
            const dayButton = document.createElement("button");
            const isToday = today.toDateString() === new Date(year, month, day).toDateString();

            dayButton.textContent = day;
            dayButton.classList.add("calendar-day");
            if (isToday) dayButton.classList.add("today");

            dayButton.dataset.date = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

            dayButton.addEventListener("click", function () {
                const selectedDate = this.dataset.date;
                window.location.href = `designar_tarefa.php?data_limite=${selectedDate}`;
            });

            calendarDays.appendChild(dayButton);
        }
    }

    // Navegação de meses
    prevMonthButton.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextMonthButton.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Exibir modal e inicializar calendário
    document.getElementById("calendar").addEventListener("click", function () {
        renderCalendar(currentDate);
        calendarModal.show();
    });
});
