<?php

/**
 * This file is part of the LaSalle Software Administrative Back-end application.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-basicfrontend-app
 * @link       https://github.com/LaSalleSoftware/lsv2-adminbackend-app
 *
 */

namespace App\Nova;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Library\Nova\Fields\Comments;
use Lasallesoftware\Library\Nova\Fields\LookupDescription;
use Lasallesoftware\Library\Nova\Fields\BaseTextField as Text;
use Lasallesoftware\Library\Nova\Fields\Uuid;
use Lasallesoftware\Library\Nova\Resources\BaseResource;

// Laravel Nova classes
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Panel;

// Laravel class
use Illuminate\Http\Request;

// Laravel facade
use Illuminate\Support\Facades\Auth;


/**
 * Class Company
 *
 * @package App\Nova
 */
class Company extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Lasallesoftware\\Library\\Profiles\\Models\\Company';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Profiles';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];


    /**
     * Determine if this resource is available for navigation.
     *
     * Only the owner role can see this resource in navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return Personbydomain::find(Auth::id())->IsOwner() || Personbydomain::find(Auth::id())->IsSuperadministrator();
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('lasallesoftwarelibrary::general.resource_label_plural_companies');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('lasallesoftwarelibrary::general.resource_label_singular_companies');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('lasallesoftwarelibrary::general.field_name_name'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_required') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_unique') .'</li>
                     </ul>'
                )
                ->sortable()
            ->rules('required', 'max:255', 'unique:companies,name,{{resourceId}}'),

            LookupDescription::make('description'),

            Comments::make('comments'),

            Text::make( __('lasallesoftwarelibrary::general.field_name_profile'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Image::make( __('lasallesoftwarelibrary::general.field_name_featured_image'))
                ->disableDownload()
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),



            BelongsToMany::make('Person')->singularLabel('Person'),
            BelongsToMany::make('Address')->singularLabel('Address'),
            BelongsToMany::make('Email')->singularLabel('Email address'),
            BelongsToMany::make('Social')->singularLabel('Social site'),
            BelongsToMany::make('Telephone')->singularLabel('Telephone number'),
            BelongsToMany::make('Website')->singularLabel('Website'),



            Heading::make( __('lasallesoftwarelibrary::general.field_heading_system_fields'))
                ->hideFromDetail(),

            new Panel(__('lasallesoftwarelibrary::general.panel_system_fields'), $this->systemFields()),

            Uuid::make('uuid'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
