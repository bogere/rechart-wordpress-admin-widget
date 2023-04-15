import App from './App';
import { render } from '@wordpress/element';

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';

/**
 * Add the i18n localisation instance to your app
 */
import './i18n/config'

// Render the App component into the DOM
render( <App />, document.getElementById( 'jobplace' ) );
