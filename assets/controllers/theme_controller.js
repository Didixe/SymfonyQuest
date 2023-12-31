import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ["body"];
    // darkMode = false;

    connect() {
        this.darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false;
        this.updateTheme();
    }
    toggleDarkMode(event) {
        // if (!this.bodyTarget.getAttribute('data-bs-theme')) {
        //     this.bodyTarget.setAttribute('data-bs-theme', 'dark');
        // } else {
        //     this.bodyTarget.setAttribute('data-bs-theme', '');
        // }
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.updateTheme();
    }

    updateTheme() {
        this.bodyTarget.setAttribute('data-bs-theme', this.darkMode ? 'dark' : '');
    }
}
