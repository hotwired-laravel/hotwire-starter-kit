# Laravel + Hotwire Starter Kit

## Introduction

A community-built starter kit to build [Hotwired](https://hotwired.dev/) apps with Laravel.

Hotwire is an alternative approach to building modern web applications without using much JavaScript by sending HTML instead of JSON over the wire. It also comes ready to be integrated with [Hotwire Native](https://native.hotwired.dev/), which is a web-first framework for building native mobile apps (if you want to see an example [Native Android app](https://github.com/hotwired-laravel/hotwire-starter-kit-android-example)). It provides you with all the tools you need to leverage your web app and build great mobile apps.

This Hotwire Starter Kit comes with [Turbo Laravel](https://turbo-laravel.com/), [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel) for a #nobuild frontend setup (but it also works with Vite, if you want to use that), [Stimulus Laravel](https://github.com/hotwired-laravel/stimulus-laravel) to make it easier to make Stimulus controllers and Hotwire Native Bridge Components, it also comes with the [Hotwire Hotreload](https://github.com/hotwired-laravel/hotreload) package installed as a dev dependency to make development easier, and the [daisyUI](https://daisyui.com/) component library integrated.

## Installation
You can use the Laravel Installer to setup the Hotwire Starter Kit.

```bash
laravel new my-app --using=hotwired-laravel/hotwire-starter-kit
```

### Local Development

We ship with [Solo](https://github.com/soloterm/solo) by default. To start the processes, you may run:

```bash
composer run dev
```

But we also ship with a [Procfile](./Procfile) so you may run it with [foreman](https://github.com/ddollar/foreman) or [node-foreman](https://github.com/strongloop/node-foreman) or, our recommended way since it only requires a single binary, using [Overmind](https://github.com/DarthSim/overmind). Download the binary, put it somewhere registered in your `$PATH` var, then run:

```bash
composer run dev:overmind
```

### Deployment

Deploying a Hotwired Laravel app is just like deploying any other Laravel app. It only differs a bit because we're using [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel), so make sure you add the steps to your deploy script:

```bash
# Build the Tailwind CSS styles...
php artisan tailwindcss:download
php artisan tailwindcss:build --prod

# Copy JavaScript files and generate the production manifest...
php artisan importmap:optimize
```

If you're uploading your assets to a CDN (like in Vapor), make sure you set the `ASSET_URL` before running these commands, since the Importmap manifest will be created using the full URL that relies on this flag.

For more information, head out to the [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel#deploying-your-app) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel) documentation.

## Contributing

Thank you for considering contributing to our starter kit! Please, feel free to make issues and send pull requests if you think something could be done differently.

## License

The Laravel + Hotwire Starter Kit is open-sourced software licensed under the MIT license.

