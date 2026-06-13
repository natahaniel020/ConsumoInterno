import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';


/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // ── Design system de ConsumoInterno (fuente de verdad) ────────
                // Nota: Los colores de Stitch (primary: #000000) son prototipos.
                // Los tokens del proyecto son los colores de producción.

                // Superficies
                'background':                '#f7f9fb',
                'surface':                   '#f7f9fb',
                'surface-bright':            '#f7f9fb',
                'surface-dim':               '#d8dadc',
                'surface-variant':           '#e0e3e5',
                'surface-tint':              '#0c56d0',

                'surface-container-lowest':  '#ffffff',
                'surface-container-low':     '#f2f4f6',
                'surface-container':         '#eceef0',
                'surface-container-high':    '#e6e8ea',
                'surface-container-highest': '#e0e3e5',

                // On-superficies
                'on-background':             '#191c1e',
                'on-surface':                '#191c1e',
                'on-surface-variant':        '#45464d',

                // Primario
                'primary':                   '#003d9b',
                'on-primary':                '#ffffff',
                'primary-container':         '#0052cc',
                'on-primary-container':      '#c4d2ff',
                'primary-fixed':             '#dae2ff',
                'primary-fixed-dim':         '#b2c5ff',
                'on-primary-fixed':          '#001848',
                'on-primary-fixed-variant':  '#0040a2',
                'inverse-primary':           '#b2c5ff',

                // Secundario
                'secondary':                 '#0058be',
                'on-secondary':              '#ffffff',
                'secondary-container':       '#2170e4',
                'on-secondary-container':    '#54647a',
                'secondary-fixed':           '#d3e4fe',
                'secondary-fixed-dim':       '#b7c8e1',
                'on-secondary-fixed':        '#0d1c2f',
                'on-secondary-fixed-variant':'#38485d',

                // Terciario
                'tertiary':                  '#7b2600',
                'on-tertiary':               '#ffffff',
                'tertiary-container':        '#a33500',
                'on-tertiary-container':     '#ffc6b2',
                'tertiary-fixed':            '#ffdbcf',
                'tertiary-fixed-dim':        '#ffb59b',
                'on-tertiary-fixed':         '#380d00',
                'on-tertiary-fixed-variant': '#812800',

                // Error
                'error':                     '#ba1a1a',
                'on-error':                  '#ffffff',
                'error-container':           '#ffdad6',
                'on-error-container':        '#93000a',

                // Bordes y outlines
                'outline':                   '#76777d',
                'outline-variant':           '#c6c6cd',

                // Inversas
                'inverse-surface':           '#2d3133',
                'inverse-on-surface':        '#eff1f3',
            },

            borderRadius: {
                DEFAULT: '0.25rem',
                lg:      '0.5rem',
                xl:      '0.75rem',
                full:    '9999px',
            },

            spacing: {
                // Tokens de espaciado de Stitch (usados en las vistas)
                'xs':            '4px',
                'sm':            '8px',
                'md':            '16px',
                'lg':            '24px',
                'xl':            '32px',
                'gutter':        '24px',
                'base':          '4px',
                'container-max': '1440px',

                // Tokens estructurales del layout
                'sidebar-width':  '256px',   /* w-64 = 256px */
                'header-height':  '64px',
            },

            fontFamily: {
                sans:        ['Inter', ...defaultTheme.fontFamily.sans],
                'headline-lg': ['Inter', ...defaultTheme.fontFamily.sans],
                'headline-md': ['Inter', ...defaultTheme.fontFamily.sans],
                'headline-sm': ['Inter', ...defaultTheme.fontFamily.sans],
                'body-lg':     ['Inter', ...defaultTheme.fontFamily.sans],
                'body-md':     ['Inter', ...defaultTheme.fontFamily.sans],
                'label-md':    ['Inter', ...defaultTheme.fontFamily.sans],
                'label-sm':    ['Inter', ...defaultTheme.fontFamily.sans],
            },

            fontSize: {
                'headline-lg': ['32px', { lineHeight: '40px',  letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-md': ['24px', { lineHeight: '32px',  letterSpacing: '-0.01em', fontWeight: '600' }],
                'headline-sm': ['20px', { lineHeight: '28px',  fontWeight: '600' }],
                'body-lg':     ['16px', { lineHeight: '24px',  fontWeight: '400' }],
                'body-md':     ['14px', { lineHeight: '20px',  fontWeight: '400' }],
                'label-md':    ['12px', { lineHeight: '16px',  letterSpacing: '0.01em', fontWeight: '500' }],
                'label-sm':    ['11px', { lineHeight: '14px',  fontWeight: '600' }],
            },
        },
    },

   
};