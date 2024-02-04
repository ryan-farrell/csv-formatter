// tailwind.config.js
import typography from '@tailwindcss/typography';
import forms from '@tailwindcss/forms';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
      // './storage/framework/views/*.php',
      './resources/**/*.blade.php',
      './resources/**/*.js',
      // './resources/**/*.vue',
    ],
    theme: {
      extend: {},
    },
    plugins: [
      typography,
      forms,
      aspectRatio,
    ],
}