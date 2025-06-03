import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './vendor/kingmaker/filament-flex-layout/resources/views/**/*.blade.php',
    ],
    safelist: [
        { pattern: /gap-/ },
    ],
}
