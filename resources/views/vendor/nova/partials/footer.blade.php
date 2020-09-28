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
    @if (class_exists('Lasallesoftware\Laravelapp\Version'))
        <br />{{ Lasallesoftware\Laravelapp\Version::PACKAGE }}, v{{ Lasallesoftware\Laravelapp\Version::VERSION }} ({{ Lasallesoftware\Laravelapp\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Librarybackend\Version'))
        <br />{{ Lasallesoftware\Librarybackend\Version::PACKAGE }}, v{{ Lasallesoftware\Librarybackend\Version::VERSION }} ({{ Lasallesoftware\Librarybackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Novabackend\Version'))
        <br />{{ Lasallesoftware\Novabackend\Version::PACKAGE }}, v{{ Lasallesoftware\Novabackend\Version::VERSION }} ({{ Lasallesoftware\Novabackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Blogbackend\Version'))
        <br />{{ Lasallesoftware\Blogbackend\Version::PACKAGE }}, {{ Lasallesoftware\Blogbackend\Version::VERSION }} ({{ Lasallesoftware\Blogbackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Podcastbackend\Version'))
        <br />{{ Lasallesoftware\Podcastbackend\Version::PACKAGE }}, {{ Lasallesoftware\Podcastbackend\Version::VERSION }} ({{ Lasallesoftware\Podcastbackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Podcastnovabackend\Version'))
        <br />{{ Lasallesoftware\Podcastnovabackend\Version::PACKAGE }}, {{ Lasallesoftware\Podcastnovabackend\Version::VERSION }} ({{ Lasallesoftware\Podcastnovabackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Podcastrssfeedbackend\Version'))
        <br />{{ Lasallesoftware\Podcastrssfeedbackend\Version::PACKAGE }}, {{ Lasallesoftware\Podcastrssfeedbackend\Version::VERSION }} ({{ Lasallesoftware\Podcastrssfeedbackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Backup\Version'))
        <br />{{ Lasallesoftware\Backup\Version::PACKAGE }}, {{ Lasallesoftware\Backup\Version::VERSION }} ({{ Lasallesoftware\Backup\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Lasalleuibackend\Version'))
        <br />{{ Lasallesoftware\Lasalleuibackend\Version::PACKAGE }}, {{ Lasallesoftware\Lasalleuibackend\Version::VERSION }} ({{ Lasallesoftware\Lasalleuibackend\Version::RELEASEDATE }})
    @endif

    @if (class_exists('Lasallesoftware\Contactformbackend\Version'))
        <br />{{ Lasallesoftware\Contactformbackend\Version::PACKAGE }}, {{ Lasallesoftware\Contactformbackend\Version::VERSION }} ({{ Lasallesoftware\Contactformbackend\Version::RELEASEDATE }})
    @endif
</p>
