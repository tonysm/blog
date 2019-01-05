<nav class="hidden lg:flex items-center justify-end text-lg">
    <a title="{{ $page->siteName }} Articles" href="{{ $page->baseUrl }}/blog"
        class="ml-6 text-grey-darker hover:text-blue-dark {{ $page->isActive('/blog') ? 'active text-blue-dark' : '' }}">
        Articles
    </a>

    <a title="{{ $page->siteName }} About" href="{{ $page->baseUrl }}/about"
        class="ml-6 text-grey-darker hover:text-blue-dark {{ $page->isActive('/about') ? 'active text-blue-dark' : '' }}">
        About
    </a>

</nav>
