console.log('loaded');
/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

store( 'notification', {
	actions: {
		toggle: () => {
			const context = getContext();
			//const state = getState();
			context.isOpen = ! context.isOpen;
		},
	},
	callbacks: {
		logIsOpen: () => {
			const { isOpen } = getContext();
			// Log the value of `isOpen` each time it changes.
			console.log( `Is notification open: ${ isOpen }` );
		},
	},
} );
