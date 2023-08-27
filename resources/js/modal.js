
//find element with data-modal attribute
const modals = document.querySelectorAll('[data-modal]');

//add cliock event listener to each element
modals.forEach(function (trigger) {
    trigger.addEventListener('click', function (event) {
        event.preventDefault();
        const modal = document.querySelector(trigger.dataset.modal);
        modal.classList.remove('hidden');
        const exits = modal.querySelectorAll('.modal-exit');
        exits.forEach(function (exit) {
            exit.addEventListener('click', function (event) {
                event.preventDefault();
                modal.classList.add('hidden');
            });
        });
    });
});