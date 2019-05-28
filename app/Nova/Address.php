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
use Lasallesoftware\Library\Nova\Fields\AddressMaplink;
use Lasallesoftware\Library\Nova\Fields\Comments;
use Lasallesoftware\Library\Nova\Fields\LookupDescription;
use Lasallesoftware\Library\Nova\Fields\Uuid;
use Lasallesoftware\Library\Nova\Resources\BaseResource;
use Lasallesoftware\Library\Rules\AddressesUniqueRule;

// Laravel Nova classes
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

// Laravel class
use Illuminate\Http\Request;

// Laravel facade
use Illuminate\Support\Facades\Auth;


/**
 * Class Address
 *
 * @package App\Nova
 */
class Address extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Lasallesoftware\\Library\\Profiles\\Models\\Address';

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
    public static $title = 'address_calculated';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'address_calculated',
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
        return __('lasallesoftwarelibrary::general.resource_label_plural_addresses');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('lasallesoftwarelibrary::general.resource_label_singular_addresses');
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

            Heading::make(__('lasallesoftwarelibrary::general.field_heading_addresses_address')),

            Text::make('Title')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Address', 'address_calculated')
                ->sortable()
                ->onlyOnIndex(),

            Place::make('Address', 'address_line_1')
                ->state('province')
                ->sortable()
                ->hideFromIndex()
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_required') .'</li>
                     </ul>'
                )
                ->rules('required', new AddressesUniqueRule),
            //Place::make('Address', 'address_line_1')->hideFromIndex()->countries(['US', 'CA']),

            Text::make('Address Line 2')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Address Line 3')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Address Line 4')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('City')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Province')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Country::make('Country')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Postal Code', 'postal_code')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Latitude', 'latitude')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make('Longitude', 'longitude')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),


            Heading::make(__('lasallesoftwarelibrary::general.field_heading_addresses_general_info')),

            LookupDescription::make('description')
                ->hideFromIndex(),

            Comments::make('comments')
                ->hideFromIndex(),

            Image::make('Featured Image','featured_image')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            AddressMaplink::make('map_link'),

            Heading::make( __('lasallesoftwarelibrary::general.field_heading_address_type'))
                ->hideFromDetail(),

            BelongsTo::make('Address Type', 'lookup_address_type', 'App\Nova\Lookup_address_type')
                ->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_required') .'</li>
                     </ul>'
                )
                ->rules('required')
                ->sortable(),

            BelongsToMany::make('Person')
                ->singularLabel('Person'),


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

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * This method is in the Laravel\Nova\PerformsQueries trait.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        self::getRelatableQueryForThisResource($query);
    }
}
