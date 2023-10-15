import './bootstrap';

const menuButton = document.getElementById('menuButton');
const menuItems = document.getElementById('menuItems');

menuButton.addEventListener('click', () => {
    menuItems.classList.toggle('hidden');
});