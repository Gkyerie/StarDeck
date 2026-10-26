import './bootstrap';

document.querySelectorAll('[data-date-picker]').forEach((picker) => {
    const valueInput = picker.querySelector('[data-date-value]');
    const displayInput = picker.querySelector('[data-date-display]');
    const toggleButton = picker.querySelector('[data-date-toggle]');
    const calendar = picker.querySelector('[data-date-calendar]');

    if (!valueInput || !displayInput || !toggleButton || !calendar) {
        return;
    }

    const parseDate = (value) => {
        const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value);

        if (!match) {
            return null;
        }

        const date = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));

        return date.getFullYear() === Number(match[1])
            && date.getMonth() === Number(match[2]) - 1
            && date.getDate() === Number(match[3])
            ? date
            : null;
    };

    const toIsoDate = (date) => {
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${date.getFullYear()}-${month}-${day}`;
    };

    const formatDate = (date) => new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(date);

    let selectedDate = parseDate(valueInput.value);
    let visibleMonth = selectedDate
        ? new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1)
        : new Date(new Date().getFullYear(), new Date().getMonth(), 1);

    const closeCalendar = () => {
        calendar.classList.add('hidden');
        toggleButton.setAttribute('aria-expanded', 'false');
    };

    const renderCalendar = () => {
        calendar.replaceChildren();

        const header = document.createElement('div');
        header.className = 'mb-3 flex items-center justify-between';

        const monthLabel = document.createElement('div');
        monthLabel.className = 'text-sm font-semibold text-white';
        monthLabel.textContent = new Intl.DateTimeFormat('id-ID', {
            month: 'long',
            year: 'numeric',
        }).format(visibleMonth);

        const navigation = document.createElement('div');
        navigation.className = 'flex gap-1';

        [
            ['Bulan sebelumnya', -1, '‹'],
            ['Bulan berikutnya', 1, '›'],
        ].forEach(([label, amount, symbol]) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.setAttribute('aria-label', label);
            button.className = 'flex h-8 w-8 items-center justify-center rounded-lg text-lg text-slate-300 hover:bg-white/10 hover:text-white';
            button.textContent = symbol;
            button.addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + amount, 1);
                renderCalendar();
            });
            navigation.append(button);
        });

        header.append(monthLabel, navigation);
        calendar.append(header);

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-7 gap-1 text-center';

        ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'].forEach((weekday) => {
            const label = document.createElement('span');
            label.className = 'py-1 text-[10px] font-semibold text-slate-500';
            label.textContent = weekday;
            grid.append(label);
        });

        const firstDay = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1);
        const mondayOffset = (firstDay.getDay() + 6) % 7;
        const daysInMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 0).getDate();

        for (let cell = 0; cell < 42; cell += 1) {
            const day = cell - mondayOffset + 1;
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'h-8 rounded-lg text-xs transition';

            if (day < 1 || day > daysInMonth) {
                button.disabled = true;
                button.classList.add('text-transparent');
                button.setAttribute('aria-hidden', 'true');
            } else {
                const date = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), day);
                const isoDate = toIsoDate(date);
                const isSelected = selectedDate && isoDate === toIsoDate(selectedDate);
                button.textContent = String(day);
                button.className += isSelected
                    ? ' bg-cyan-300 font-semibold text-slate-950'
                    : ' text-slate-300 hover:bg-white/10 hover:text-white';
                button.setAttribute('aria-label', formatDate(date));
                button.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
                button.addEventListener('click', () => {
                    selectedDate = date;
                    valueInput.value = isoDate;
                    displayInput.value = formatDate(date);
                    closeCalendar();
                });
            }

            grid.append(button);
        }

        calendar.append(grid);

        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'mt-3 w-full rounded-lg border border-slate-700 px-3 py-2 text-xs font-medium text-slate-300 hover:border-slate-500 hover:text-white';
        clearButton.textContent = 'Hapus tanggal';
        clearButton.addEventListener('click', () => {
            selectedDate = null;
            valueInput.value = '';
            displayInput.value = '';
            closeCalendar();
        });
        calendar.append(clearButton);
    };

    const openCalendar = () => {
        const currentDate = parseDate(valueInput.value);
        if (currentDate) {
            visibleMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        }
        renderCalendar();
        calendar.classList.remove('hidden');
        toggleButton.setAttribute('aria-expanded', 'true');
    };

    toggleButton.addEventListener('click', () => {
        if (calendar.classList.contains('hidden')) {
            openCalendar();
        } else {
            closeCalendar();
        }
    });

    displayInput.addEventListener('click', openCalendar);
    displayInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openCalendar();
        }
    });

    document.addEventListener('click', (event) => {
        if (!picker.contains(event.target)) {
            closeCalendar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeCalendar();
        }
    });
});
