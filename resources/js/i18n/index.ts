import { createI18n } from 'vue-i18n';
import {
    registerMessageCompiler,
    compile,
    registerMessageResolver,
    resolveValue,
    registerLocaleFallbacker,
    fallbackWithLocaleChain,
} from '@intlify/core-base';
import en from './en.json';
import de from './de.json';

// vue-i18n's package.json marks "sideEffects": false, so Vite tree-shakes
// the module-level registration calls from the vue-i18n entry file.
// We must register the compiler, resolver, and fallbacker explicitly.
registerMessageCompiler(compile);
registerMessageResolver(resolveValue);
registerLocaleFallbacker(fallbackWithLocaleChain);

const savedLocale = typeof localStorage !== 'undefined'
    ? localStorage.getItem('visionflow-locale') || 'en'
    : 'en';

const i18n = createI18n({
    locale: savedLocale,
    fallbackLocale: 'en',
    messages: { en, de },
});

export default i18n;
