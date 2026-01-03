/**
 * Contact Popup Handler
 * Handles opening and closing of contact us popup
 */
document.addEventListener('DOMContentLoaded', function () {
    const openBtn   = document.getElementById('newconOpen');
    const popup     = document.getElementById('newconPopup');
    const closeBtn  = document.getElementById('newconClose');
    const cancelBtn = document.getElementById('newconCancel');

    if (!openBtn || !popup || !closeBtn || !cancelBtn) {
        return;
    }

    function openPopup(e) {
        e.preventDefault();
        popup.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePopup() {
        popup.style.display = 'none';
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openPopup);
    closeBtn.addEventListener('click', closePopup);
    cancelBtn.addEventListener('click', closePopup);

    popup.addEventListener('click', function (ev) {
        if (ev.target === popup) closePopup();
    });

    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' && popup.style.display === 'flex') {
            closePopup();
        }
    });
});
