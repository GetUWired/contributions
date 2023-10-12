# TailwindCSS in WordPress

This guide outlines how to add Vite and TailwindCSS to a WordPress theme, how to
set up a manifest to properly get the outputted filenames with hashes included
(to break the cache), and how to properly enqueue the built files.

1. Initialize NPM:

    ```bash
    npm init
    ```

2. Install Vite with:

    ```bash
    npm create vite@latest
    ```

    and select `Vanilla`

3. Add a Vite config file as `vite.config.js` containing:

    ```js
    /** @type {import('vite').UserConfig} */
    export default {
      build: {
        rollupOptions: {
          input: {
            tailwindcss: "./src/frontend/tailwind.css",
          },
        },
        manifest: true,
      },
    };
    ```

4. Install TailwindCSS and its dependencies, and initialize TailwindCSS:

    ```bash
    npm install -D tailwindcss postcss autoprefixer
    npx tailwindcss init -p
    ```

5. Add the directory/directories containing the template files you're going to
    use TailwindCSS in to your `tailwind.config.js` file, and add a prefix to
    avoid conflicts:

    ```js
    /** @type {import('tailwindcss').Config} */
    export default {
      content: ["./src/**/*.{php,html,js,jsx}"],
      theme: {
        extend: {},
      },
      plugins: [],
      prefix: "tw-",
    };
    ```

6. Set up your TailwindCSS file at `.src/frontend/tailwind.css`:

    ```css
    @tailwind base;
    @tailwind components;
    @tailwind utilities;
    ```

7. Add some functions to your theme to handle getting and enqueueing the outputted
    files, like those in [Utils.php](Utils.php).

8. Enqueue your outputted scripts with the above functions with something like:

    ```php
     $this->utils->enqueue_bundled_style('tailwindcss', 'src/frontend/tailwind.css', [], '1.0.0', 'all');
    ```

9. Add a start script to `package.json` to build as you go:

    ```json
    "scripts": {
        "start": "vite build --watch"
    },
    ```

10. Start Vite and use TailwindCSS as normal, but with `tw-` prefixing all class
    names.

        ```bash
        npm run start
        ```
