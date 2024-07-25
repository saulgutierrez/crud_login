document.querySelector('.menu').addEventListener('click', function() {
    document.querySelectorAll('.navbar li:not(.menu)').forEach(function(item) {
        item.classList.toggle('hidden');
    });
});