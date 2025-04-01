# Laravel + Hotwire Starter Kit

## Introduction

A community-built starter kit to build [Hotwired](https://hotwired.dev/) apps with Laravel.

Hotwire is an alternative approach to building modern web applications without using much JavaScript by sending HTML instead of JSON over the wire. It also comes ready to be integrated with [Hotwire Native](https://native.hotwired.dev/), which is a web-first framework for building native mobile apps. It provides you with all the tools you need to leverage your web app and build great mobile apps.

This Hotwire Starter Kit comes with [Turbo Laravel](https://turbo-laravel.com/), [Tailwind CSS Laravel](https://github.com/tonysm/tailwindcss-laravel) and [Importmap Laravel](https://github.com/tonysm/importmap-laravel) for a #nobuild frontend setup (but it also works with Vite, if you want to use that), [Stimulus Laravel](https://github.com/hotwired-laravel/stimulus-laravel) to make it easier to make Stimulus controllers and Hotwire Native Bridge Components, it also comes with the [Hotwire Hotreload](https://github.com/hotwired-laravel/hotreload) package installed as a dev dependency to make development easier, and the [daisyUI](https://daisyui.com/) component library integrated.

### Local Development

We ship with [Solo](https://github.com/soloterm/solo) by default. To start the processes, you may run:

```bash
composer run dev
```

But we also ship with a [Procfile](./Procfile) so you may run it with [foreman](https://github.com/ddollar/foreman) or [node-foreman](https://github.com/strongloop/node-foreman) or, our recommended way since it only requires a single binary, using [Overmind](https://github.com/DarthSim/overmind). Download the binary, put it somewhere registered in your `$PATH` var, then run:

```bash
composer run dev:overmind
```

## Contributing

Thank you for considering contributing to our starter kit! Please, feel free to make issues and send pull requests if you think something could be done differently.

## License

The Laravel + Hotwire Starter Kit is open-sourced software licensed under the MIT license.

