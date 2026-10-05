# Laravel + Hotwire Starter Kit

A community-built starter kit to build [Hotwired](https://hotwired.dev/) apps with Laravel.

Hotwire is an alternative approach to building modern web applications without using much JavaScript by sending HTML instead of JSON over the wire.

This Hotwire Starter Kit comes with:

- [Turbo Laravel](https://turbo-laravel.com/)
- [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel) for a #nobuild frontend setup (but it also works with Vite, if you want to use that)
- [Stimulus Laravel](https://github.com/hotwired-laravel/stimulus-laravel) to make it easier to create Stimulus controllers and Hotwire Native Bridge Components
- [Hotwire Hotreload](https://github.com/hotwired-laravel/hotreload) installed as a dev dependency to make development easier
- [daisyUI](https://daisyui.com/) component library integrated

### Hotwire Native

It also comes ready to be integrated with [Hotwire Native](https://native.hotwired.dev/), which is a web-first framework for building native mobile apps. It provides you with all the tools you need to leverage your web app and build great mobile apps.

If you want to see an example, check out this [Native Android app](https://github.com/hotwired-laravel/hotwire-starter-kit-android-example).

## Requirements

- PHP 8.3+
- [Composer](https://getcomposer.org/)
- The [Laravel Installer](https://laravel.com/docs/installation#installing-php)

No Node.js required.

## Installation

You can use the Laravel Installer to set up the Hotwire Starter Kit.

```bash
laravel new my-app --using=hotwired-laravel/hotwire-starter-kit --pest --no-node
```

If you want teams support, make sure you use the `teams` branch (`dev-teams`):

```bash
laravel new my-app --using=hotwired-laravel/hotwire-starter-kit:dev-teams --pest --no-node
```

## Local Development

We ship with a [Procfile](./Procfile), so you may run it with [foreman](https://github.com/ddollar/foreman), [node-foreman](https://github.com/strongloop/node-foreman) or, our recommended way since it only requires a single binary, [Overmind](https://github.com/DarthSim/overmind). Download the binary, put it somewhere in your `$PATH`, then run:

```bash
composer run dev
```

The `dev` script runs `overmind start` under the hood. If you're using foreman or node-foreman instead, run `foreman start` (or `nf start`) directly.

### Running with Docker

If you prefer Docker, the starter kit ships with a [Laravel Sail](https://laravel.com/docs/sail) `compose.yaml` file:

```bash
vendor/bin/sail up -d
```

## Deployment

Deploying a Hotwired Laravel app is just like deploying any other Laravel app. It only differs a bit because we're using [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel), so make sure you add these steps to your deploy script:

```bash
# Build the Tailwind CSS styles...
php artisan tailwindcss:download
php artisan tailwindcss:build --prod

# Copy JavaScript files and generate the production manifest...
php artisan importmap:optimize
```

If you're uploading your assets to a CDN (like in Vapor), make sure you set the `ASSET_URL` before running these commands, since the Importmap manifest will be created using the full URL, which relies on this environment variable.

We also ship with a production [Dockerfile](./Dockerfile) (based on [serversideup/php](https://serversideup.net/open-source/docker-php/)) that already runs these steps for you.

For more information, head over to the [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel#deploying-your-app) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel) documentation.

## Contributing

Thank you for considering contributing to our starter kit! Please feel free to open issues and send pull requests if you think something could be done differently.

## License

The Laravel + Hotwire Starter Kit is open-sourced software licensed under the [MIT license](./LICENSE).
