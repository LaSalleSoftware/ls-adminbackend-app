<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\BlogTables;

use Tests\LaSalleDuskTestCase;

class BlogTablesBaseDuskTestCase extends LaSalleDuskTestCase
{
    public $loginOwnerBobBloom = [
        'email'    => 'bob.bloom@lasallesoftware.ca',
        'password' => 'secret',
    ];

    public $loginSuperadminDomain1 = [
        'email'    => 'sidney.bechet@blogtest.ca',
        'password' => 'secret',
    ];

    public $loginAdminDomain1 = [
        'email'    => 'robert.johnson@blogtest.ca',
        'password' => 'secret',
    ];

    public $newCategoryData = [
        'installed_domain_id' => 1,
        'title'               => 'News',
        'content'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis nam donec, la ultrices per. Vivamus vitae justo imperdiet magnis aenean, dis duis eget tristique leo, cras aliquet eleifend litora. Eros interdum praesent at mus litora mattis leo, duis ligula enim fames platea auctor rutrum massa, aptent cum volutpat parturient cras per.',
        'description'         => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est grav',
        'enabled'             => 1,
    ];

    public $editCategoryData = [
        'title'       => 'Local News',
    ];


    public $newTagData = [
        'installed_domain_id' => 3,
        'title'               => 'Blues',
        'description'         => 'Blues is a music genre and musical form originated by African Americans in the Deep South of the United States around the end of the 19th century.',
        'enabled'             => 1,
    ];

    public $editTagData = [
        'title'       => 'Blues Music',
    ];

    public $postTitles = [
        '1' => 'Biography of Blues Boy King on Domain 1',
        '2' => 'Biography of Robert Johnson on Domain 1',
        '3' => 'Biography of Stevie Ray Vaughan on Domain 1',
        '4' => 'Biography of Blues Boy King on Domain 2',
        '5' => 'Biography of Robert Johnson on Domain 2',
        '6' => 'Biography of Stevie Ray Vaughan on Domain 2',
        '7' => 'Biography of Blues Boy King on Domain 3',
        '8' => 'Biography of Robert Johnson on Domain 3',
        '9' => 'Biography of Stevie Ray Vaughan on Domain 3',
    ];

    public $postupdateTitles = [
        '1' => 'Not This Year - Again',
        '2' => 'An Important Update!',
        '3' => 'A Very Important Update!',
    ];

    // PLEASE NOTE THAT YOU CANNOT SPECIFY A CATEGORY ON A POST CREATE ANYMORE!! 
    // CATEGORY IS NULLABLE! ==> $table->integer('category_id')->unsigned()->nullable();
    // https://github.com/LaSalleSoftware/lsv2-blogbackend-pkg/blob/master/database/migrations/2019_06_03_183254_create_posts_table.php
    public $newPostData = [
        'installed_domain_id' => 1,
        'personbydomain_id'   => 1,
        'category_id'         => null, 
        'title'               => 'Lorem ipsum dolor sit amet consectetur',
        'slug'                => 'lorem-ipsum-dolor-sit-amet-consectetur',
        'content'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'excerpt'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'meta_description'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'featured_image'      => null,
        'enabled'             => 1,
        'publish_on'          => '2019-06-18',
    ];

    public $editPostData = [
        'installed_domain_id' => 1,
        'personbydomain_id'   => 1,
        'category_id'         => 1,
        'title'               => 'Editing lorem ipsum dolor sit amet consectetur',
        'slug'                => 'Editing-lorem-ipsum-dolor-sit-amet-consectetur',
        'content'             => 'Editing lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'excerpt'             => 'Editing lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'meta_description'    => 'Editing lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'featured_image'      => null,
        'enabled'             => 1,
        'publish_on'          => '2019-06-18',
    ];


    public $newPostupdateData = [
        'installed_domain_id' => 1,
        'personbydomain_id'   => 1,
        'post_id'             => 2,
        'post_title'          => 'Biography of Robert Johnson on Domain 1',
        'title'               => 'Lorem ipsum dolor sit amet consectetur',
        'content'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'excerpt'             => 'Lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'enabled'             => 1,
        'publish_on'          => '2019-06-18',
    ];

    public $editPostupdateData = [
        'installed_domain_id' => 1,
        'personbydomain_id'   => 1,
        'post_id'             => 2,
        'title'               => 'Editing lorem ipsum dolor sit amet consectetur',
        'slug'                => 'Editing-lorem-ipsum-dolor-sit-amet-consectetur',
        'content'             => 'Editing lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'excerpt'             => 'Editing lorem ipsum dolor sit amet consectetur adipiscing elit per, est gravida id cursus sagittis',
        'enabled'             => 1,
        'publish_on'          => '2019-06-18',
    ];
}
