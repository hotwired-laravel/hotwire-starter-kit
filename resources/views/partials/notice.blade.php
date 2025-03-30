<div data-turbo-temporary data-controller="bridge--toast flash" data-bridge-message="{{ $message }}"
    data-action="animationend->flash#remove"
    class="py-1 px-4 leading-7 text-center text-accent-foreground rounded-full bg-accent/70 transition-all shadow animate-appear-then-fade-out">
    {{ $message }}
</div>
