/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'reading-mode', {
	state: {
		isDark: false,
	  },
	  actions: {
		setMode: () => {
		  // state is global
		  state.isDarkMode = !state.isDarkMode;
		},
	  },
} );
