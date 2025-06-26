const toggle = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');
const content = document.querySelector('.main-content');

toggle.addEventListener('click', () => {
sidebar.classList.toggle('open');
content.classList.toggle('shift');
});


