@extends('_layouts.master')

@push('meta')
    <meta property="og:title" content="About {{ $page->siteName }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="A little bit about {{ $page->siteName }}" />
@endpush

@section('body')
    <h1>About</h1>

    <img src="/assets/img/about.png"
        alt="About image"
        class="flex rounded-full h-64 w-64 bg-contain mx-auto md:float-right my-6 md:ml-10">

    <p class="mb-6">Hi, I'm Tony Messias. I work as a Software Engineer for <a href="https://madewithlove.be/">madewithlove</a>. I have been building web applications since late 2010, so it's been a while.</p>

    <p class="mb-6">This is my personal page, expect articles about experiments or thoughts on Web Development, Linux, or just Programming in general.</p>

    <p class="mb-6">You can find me as <a href="https://twitter.com/tony0x01">@tony0x01</a> on Twitter and I also have a
        <a href="https://www.youtube.com/channel/UCGtfJjAR5JeBPAmxN_ZHx4Q?view_as=subscriber">YouTube channel</a> where I occasionally share a technical video of some experiment or talk.</p>

    <p class="mb-6">I'm a co-founder and one of the organizers of <a href="https://www.meetup.com/pt-BR/maceio-dev-meetup/">Maceio DEV Meetup</a>, where we monthly join to discuss a varied of topics related to software development (from sales, to development practices/techniques, to infrastructure). If you are in town (Maceió/AL, Brazil), reach out.</p>

    <p class="mb-6">Some of the topics I'm interested:</p>

    <ul>
        <li>PHP/Laravel</li>
        <li>Elixir/Phoenix</li>
        <li>React/VueJS</li>
        <li>Docker/Kubernetes</li>
        <li>Ubuntu</li>
        <li>Golang</li>
        <li>Ruby/Rails</li>
    </ul>

    <p class="mb-6">Ideas are my own.</p>
@endsection
