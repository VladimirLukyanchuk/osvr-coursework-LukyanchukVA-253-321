document.addEventListener('DOMContentLoaded', () => {
    const planner = document.querySelector('#planner');
    if (planner) {
        const checkboxes = planner.querySelectorAll('input[name="topics[]"]');
        const counter = planner.querySelector('#selected-count');
        const update = () => {
            const count = [...checkboxes].filter((item) => item.checked).length;
            counter.textContent = 'Выбрано материалов: ' + count;
        };
        checkboxes.forEach((item) => item.addEventListener('change', update));
        update();
    }

    document.querySelectorAll('.delete-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Удалить запись? Это действие нельзя отменить.')) event.preventDefault();
        });
    });
});
