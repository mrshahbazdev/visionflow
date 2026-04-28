import { createI18n } from 'vue-i18n';
import en from './en.json';
import de from './de.json';

const savedLocale = typeof localStorage !== 'undefined'
    ? localStorage.getItem('visionflow-locale') || 'en'
    : 'en';

const i18n = createI18n({
    legacy: false,
    locale: savedLocale,
    fallbackLocale: 'en',
    messages: { en, de },
});

export default i18n;
