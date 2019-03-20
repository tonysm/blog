@extends('_layouts.master')

@push('meta')
    <meta property="og:title" content="About {{ $page->siteName }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="A little bit about {{ $page->siteName }}" />
@endpush

@section('body')
    <img src="/assets/img/about.jpg"
         alt="About image"
         class="flex rounded-full h-64 w-64 bg-contain mx-auto md:float-right my-6 md:ml-10">

    <p class="mb-6">
        Hi, I'm Tony Messias. I've working writing web applications since 2010. I work remotely for <a href="https://madewithlove.be/">madewithlove</a> as a Software Engineer.
    </p>

    <p class="mb-6">
        Every now and then I try to share things about what I'm learning through my <a href="{{ '/articles' }}">articles</a> and <a href="https://www.youtube.com/channel/UCGtfJjAR5JeBPAmxN_ZHx4Q?view_as=subscriber">screencasts</a>. You can also find me on Twitter at <a href="https://twitter.com/tony0x01">@tony0x01</a>.
    </p>

    <p class="mb-6">
        I'm a co-founder and one of the organizers of a Meetup group called <a href="https://www.meetup.com/pt-BR/maceio-dev-meetup/">Maceio DEV Meetup</a>, where we try to group monthly to discuss many different topics, from servers to selling software. If you're in town (Maceió/AL), reach out.
    </p>
@endsection
