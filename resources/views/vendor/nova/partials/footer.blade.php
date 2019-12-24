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
    &copy; {{ date('Y') }} The South LaSalle Trading Corporation
    <span class="px-1">&middot;</span>
    By Bob Bloom


    <br /><br />
    {{ App\Version::PACKAGE }}, v{{ App\Version::VERSION }} ({{ App\Version::RELEASEDATE }})
    @if (class_exists('Lasallesoftware\Library\Version'))
        <br />{{ Lasallesoftware\Library\Version::PACKAGE }}, v{{ Lasallesoftware\Library\Version::VERSION }} ({{ Lasallesoftware\Library\Version::RELEASEDATE }})
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

    @if (class_exists('Lasallesoftware\Lasalleui\Version'))
        <br />{{ Lasallesoftware\Lasalleui\Version::PACKAGE }}, v{{ Lasallesoftware\Lasalleui\Version::VERSION }} ({{ Lasallesoftware\Lasalleui\Version::RELEASEDATE }})
    @endif
</p>
