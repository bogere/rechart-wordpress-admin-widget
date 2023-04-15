import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

/**
 * Configure the i18n instance
 * The i18n instance will hold all of your translations, the current language
 */
i18n.use( initReactI18next ).init( {
	fallbackLng: 'en',
	lng: 'en', //default language
	resources: {
		en: {
			translations: require( './locales/en/translations.json' ),
		},
		fr: {
			translations: require( './locales/fr/translations.json' ),
		},
	},
	ns: [ 'translations' ],
	defaultNS: 'translations',
} );

i18n.languages = [ 'en', 'fr' ];

export default i18n;
