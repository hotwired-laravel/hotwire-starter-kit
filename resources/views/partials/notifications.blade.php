<div id="notifications"
    class="fixed top-10 left-0 right-0 lg:[:where([data-layout=sidebar])_&]:top-15 lg:[:where([data-layout=sidebar])_&]:left-[250px] flex flex-col items-center justify-center space-y-2 z-10">
    @if (session()->has('notice'))
        @include('partials.notice', ['message' => session('notice')])
    @endif
</div>
