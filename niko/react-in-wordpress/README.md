# React in WordPress

The steps below outline how to set up a new Gutenberg Block, and how to add a
React App to it.

1. [Create a block](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/)
   If you're setting up a single block in its own plugin, run the following
   from your `plugins/` directory:

   ```bash
   npx @wordpress/create-block@latest block-name
   ```

   If you're adding a block to an existing plugin, add a `src/` directory in its
   root directory, and within it, run:

   ```bash
   npx @wordpress/create-block@latest block-name --no-plugin
   ```

2. Initialize an NPM package in your plugin's root directory, and add necessary dependencies:

   **Necessary:**

   ```bash
   npm init
   npm install @wordpress/scripts @wordpress/blocks @wordpress/element
   ```

   **Optional:**

   ```bash
   npm install @wordpress/prettier-config
   ```

3. Register the block in your plugin with something like the following, which
   should be run on the `init` action hook:

   ```php
   public function enqueue_blocks()
   {
       register_block_type(
           BLOCK_NAME_PATH . 'build/block-name',
           array(
               'render_callback' => array($this, 'render_block_name_block')
           )
       );
   }

   public function render_block_name_block($attributes)
   {
       // Any additional data from WordPress you want to pass into the React app
       $attributes = [
           'name' => 'testuser',
           'age' => 'userage',
       ];

       return '<pre id="block-name-app">' . json_encode($attributes) . '</pre>';
   }
   ```

4. Add `frontend.jsx` to the block's directory, instantiating the React app with
   something like:

   ```js
   import { createRoot } from "@wordpress/element";
   import App from "./pages/App";

   const setupReactApp = () => {
     // Get the data from the HTML to eventually pass to the React app
     const element = document.getElementById("block-name-app");
     if (!element) {
       return;
     }

     if (!element.textContent) {
       element.textContent = "{}";
     }

     const data = JSON.parse(element.textContent);

     // Render the React app
     const root = createRoot(element);
     root.render(<App {...data} />);
   };

   window.addEventListener("DOMContentLoaded", setupReactApp);
   ```

5. Add this file to the plugin's `block.json` file as a `viewScript`:

   ```json
   {
     "viewScript": "file:./frontend.js"
   }
   ```

6. Start building your React app, pulling React functions out of
   `@wordpress/element` like:

   ```js
   import { useContext, useEffect, useState } from "@wordpress/element";
   ```
