<div id="notifications" class="fixed top-10 left-0 right-0 flex flex-col items-center justify-center space-y-2 z-10">
    @if (session()->has('notice'))
        @include('partials.notice', ['message' => session('notice')])
    @endif
</div>
