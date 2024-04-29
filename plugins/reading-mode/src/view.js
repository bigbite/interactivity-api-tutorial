console.log('loaded');
/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'bigbite', {
	state: {
		isDark: false,
	  },
	  actions: {
		setMode: () => {
		  // state is global
		  console.log('is dark');
		  state.isDark = !state.isDark;
		},
	  },
} );
