## Interactivity API Example

Basic WordPress Interactivity API Demo

### Prerequisites

NPM
WordPress 6.5 +
A local development environment - [WP ENV](https://developer.wordpress.org/block-editor/getting-started/devenv/get-started-with-wp-env/) ,[Local](https://localwp.com/), 

### What are we building?

We're going to make a simple reading mode block, in this example it simply sets a `dark mode` state that, we're also going to build a 'notification' block that changes it's own appearance depending on the reading mode state and also handles whether it is expanded or not. 

[Demo](http://bigbite.im/i/1d5G8f)

### Step 1 - Setup the skeleton block

Create skeleton block using create block with the interactivity flag

```
cd  {insert-local-path}/www/wp-content/plugins
```

```
npx @wordpress/create-block --template @wordpress/create-block-interactive-template
```

Use `reading-mode` as the block slug when prompted and here we use `bigbite` for the `internal namespace`

Watch the plugin for changes 

```
cd reading-mode
```

```
npm run start
```

### Step 2 - Add a store 

The block should be scaffolded and recognise your changes. You should see a `view.js` file in the `src` directory. This is the file that will be loaded on the front end when the block is used. The `block.json` file should already include this so you shouldn't need to do anything to make sure it's loaded.

The interactivy API uses 'stores' to provide actions, essentially just functions that will run when a user interaction occurs. The store can also be used to set `state`, the naming here is confusing if you've used state in other instances such as react and various other libraries. `state` in the context of the interactivity API actually just sets values that the full page may need access to. It might be better to think of this as global state.

As we're going to set a reading mode that many other blocks might need to react to we can use state in this instance. add the following to the `view.js` file to setup the store and add some default state

```
import { store, getContext } from '@wordpress/interactivity';

 const { state } = store( "bigbite", {
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
  });
```

All we're doing above is setting state for `isDark` and making it false by default. We're also setting an action to toggle the `isDark` state when the user interacts with the toggle.

### Step 2 - Add the render for the toggle button

Open the `render.php` inside the block `src` folder and replace the markup with the following code, this renders an input and a label for the mode toggle, we will style this later

```
<div 
	<?php echo $wrapper_attributes; ?>
	>
	<div class="mode-button">
		<input class="mode-button-input" type="checkbox" id="switch<?php echo $unique_id_append; ?>" name="mode">
		<label for="switch<?php echo $unique_id_append; ?>">Toggle</label>
	</div>
</div>
```

At the moment the render has no connection to the interactivity API, we can enable this by using data attributes. Add the data attrbiutes below, these are called `directives` in the API docs:

```
<?php

// Generate unique id for aria-controls.
$unique_id          = wp_unique_id('p-');
$wrapper_attributes = get_block_wrapper_attributes();
?>

<div 
    <?php echo $wrapper_attributes; ?>
    data-wp-interactive="bigbite"
    data-wp-class--isDark="state.isDark"
    >
    <div class="mode-button">
        <input class="mode-button-input" type="checkbox" id="switch<?php echo $unique_id_append; ?>" name="mode">
        <label data-wp-on--click="actions.setMode" for="switch<?php echo $unique_id_append; ?>">Toggle</label>
    </div>
</div>

```

`data-wp-interactive` is a directive that enables the interactivity API for the element and it's children and the `data-wp-class` directive will add or remove a class on a HTML element. The `data-wp-on--click` directive provides the key to this simle functionality, it instruct the interactivy API to run the `setMode` function in our action everytime the element is clicked. If we look back at our `setMode` function you will notice it toggles the global `isDark` value:

```
setMode: () => {
	// state is global
	console.log('is dark');
	state.isDark = !state.isDark;
}
```

### Step 3 - Add CSS for the toggle button

We won't go through the code here, just add this to the `style.scss` file for the block and you should have a styled toggle:

```

.wp-block-bigbite-reading-mode input[type=checkbox] {
	height: 0;
	width: 0;
	visibility: hidden;
  }
  
  .wp-block-bigbite-reading-mode label {
	cursor: pointer;
	text-indent: -9999px;
	width: 55px;
	height: 30px;
	background: #FFBD07;
	margin: 0 auto;
	display: flex;
	justify-content: center;
	align-items: center;
	-webkit-border-radius: 100px;
	-moz-border-radius: 100px;
	border-radius: 100px;
	position: relative;
  }
  
  .wp-block-bigbite-reading-mode label:after {
	content: '';
	background: #fff;
	width: 20px;
	height: 20px;
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	position: absolute;
	top: 5px;
	left: 4px;
    transition: cubic-bezier(0.68, -0.55, 0.27, 01.55) 320ms;
  }
  
  .wp-block-bigbite-reading-mode input:checked + label {
	background: black;
  }
  
  .wp-block-bigbite-reading-mode input:checked + label:after {
	left: calc(100% - 5px);
	-webkit-transform: translateX(-100%);
	-moz-transform: translateX(-100%);
	-ms-transform: translateX(-100%);
	-o-transform: translateX(-100%);
	transform: translateX(-100%);
  }

```

### Step 4 - Test the block

In the WordPress dashboard:

- Activate the block plugin in the dashboard
- Add a page
- Add a reading mode block
- Save the page
- Open the page and click the toggle

You should see the toggle animate, so in this simple example you have some basic functionality that toggles the class on an element basedd on the value of some global state.

### Step 5 - Add another block 

We're now going to add another block to our project that will also use the global state. For the sake of this tutoiral we're going to create a basic 'notification' block that will change it's styles depending on the global state. It will also toggle it's own state when clicked

Firstly add the block:

```
npx @wordpress/create-block --template @wordpress/create-block-interactive-template
```

use `notification` as the block slug when promopted and here we use `bigbite` for the `internal namespace`

Activate the plugin in the dashboard

Add a store for your block, in the view.js file for the block replace the existing code with the following:

```
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
```

This will toggle the `isOpen` status and also runs a callback so you can see in the console when any value changes

Now add the render for the block, inside the `render.php` for the new block:

```
<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 */

// Generate unique id for aria-controls.
$unique_id = wp_unique_id( 'p-' );

?>

<div 
	data-wp-interactive="readingmodes"
	data-wp-class--isDark="state.isDark"
	data-wp-bind--hidden="!state.isNotificationsHidden">
	<div
		<?php echo get_block_wrapper_attributes(); ?>
		data-wp-interactive="notifications"
		data-wp-context='{ "isOpen": false }'
		data-wp-watch="callbacks.logIsOpen"
	>
		<div class="teaser">
			<div>
				<p><?php echo esc_html( __( 'Notification Block', 'bigbite' ) ); ?></p>
			</div>
			<button
				data-wp-on--click="actions.toggle"
				data-wp-bind--aria-expanded="context.isOpen"
				data-wp-class--isOpen="context.isOpen"
				aria-controls="<?php echo esc_attr( $unique_id ); ?>"
			>
			<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M7 10L12 15L17 10" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			</button>
		</div>

		<div
			id="<?php echo esc_attr( $unique_id ); ?>"
			data-wp-bind--hidden="!context.isOpen"
			class="content"
		>
			<?php
				echo wp_kses_post( html_entity_decode( $content ) );
			?>
		</div>
	</div>
</div>
```

The `data-wp-class--isDark="state.isDark"` will make sure the block has a class that binds to the global `isDark` state, so whenever the user clicks the dark mode toggle the class will be updated. 

The block also handles it's own interactvity, so when the arrow button is clicked the toggle action is run:

```
<button
	data-wp-on--click="actions.toggle"
```

The notifcation content uses a bind directive to toggle it's visibility depending on whether the notification is `open`or not:

```
<div
	id="<?php echo esc_attr( $unique_id ); ?>"
	data-wp-bind--hidden="!context.isOpen"
	class="content"
>
	<?php
		echo wp_kses_post( html_entity_decode( $content ) );
	?>
</div>
```



Add some styles for the block in the `styles.scss` for the block:

```
 .wp-block-bigbite-notification {
	width: 100%;
	margin-bottom: 20px;
	padding: 0 20px;
	background-color: white;
	border-radius: 5px;
	box-shadow: 0px 15px 19px 0px rgb( 0, 0, 0, .2 );
}

.wp-block-bigbite-notification .content {
	padding-bottom: 20px;
}

.wp-block-bigbite-notification .teaser {
	display: flex;
	align-items: center;
	justify-content: space-between;
}

.wp-block-bigbite-notification button.isopen {
	transform: rotate(180deg);
}

.isdark .wp-block-bigbite-notification {
	background-color: black;
	color: white;
}

.isdark .wp-block-bigbite-notification button {
	background: none;
}

.isdark .wp-block-bigbite-notification p { 
	background: black;
	color: white;
}

.isdark .wp-block-bigbite-notification button svg path {
	stroke: white;
}
```

To allow you to add inner blocks to the notification replace the contents of the `src/edit.js` for your block, this will allow you to add some custom text for your notification content:

```
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InnerBlocks />
		</div>
	);
}
```

Finally add a `save.js` file and include it in the index.js so that the inner block content is saved:

save.js:

```
import { InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	return <InnerBlocks.Content />;
}
```

index.js:
```
import Save from './save';

 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	save: Save
} );
```

### Step 6 - Test the blocks

- Return to the previous page in the dashboard that you were editing
- Add some notifications blocks to the page, add some content and save the page
- View the front end of the website

You should now have notification blocks that you can expand and close, the reading mode toggle should toggle the styles on the notifcation.


### Finished!

It's obvious that this simple example could be replicated eaily with some custom JavaScript, however using the interactivity API would provide benefits at scale and the extensive directives supplied with the API could significantly reduce the amount of custom JavaScript you need to write on the front end to respond to common user interactions. Dom manipulation can often be difficult to maintain and embracing the interacrtivity API could reolve many pain points. 


Further Reading:

