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
use Lasallesoftware\Library\Nova\Resources\BaseResource;
use Lasallesoftware\Library\Nova\Fields\LookupTitle;
use Lasallesoftware\Library\Nova\Fields\LookupDescription;
use Lasallesoftware\Library\Nova\Fields\LookupEnabled;

// Laravel Nova classes
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Panel;

// Laravel classes
use Illuminate\Http\Request;

// Laravel facade
use Illuminate\Support\Facades\Auth;


/**
 * Class Lookup_lasallesoftware_event
 *
 * @package App\Nova
 */
class Lookup_lasallesoftware_event extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Lasallesoftware\\Library\\LaSalleSoftwareEvents\\Models\\Lookup_lasallesoftware_event';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Lookup Tables';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
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
        return Personbydomain::find(Auth::id())->IsOwner();
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('lasallesoftwarelibrary::general.resource_label_plural_lookup_lasallesoftware_events');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('lasallesoftwarelibrary::general.resource_label_singular_lookup_lasallesoftware_events');
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

            LookupTitle::make('title')
                ->creationRules('unique:lookup_lasallesoftware_events,title')
                ->updateRules('unique:lookup_lasallesoftware_events,title,{{resourceId}}'),

            LookupDescription::make('description'),

            LookupEnabled::make('enabled'),


            Heading::make( __('lasallesoftwarelibrary::general.field_heading_system_fields'))
                ->hideFromDetail(),

            new Panel(__('lasallesoftwarelibrary::general.panel_system_fields'), $this->systemFields()),
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
