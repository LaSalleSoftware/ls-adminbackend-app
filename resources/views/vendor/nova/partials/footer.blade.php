<p class="mt-8 text-center text-xs text-80">
    LaSalle Software integrates the commercial first party Laravel administration package <a
        href="https://nova.laravel.com"
        target="_blank"
        class="text-primary dim no-underline">
        Laravel Nova
    </a>
    <br />
    <span class="px-1">&middot;</span>
    &copy; {{ date('Y') }} Laravel LLC - By Taylor Otwell, David Hemphill, and Steve Schoger.
    <span class="px-1">&middot;</span>
    v{{ Laravel\Nova\Nova::version() }}

    <br /><br />

    <a
        href="https://lasallesoftware.ca"
        target="_blank"
        class="text-primary dim no-underline">
        LaSalle Software
    </a>
    <span class="px-1">&middot;</span>
    &copy; 2019-{{ date('Y') }} The South LaSalle Trading Corporation
    <span class="px-1">&middot;</span>
    By Bob Bloom


    <br /><br />
    {{ App\Version::PACKAGE }}, v{{ App\Version::VERSION }} ({{ App\Version::RELEASEDATE }})
    @if (class_exists('Lasallesoftware\Librarybackend\Version'))
        <br />{{ Lasallesoftware\Librarybackend\Version::PACKAGE }}, v{{ Lasallesoftware\Librarybackend\Version::VERSION }} ({{ Lasallesoftware\Librarybackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Novabackend\Version'))
        <br />{{ Lasallesoftware\Novabackend\Version::PACKAGE }}, v{{ Lasallesoftware\Novabackend\Version::VERSION }} ({{ Lasallesoftware\Novabackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Blogbackend\Version'))
        <br />{{ Lasallesoftware\Blogbackend\Version::PACKAGE }}v, {{ Lasallesoftware\Blogbackend\Version::VERSION }} ({{ Lasallesoftware\Blogbackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Backup\Version'))
        <br />{{ Lasallesoftware\Backup\Version::PACKAGE }}, v{{ Lasallesoftware\Backup\Version::VERSION }} ({{ Lasallesoftware\Backup\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Lasalleuibackend\Version'))
        <br />{{ Lasallesoftware\Lasalleuibackend\Version::PACKAGE }}, v{{ Lasallesoftware\Lasalleuibackend\Version::VERSION }} ({{ Lasallesoftware\Lasalleuibackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Contactformbackend\Version'))
        <br />{{ Lasallesoftware\Contactformbackend\Version::PACKAGE }}, v{{ Lasallesoftware\Contactformbackend\Version::VERSION }} ({{ Lasallesoftware\Contactformbackend\Version::RELEASEDATE }})
    @endif
</p>
