<!DOCTYPE html>
<html>

<head>
    <title>{{config('app.name')}} | Pulse</title>
    <link rel="icon" href="{{asset('img/icon.png')}}" type="image/png" sizes="16x16">
</head>

<x-pulse>
    <livewire:pulse.servers cols="full" />

    <livewire:pulse.usage cols="4" rows="2" />

    <livewire:pulse.queues cols="4" />

    <livewire:pulse.cache cols="4" />

    <livewire:pulse.slow-queries cols="8" />

    <livewire:pulse.exceptions cols="6" />

    <livewire:pulse.slow-requests cols="6" />

    <livewire:pulse.slow-jobs cols="6" />

    <livewire:pulse.slow-outgoing-requests cols="6" />
</x-pulse>

</html>