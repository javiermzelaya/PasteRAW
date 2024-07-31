document.addEventListener('DOMContentLoaded', (event) => {
    const themeToggleButton = document.getElementById('toggle-theme');
    const themeStyle = document.getElementById('theme-style');
    const lightTheme = 'light_mode.css';
    const darkTheme = 'dark_mode.css';

    // Check local storage for theme preference
    const currentTheme = localStorage.getItem('theme') || lightTheme;
    themeStyle.setAttribute('href', currentTheme);

    // Update button text based on current theme
    themeToggleButton.textContent = currentTheme === lightTheme ? 'Switch to Dark Mode' : 'Switch to Light Mode';

    themeToggleButton.addEventListener('click', () => {
        const newTheme = themeStyle.getAttribute('href') === lightTheme ? darkTheme : lightTheme;
        themeStyle.setAttribute('href', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Update button text
        themeToggleButton.textContent = newTheme === lightTheme ? 'Switch to Dark Mode' : 'Switch to Light Mode';
    });
});