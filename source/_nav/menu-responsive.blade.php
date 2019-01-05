<nav id="js-nav-menu" class="nav-menu hidden lg:hidden">
    <ul class="list-reset my-0">
        <li class="pl-4">
            <a
                title="{{ $page->siteName }} Articles"
                href="{{ $page->baseUrl }}/blog"
                class="nav-menu__item hover:text-blue {{ $page->isActive('/blog') ? 'active text-blue' : '' }}"
            >Articles</a>
        </li>
        <li class="pl-4">
            <a
                title="{{ $page->siteName }} About"
                href="{{ $page->baseUrl }}/about"
                class="nav-menu__item hover:text-blue {{ $page->isActive('/about') ? 'active text-blue' : '' }}"
            >About</a>
        </li>
    </ul>
</nav>
